<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $datos['empleados'] = empleado::paginate(4);
        return view('empleado.index',$datos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('empleado.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validar que la cédula no esté repetida
    $request->validate([
        'cedula' => 'required|unique:empleados,cedula',
    ], [
        'cedula.required' => 'La cédula es obligatoria',
        'cedula.unique' => 'El colaborador ya está creado',
    ]);

    // Guardar los datos
    $datosEmpleado = $request->except('_token');

    if ($request->hasFile('foto')) {
        $datosEmpleado['foto'] = $request->file('foto')->store('uploads', 'public');
    }

    empleado::insert($datosEmpleado);

    return redirect('empleado')->with('mensaje', 'Empleado agregado con éxito');
}


    /**
     * Display the specified resource.
     */
    public function show(empleado $empleado)
    {
        //
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(empleado $empleado)
    {
        //
           $empleado = empleado::findOrFail($empleado->id_personal);
        return view('empleado.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, empleado $empleado)
    {
        //
           $datosEmpleado = request()->except(['_token','_method']);
           if($request->hasFile('foto')) {
            $empleado = empleado::findOrFail($empleado->id_personal);
            Storage::delete('public/'.$empleado->foto);
            $datosEmpleado['foto'] = $request->file('foto')->store('uploads', 'public');
        }



        empleado::where('id_personal', '=', $empleado->id_personal)->update($datosEmpleado);    
        $empleado = empleado::findOrFail($empleado->id_personal);
        return view('empleado.edit', compact('empleado'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_personal)
    {

        $empleado = empleado::findOrFail($id_personal);
        if(Storage::delete('public/'.$empleado->foto)) {

            empleado::destroy($id_personal);
            //$empleado->delete();
        }
        
        return redirect('empleado')->with('mensaje', 'Empleado eliminado');
    }
    
}
