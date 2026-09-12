<?php

namespace App\Http\Controllers;

use App\Models\PlantillaMembrete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PlantillaMembreteController extends Controller
{
    /**
     * Display a listing of headers and footers (JSON and view).
     */
    public function index(Request $request)
    {
        $encabezados = PlantillaMembrete::encabezados()->orderBy('es_predeterminado', 'desc')->orderBy('nombre', 'asc')->get();
        $pies = PlantillaMembrete::pies()->orderBy('es_predeterminado', 'desc')->orderBy('nombre', 'asc')->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'encabezados' => $encabezados,
                'pies' => $pies,
            ]);
        }

        return view('plantillas.membretes.index', compact('encabezados', 'pies'));
    }

    /**
     * Store a newly created header or footer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'tipo' => 'required|in:encabezado,pie',
            'contenido_html' => 'required|string',
            'datos_json' => 'nullable|array',
        ]);

        $membrete = PlantillaMembrete::create([
            'nombre' => $validated['nombre'],
            'tipo' => $validated['tipo'],
            'contenido_html' => $validated['contenido_html'],
            'datos_json' => $validated['datos_json'] ?? null,
            'es_predeterminado' => false,
            'activo' => true,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '¡' . ucfirst($membrete->tipo) . ' guardado exitosamente!',
                'membrete' => $membrete,
            ]);
        }

        return redirect()->back()->with('success', '¡Membrete guardado con éxito!');
    }

    /**
     * Show the specified resource in JSON.
     */
    public function show(PlantillaMembrete $membrete)
    {
        return response()->json([
            'success' => true,
            'membrete' => $membrete,
        ]);
    }

    /**
     * Update the specified header or footer.
     */
    public function update(Request $request, PlantillaMembrete $membrete)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'contenido_html' => 'required|string',
            'datos_json' => 'nullable|array',
        ]);

        $membrete->update([
            'nombre' => $validated['nombre'],
            'contenido_html' => $validated['contenido_html'],
            'datos_json' => $validated['datos_json'] ?? $membrete->datos_json,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '¡' . ucfirst($membrete->tipo) . ' actualizado exitosamente!',
                'membrete' => $membrete,
            ]);
        }

        return redirect()->back()->with('success', '¡Membrete actualizado con éxito!');
    }

    /**
     * Remove the specified header or footer.
     */
    public function destroy(PlantillaMembrete $membrete)
    {
        if ($membrete->es_predeterminado) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar un membrete oficial del sistema.',
            ], 403);
        }

        $membrete->delete();

        return response()->json([
            'success' => true,
            'message' => 'Membrete eliminado correctamente.',
        ]);
    }

    /**
     * Upload an image for a header/footer logo or divider.
     */
    public function uploadImage(Request $request)
    {
        $destinationPath = public_path('uploads/membretes');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $fileName = 'mbr_' . uniqid() . '_' . time() . '.' . $extension;
            $file->move($destinationPath, $fileName);
            $url = asset('uploads/membretes/' . $fileName);
            return response()->json(['success' => true, 'url' => $url]);
        }

        return response()->json(['error' => 'No se recibió ningún archivo de imagen válido.'], 422);
    }
}
