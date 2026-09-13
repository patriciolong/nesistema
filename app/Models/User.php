<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'username', 'role', 'status', 'office', 'permissions'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Catálogo maestro de permisos por módulo del sistema.
     */
    public const AVAILABLE_PERMISSIONS = [
        'dashboard' => [
            'nombre' => 'Dashboard & Análisis',
            'icono' => '📊',
            'color' => 'indigo',
            'permisos' => [
                'dashboard.view' => [
                    'nombre' => 'Acceso al Dashboard',
                    'descripcion' => 'Permite ver el panel principal, métricas y accesos rápidos.',
                ],
                'dashboard.export' => [
                    'nombre' => 'Descargar Reportes en Excel',
                    'descripcion' => 'Permite exportar el libro multi-hoja de métricas a formato .xlsx.',
                ],
                'dashboard.ia' => [
                    'nombre' => 'Diagnóstico con IA',
                    'descripcion' => 'Permite visualizar las sugerencias, cuellos de botella y análisis inteligente.',
                ],
            ],
        ],
        'clientes' => [
            'nombre' => 'Clientes & Cartera',
            'icono' => '👥',
            'color' => 'emerald',
            'permisos' => [
                'clientes.view' => [
                    'nombre' => 'Ver Clientes',
                    'descripcion' => 'Permite consultar el listado y expediente de clientes.',
                ],
                'clientes.create' => [
                    'nombre' => 'Registrar Clientes',
                    'descripcion' => 'Permite crear nuevos clientes en el sistema.',
                ],
                'clientes.edit' => [
                    'nombre' => 'Editar Clientes',
                    'descripcion' => 'Permite modificar la información de clientes existentes.',
                ],
                'clientes.delete' => [
                    'nombre' => 'Eliminar Clientes',
                    'descripcion' => 'Permite eliminar clientes de la base de datos.',
                ],
                'clientes.abonar' => [
                    'nombre' => 'Registrar Abonos y Cobros',
                    'descripcion' => 'Permite recibir pagos, registrar abonos y emitir recibos oficiales.',
                ],
                'clientes.export' => [
                    'nombre' => 'Exportar Clientes a Excel',
                    'descripcion' => 'Permite descargar el padrón de clientes en formato Excel.',
                ],
                'cartera.view' => [
                    'nombre' => 'Consultar Cartera y Créditos',
                    'descripcion' => 'Permite consultar las cuentas por cobrar, estados de deuda de clientes y gestionar pagos de créditos.',
                ],
            ],
        ],
        'cajas' => [
            'nombre' => 'Operación de Caja',
            'icono' => '💵',
            'color' => 'teal',
            'permisos' => [
                'cajas.operar' => [
                    'nombre' => 'Operar Caja de Turno',
                    'descripcion' => 'Permite abrir caja, ingresar gastos menores, arqueos y realizar cierres.',
                ],
                'cajas.historial' => [
                    'nombre' => 'Historial de Cajas Propias',
                    'descripcion' => 'Permite consultar y reimprimir actas de turnos cerrados del propio usuario.',
                ],
            ],
        ],
        'tramites' => [
            'nombre' => 'Notaría, Trámites & Plantillas',
            'icono' => '📝',
            'color' => 'amber',
            'permisos' => [
                'tramites.create' => [
                    'nombre' => 'Crear y Redactar Trámites',
                    'descripcion' => 'Permite iniciar trámites varios, divorcios, impuestos, poderes y personalizados.',
                ],
                'plantillas.view' => [
                    'nombre' => 'Ver Catálogo de Plantillas',
                    'descripcion' => 'Permite explorar los modelos y plantillas de documentos notariales.',
                ],
                'plantillas.generar' => [
                    'nombre' => 'Generar y Emitir Documentos',
                    'descripcion' => 'Permite rellenar, emitir y descargar documentos notariales oficiales.',
                ],
                'plantillas.manage' => [
                    'nombre' => 'Administrar Plantillas (Editor)',
                    'descripcion' => 'Permite crear, editar, duplicar o eliminar modelos en el editor de plantillas.',
                ],
            ],
        ],
        'reportes' => [
            'nombre' => 'Reportes & Auditoría Financiera',
            'icono' => '📈',
            'color' => 'sky',
            'permisos' => [
                'reportes.cajas' => [
                    'nombre' => 'Auditoría Global de Cajas',
                    'descripcion' => 'Permite consultar los arqueos, cierres y actas de todas las cajas del sistema.',
                ],
                'reportes.desempeno' => [
                    'nombre' => 'Desempeño y Efectividad de Cajeros',
                    'descripcion' => 'Permite ver estadísticas de cobros, diferencias y exactitud por usuario.',
                ],
            ],
        ],
        'administracion' => [
            'nombre' => 'Administración & Configuración',
            'icono' => '⚙️',
            'color' => 'purple',
            'permisos' => [
                'bancos.manage' => [
                    'nombre' => 'Gestión de Bancos',
                    'descripcion' => 'Permite configurar entidades financieras y cuentas bancarias.',
                ],
                'tarjetas.manage' => [
                    'nombre' => 'Gestión de Tarjetas y POS',
                    'descripcion' => 'Permite registrar datáfonos, terminales y redes de pago.',
                ],
                'oficinas.manage' => [
                    'nombre' => 'Gestión de Oficinas',
                    'descripcion' => 'Permite administrar sucursales y puntos de atención.',
                ],
                'tipo_tramites.manage' => [
                    'nombre' => 'Constructor de Tipos de Trámites',
                    'descripcion' => 'Permite crear y personalizar formularios dinámicos para el sistema.',
                ],
                'users.manage' => [
                    'nombre' => 'Gestión de Usuarios',
                    'descripcion' => 'Permite crear cuentas, editar usuarios y reestablecer accesos.',
                ],
                'permisos.manage' => [
                    'nombre' => 'Gestión de Permisos',
                    'descripcion' => 'Permite asignar o revocar módulos y accesos específicos a otros usuarios.',
                ],
            ],
        ],
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    /**
     * Accessor for id_usuario attribute compatibility.
     */
    public function getIdUsuarioAttribute()
    {
        return $this->attributes['id'] ?? $this->id;
    }

    public function cajaSesiones()
    {
        return $this->hasMany(CajaSesion::class, 'user_id');
    }

    public function cajaMovimientos()
    {
        return $this->hasMany(CajaMovimiento::class, 'user_id');
    }

    /**
     * Obtains the currently active open cash session for this user, if any.
     */
    public function cajaAbierta()
    {
        return $this->cajaSesiones()->where('estado', 'abierta')->latest()->first();
    }

    public function logins()
    {
        return $this->hasMany(LoginData::class, 'id_usuario');
    }

    /**
     * Returns true if user has an open cash session.
     */
    public function hasCajaAbierta(): bool
    {
        return $this->cajaSesiones()->where('estado', 'abierta')->exists();
    }

    /**
     * Obtener los permisos por defecto según el rol del usuario.
     */
    public static function getRoleDefaultPermissions(string $role): array
    {
        if ($role === 'Administrador') {
            $all = [];
            foreach (self::AVAILABLE_PERMISSIONS as $group) {
                foreach (array_keys($group['permisos']) as $key) {
                    $all[] = $key;
                }
            }
            return $all;
        }

        if ($role === 'Supervisor') {
            return [
                'dashboard.view', 'dashboard.export', 'dashboard.ia',
                'clientes.view', 'clientes.create', 'clientes.edit', 'clientes.abonar', 'clientes.export', 'cartera.view',
                'cajas.operar', 'cajas.historial',
                'tramites.create', 'plantillas.view', 'plantillas.generar', 'plantillas.manage',
                'reportes.cajas', 'reportes.desempeno',
                'bancos.manage', 'tarjetas.manage', 'tipo_tramites.manage',
            ];
        }

        // Empleado / Cajero por defecto
        return [
            'dashboard.view',
            'clientes.view', 'clientes.create', 'clientes.edit', 'clientes.abonar', 'cartera.view',
            'cajas.operar', 'cajas.historial',
            'tramites.create', 'plantillas.view', 'plantillas.generar',
        ];
    }

    /**
     * Retorna la lista efectiva de claves de permisos que tiene el usuario.
     */
    public function getEffectivePermissions(): array
    {
        if ($this->role === 'Administrador') {
            return self::getRoleDefaultPermissions('Administrador');
        }

        if (is_array($this->permissions)) {
            return $this->permissions;
        }

        return self::getRoleDefaultPermissions($this->role ?? 'Empleado');
    }

    /**
     * Verifica si el usuario tiene permiso para una clave específica.
     */
    public function hasPermission(string|array $permission): bool
    {
        if ($this->role === 'Administrador') {
            return true;
        }

        $effective = $this->getEffectivePermissions();

        if (is_array($permission)) {
            foreach ($permission as $p) {
                if (in_array($p, $effective, true)) {
                    return true;
                }
            }
            return false;
        }

        return in_array($permission, $effective, true);
    }
}
