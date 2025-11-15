<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Obtener el id_proyecto de la URL
        $id_proyecto = $request->query('id_proyecto');
    
        // Pasar el id_proyecto al formulario
        return view('reporte.create', compact('id_proyecto'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de datos, si lo deseas
        $validatedData = $request->validate([
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            // Otros campos a validar...
        ]);
    
        // Obtener todos los datos del formulario, excepto el token
        $datosReporte = $request->except('_token');
        
        // Guardar fotos si se suben
        if ($request->hasFile('foto1')) {
            $datosReporte['foto1'] = $request->file('foto1')->store('uploads', 'public');
        }
        
        if ($request->hasFile('foto2')) {
            $datosReporte['foto2'] = $request->file('foto2')->store('uploads', 'public');
        }
        
        if ($request->hasFile('foto3')) {
            $datosReporte['foto3'] = $request->file('foto3')->store('uploads', 'public');
        }
    
        // Asegurarse de que el id_proyecto esté incluido en los datos
        $id_proyecto = $request->input('id_proyecto');
        $datosReporte['id_proyecto'] = $id_proyecto;
    
        // Insertar en la base de datos
        Reporte::create($datosReporte); // Cambié `insert` a `create`, porque se recomienda para manejar datos Eloquent.
    
        // Redirigir al avance del proyecto con el parámetro query 'id_proyecto'
        return redirect('proyecto')
                         ->with('mensaje', 'Reporte agregado con éxito');
    }
    


    /**
     * Display the specified resource.
     */
    public function show(reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reporte $reporte)
    {
        //
    }
}
