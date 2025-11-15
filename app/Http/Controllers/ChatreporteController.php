<?php

namespace App\Http\Controllers;

use App\Models\Chatreporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatreporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Aquí podrías recuperar y mostrar todos los chatreportes.
        // Ejemplo:
        // $chatreportes = Chatreporte::all();
        // return view('chatreporte.index', compact('chatreportes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Obtener parámetros de la URL
        $id_proyecto = $request->query('id_proyecto');
    
        // Obtener los reportes anteriores del proyecto, ordenados por fecha descendente
        $reportes = Chatreporte::where('id_proyecto', $id_proyecto)
                                ->orderBy('created_at', 'desc') // o 'fecha' si tienes ese campo
                                ->get();
    
        // Pasar los datos a la vista
        return view('chatreporte.create', compact('id_proyecto', 'reportes'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validatedData = $request->validate([
            'fecha' => 'required|date',
            'texto' => 'required|string',
            'usuario' => 'required|string|max:255',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'id_proyecto' => 'required|integer',
            'empresa' => 'required|string',
            'trabajo' => 'required|string',
        ]);
    
        // Obtener todos los datos del formulario, excepto el token
        $datosChatreporte = $request->except('_token');
    
        // Guardar foto si se sube
        if ($request->hasFile('foto1')) {
            $datosChatreporte['foto1'] = $request->file('foto1')->store('uploads', 'public');
        }
    
        // Insertar en la base de datos
        Chatreporte::create($datosChatreporte);
    
        // Redirigir nuevamente a la misma página con los parámetros
        return redirect()->route('chatreporte.create', [
            'id_proyecto' => $request->input('id_proyecto'),
            'empresa' => $request->input('empresa'),
            'trabajo' => $request->input('trabajo'),
        ])->with('mensaje', 'Chatreporte agregado con éxito');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Chatreporte $chatreporte)
    {
        // Aquí podrías mostrar los detalles de un solo chatreporte.
        // Ejemplo:
        // return view('chatreporte.show', compact('chatreporte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chatreporte $chatreporte)
    {
        // Aquí podrías mostrar el formulario de edición con los datos del chatreporte.
        // Ejemplo:
        // return view('chatreporte.edit', compact('chatreporte'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chatreporte $chatreporte)
    {
        // Validación de datos
        $validatedData = $request->validate([
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            // Otros campos a validar...
        ]);
        
        // Obtener todos los datos del formulario, excepto el token
        $datosChatreporte = $request->except('_token');
        
        // Guardar foto si se sube
        if ($request->hasFile('foto1')) {
            // Eliminar la foto anterior si existe
            if ($chatreporte->foto1) {
                Storage::delete('public/' . $chatreporte->foto1);
            }
            // Guardar la nueva foto
            $datosChatreporte['foto1'] = $request->file('foto1')->store('uploads', 'public');
        }

        // Actualizar el chatreporte con los nuevos datos
        $chatreporte->update($datosChatreporte);

        // Redirigir al avance del proyecto con el parámetro query 'id_proyecto'
        return redirect('proyecto')
                         ->with('mensaje', 'Chatreporte actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chatreporte $chatreporte)
    {
        // Eliminar la foto asociada si existe
        if ($chatreporte->foto1) {
            Storage::delete('public/' . $chatreporte->foto1);
        }

        // Eliminar el chatreporte de la base de datos
        $chatreporte->delete();

        // Redirigir al avance del proyecto con el parámetro query 'id_proyecto'
        return redirect('proyecto')
                         ->with('mensaje', 'Chatreporte eliminado con éxito');
    }
}
