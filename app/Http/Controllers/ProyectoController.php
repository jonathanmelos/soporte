<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Requerimientos;


class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener proyectos con estado pendiente, contar los reportes asociados, y cargar la relación 'requerimiento'
        $proyectos = Proyecto::where('estado', 'pendiente')
                             ->withCount('chatreportes') // Incluye el conteo de reportes
                             ->with('requerimientos') // Carga la relación 'requerimiento'
                             ->paginate(10); // Solo proyectos con estado pendiente
        
        // Pasar los proyectos a la vista
        return view('proyecto.index', compact('proyectos'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $id_requerimiento = $request->query('id_requerimientos');
        $contacto = $request->query('contacto');
        $empresa = $request->query('empresa');
    
        return view('proyecto.create', compact('id_requerimiento', 'contacto', 'empresa'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Guardar los datos
        $datosProyecto = $request->except('_token');  // Excluir token de CSRF
    
        // Si el archivo de documento ha sido cargado
        if ($request->hasFile('documento')) {
            // Guardar el archivo en el almacenamiento público
            $datosProyecto['documento'] = $request->file('documento')->store('documentos', 'public');
        }
    
        // Establecer el responsable automáticamente (por el usuario logeado)
        $datosProyecto['responsable'] = auth()->user()->name;
    
        // Insertar los datos en la tabla 'proyecto'
        Proyecto::insert($datosProyecto);
    
        // Redirigir con mensaje de éxito
        return redirect('proyecto')->with('mensaje', 'Nuevo proyecto creado con éxito');
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show($id_proyecto)
    {
        // Obtiene el proyecto junto con los chatreportes
        $proyecto = Proyecto::with('chatreportes')->findOrFail($id_proyecto);
    
        // Obtiene los reportes asociados a ese proyecto
        $reportes = $proyecto->chatreportes;
    
        // Pasa el proyecto y los reportes a la vista
        return view('proyecto.show', compact('proyecto', 'reportes'));
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(proyecto $proyecto)
{
    // Ya tenemos el proyecto con su campo 'avance_obra'
    return view('proyecto.edit', compact('proyecto'));
}
       /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, proyecto $proyecto)
    {
        //

        // Obtener todos los datos del formulario excepto el token y el método
        $datosProyecto = $request->except(['_token', '_method']);
    
        // Actualizar el registro de 'requerimientos'
        $proyecto->update($datosProyecto);
    
        // Redirigir a la lista de requerimientos con un mensaje de éxito
        return redirect()->route('proyecto.index')->with('mensaje', 'Proyecto actualizado con éxito');
    }

    public function avance(Request $request)
    {
        // Obtener el id_proyecto de la consulta
        $id_proyecto = $request->query('id_proyecto');
    
        // Buscar el proyecto en la base de datos con el id_proyecto
        $proyecto = Proyecto::find($id_proyecto);
    
        if (!$proyecto) {
            // Si no se encuentra el proyecto, puedes redirigir o mostrar un error
            return redirect()->route('home')->with('error', 'Proyecto no encontrado');
        }
    
        // Pasar el proyecto a la vista
        return view('proyecto.avance', compact('proyecto'));
    }
    
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(proyecto $proyecto)
    {
        //
    }

    // Método GET para mostrar el formulario de cierre
public function cerrar(Proyecto $proyecto)
{
    // Solo se necesita este método para mostrar el formulario
    return view('proyecto.cerrar', compact('proyecto'));
}

// Método PUT para actualizar los datos del proyecto con las valoraciones
public function actualizarCierre(Request $request, Proyecto $proyecto)
{
    // Validación de los campos de valoración
    $request->validate([
        'val_material' => 'required|integer|between:1,5',
        'val_equipo' => 'required|integer|between:1,5',
        'val_cliente' => 'required|integer|between:1,5',
        'val_planificacion' => 'required|integer|between:1,5',
    ]);

    // Actualizar las valoraciones en el proyecto
    $proyecto->val_material = $request->input('val_material');
    $proyecto->val_equipo = $request->input('val_equipo');
    $proyecto->val_cliente = $request->input('val_cliente');
    $proyecto->val_planificacion = $request->input('val_planificacion');

    // Actualizar fecha y estado
    $proyecto->fecha_finalizacion = now();
    $proyecto->estado = 'finalizado';

    // Guardar los cambios en la base de datos
    $proyecto->save();

    // Redirigir a la lista de proyectos con un mensaje de éxito
    return redirect('/proyecto')->with('success', 'Proyecto cerrado y valorado con éxito.');
}


public function reportes()
{
    return $this->hasMany(Reporte::class, 'id_proyecto', 'id_proyecto');
}

    
}
