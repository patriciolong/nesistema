<?php

namespace App\Services;

use App\Models\CajaSesion;
use App\Models\CajaMovimiento;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class CajaService
{
    /**
     * Get the active open cash session for a user.
     */
    public function getCajaAbierta(User|int $user): ?CajaSesion
    {
        $userId = $user instanceof User ? $user->id : $user;

        return CajaSesion::where('user_id', $userId)
            ->where('estado', 'abierta')
            ->latest('fecha_apertura')
            ->first();
    }

    /**
     * Check whether a user currently has an open cash register session.
     */
    public function hasCajaAbierta(User|int $user): bool
    {
        return $this->getCajaAbierta($user) !== null;
    }

    /**
     * Verify if user is allowed to make transactions and returns the active caja.
     * Throws an exception or returns null if no cash register is open.
     */
    public function requireCajaAbierta(User|int $user): CajaSesion
    {
        $caja = $this->getCajaAbierta($user);

        if (!$caja) {
            throw new Exception('No tienes una caja abierta actualmente. Debes abrir una caja para poder registrar cobros o trámites.');
        }

        return $caja;
    }

    /**
     * Opens a new cash register session for a user.
     */
    public function abrirCaja(User|int $user, float $montoApertura, ?string $observaciones = null): CajaSesion
    {
        $userModel = $user instanceof User ? $user : User::findOrFail($user);

        // Check if there is already an open session
        $cajaExistente = $this->getCajaAbierta($userModel);
        if ($cajaExistente) {
            throw new Exception('Ya tienes una caja abierta (# ' . $cajaExistente->id . '). Debes cerrarla antes de abrir una nueva.');
        }

        return CajaSesion::create([
            'user_id' => $userModel->id,
            'oficina' => $userModel->office ?? 'General',
            'monto_apertura' => $montoApertura,
            'fecha_apertura' => now(),
            'estado' => 'abierta',
            'total_sistema_efectivo' => $montoApertura, // Starts with initial cash
            'total_sistema_cheque' => 0,
            'total_sistema_transferencia' => 0,
            'total_sistema_tarjeta' => 0,
            'total_sistema_credito' => 0,
            'total_sistema_ingresos_extra' => 0,
            'total_sistema_egresos_extra' => 0,
            'total_sistema_total' => $montoApertura,
            'observaciones_apertura' => $observaciones,
        ]);
    }

    /**
     * Registers a transaction in the active cash register session.
     */
    public function registrarMovimiento(array $data): CajaMovimiento
    {
        return DB::transaction(function () use ($data) {
            $movimiento = CajaMovimiento::create([
                'caja_sesion_id' => $data['caja_sesion_id'],
                'user_id' => $data['user_id'],
                'cliente_id' => $data['cliente_id'] ?? null,
                'tipo' => $data['tipo'] ?? 'ingreso_tramite',
                'monto' => $data['monto'],
                'metodo_pago' => $data['metodo_pago'] ?? 'Efectivo',
                'banco_id' => $data['banco_id'] ?? null,
                'tarjeta_id' => $data['tarjeta_id'] ?? null,
                'pago_id' => $data['pago_id'] ?? null,
                'concepto' => $data['concepto'] ?? 'Movimiento de Caja',
                'numero_referencia' => $data['numero_referencia'] ?? null,
                'tramite_tipo' => $data['tramite_tipo'] ?? null,
                'tramite_id' => $data['tramite_id'] ?? null,
            ]);

            // Refresh system totals on the session
            $caja = CajaSesion::lockForUpdate()->find($data['caja_sesion_id']);
            if ($caja) {
                $this->actualizarTotalesSistema($caja);
            }

            return $movimiento;
        });
    }

    /**
     * Calculates and updates theoretical system totals for a cash session.
     */
    public function actualizarTotalesSistema(CajaSesion $caja): CajaSesion
    {
        $totales = $this->calcularTotalesSistema($caja);

        $caja->update([
            'total_sistema_efectivo' => $totales['efectivo'],
            'total_sistema_cheque' => $totales['cheque'],
            'total_sistema_transferencia' => $totales['transferencia'],
            'total_sistema_tarjeta' => $totales['tarjeta'],
            'total_sistema_credito' => $totales['credito'],
            'total_sistema_ingresos_extra' => $totales['ingresos_extra'],
            'total_sistema_egresos_extra' => $totales['egresos_extra'],
            'total_sistema_total' => $totales['total_general'],
        ]);

        return $caja;
    }

    /**
     * Computes the live totals based on registered movements.
     */
    public function calcularTotalesSistema(CajaSesion|int $caja): array
    {
        $cajaModel = $caja instanceof CajaSesion ? $caja : CajaSesion::findOrFail($caja);
        $movimientos = $cajaModel->movimientos()->get();

        $ingresosEfectivo = $movimientos->whereIn('tipo', ['ingreso_tramite', 'ingreso_abono', 'ingreso_extra'])->where('metodo_pago', 'Efectivo')->sum('monto');
        $egresosEfectivo = $movimientos->whereIn('tipo', ['egreso_gasto', 'egreso_retiro'])->where('metodo_pago', 'Efectivo')->sum('monto');
        
        $totalEfectivoEsperado = (float)$cajaModel->monto_apertura + (float)$ingresosEfectivo - (float)$egresosEfectivo;

        $totalCheque = $movimientos->whereIn('tipo', ['ingreso_tramite', 'ingreso_abono', 'ingreso_extra'])->where('metodo_pago', 'Cheque')->sum('monto');
        $totalTransferencia = $movimientos->whereIn('tipo', ['ingreso_tramite', 'ingreso_abono', 'ingreso_extra'])->where('metodo_pago', 'Transferencia')->sum('monto');
        $totalTarjeta = $movimientos->whereIn('tipo', ['ingreso_tramite', 'ingreso_abono', 'ingreso_extra'])->where('metodo_pago', 'Tarjeta')->sum('monto');
        $totalCredito = $movimientos->whereIn('tipo', ['ingreso_tramite', 'ingreso_abono'])->where('metodo_pago', 'Crédito')->sum('monto');

        $ingresosExtra = $movimientos->where('tipo', 'ingreso_extra')->sum('monto');
        $egresosExtra = $movimientos->whereIn('tipo', ['egreso_gasto', 'egreso_retiro'])->sum('monto');

        $totalGeneral = $totalEfectivoEsperado + $totalCheque + $totalTransferencia + $totalTarjeta;

        return [
            'monto_apertura' => (float)$cajaModel->monto_apertura,
            'ingresos_efectivo' => (float)$ingresosEfectivo,
            'egresos_efectivo' => (float)$egresosEfectivo,
            'efectivo' => (float)$totalEfectivoEsperado,
            'cheque' => (float)$totalCheque,
            'transferencia' => (float)$totalTransferencia,
            'tarjeta' => (float)$totalTarjeta,
            'credito' => (float)$totalCredito,
            'ingresos_extra' => (float)$ingresosExtra,
            'egresos_extra' => (float)$egresosExtra,
            'total_general' => (float)$totalGeneral,
        ];
    }

    /**
     * Checks if cash close declarations match the system totals.
     */
    public function validarCuadre(CajaSesion|int $caja, array $declarados): array
    {
        $cajaModel = $caja instanceof CajaSesion ? $caja : CajaSesion::findOrFail($caja);
        $sistema = $this->calcularTotalesSistema($cajaModel);

        $montoEfectivo = floatval($declarados['monto_efectivo'] ?? ($declarados['efectivo'] ?? 0));
        $montoCheque = floatval($declarados['monto_cheque'] ?? ($declarados['cheque'] ?? 0));
        $montoTransferencia = floatval($declarados['monto_transferencia'] ?? ($declarados['transferencia'] ?? 0));
        $montoTarjeta = floatval($declarados['monto_tarjeta'] ?? ($declarados['tarjeta'] ?? 0));
        $montoCredito = floatval($declarados['monto_credito'] ?? ($declarados['credito'] ?? $sistema['credito']));

        $difEfectivo = round($montoEfectivo - $sistema['efectivo'], 2);
        $difCheque = round($montoCheque - $sistema['cheque'], 2);
        $difTransferencia = round($montoTransferencia - $sistema['transferencia'], 2);
        $difTarjeta = round($montoTarjeta - $sistema['tarjeta'], 2);

        $totalDeclarado = $montoEfectivo + $montoCheque + $montoTransferencia + $montoTarjeta;
        $difTotal = round($totalDeclarado - $sistema['total_general'], 2);

        $cuadrado = (abs($difTotal) < 0.01 && abs($difEfectivo) < 0.01 && abs($difCheque) < 0.01 && abs($difTransferencia) < 0.01 && abs($difTarjeta) < 0.01);

        return [
            'sistema' => $sistema,
            'declarado' => [
                'efectivo' => $montoEfectivo,
                'cheque' => $montoCheque,
                'transferencia' => $montoTransferencia,
                'tarjeta' => $montoTarjeta,
                'credito' => $montoCredito,
                'total' => $totalDeclarado,
            ],
            'diferencias' => [
                'efectivo' => $difEfectivo,
                'cheque' => $difCheque,
                'transferencia' => $difTransferencia,
                'tarjeta' => $difTarjeta,
                'total' => $difTotal,
            ],
            'diferencia_total' => $difTotal,
            'cuadrado' => $cuadrado,
        ];
    }

    /**
     * Closes the cash session if squared (or forced by admin if ever allowed).
     */
    public function cerrarCaja(CajaSesion|int $caja, array $declarados, ?string $observaciones = null, bool $forzarCierre = false): CajaSesion
    {
        $cajaModel = $caja instanceof CajaSesion ? $caja : CajaSesion::findOrFail($caja);
        $validacion = $this->validarCuadre($cajaModel, $declarados);

        if (!$validacion['cuadrado'] && !$forzarCierre) {
            throw new Exception('La caja no puede cerrarse porque presenta un descuadre de $' . number_format($validacion['diferencias']['total'], 2) . '. Debe cuadrar exactamente todos los métodos de pago.');
        }

        return DB::transaction(function () use ($cajaModel, $validacion, $observaciones) {
            $sistema = $validacion['sistema'];
            $decl = $validacion['declarado'];
            $dif = $validacion['diferencias'];

            $cajaModel->update([
                'fecha_cierre' => now(),
                'estado' => 'cerrada',
                'observaciones_cierre' => $observaciones,
                'cuadrado' => $validacion['cuadrado'],
                // Totales de Sistema
                'total_sistema_efectivo' => $sistema['efectivo'],
                'total_sistema_cheque' => $sistema['cheque'],
                'total_sistema_transferencia' => $sistema['transferencia'],
                'total_sistema_tarjeta' => $sistema['tarjeta'],
                'total_sistema_credito' => $sistema['credito'],
                'total_sistema_total' => $sistema['total_general'],
                // Totales de Cierre Declarados
                'monto_cierre_efectivo' => $decl['efectivo'],
                'monto_cierre_cheque' => $decl['cheque'],
                'monto_cierre_transferencia' => $decl['transferencia'],
                'monto_cierre_tarjeta' => $decl['tarjeta'],
                'monto_cierre_credito' => $decl['credito'],
                'monto_cierre_total' => $decl['total'],
                // Diferencias
                'diferencia_efectivo' => $dif['efectivo'],
                'diferencia_cheque' => $dif['cheque'],
                'diferencia_transferencia' => $dif['transferencia'],
                'diferencia_tarjeta' => $dif['tarjeta'],
                'diferencia_total' => $dif['total'],
            ]);

            return $cajaModel->fresh();
        });
    }
}
