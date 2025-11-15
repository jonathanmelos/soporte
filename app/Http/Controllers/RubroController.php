<?php

namespace App\Http\Controllers;

use App\Models\Rubro;
use Illuminate\Http\Request;

class RubroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Aquí puedes listar rubros si quieres
        $rubros = Rubro::orderBy('nombre')->paginate(10);
        return view('rubros.index', compact('rubros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retorna la vista para crear rubro
        return view('rubros.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // Validar datos recibidos
    $request->validate([
        'categoria' => 'required|string|max:255',
        'nombre' => 'required|string|max:255',
        'unidad_medida' => 'required|string|max:100',
        'creado' => 'required|string|max:255',
    ]);

    // Crear nuevo rubro y guardar en una variable
    $rubro = Rubro::create([
        'categoria' => $request->categoria,
        'nombre' => $request->nombre,
        'unidad_medida' => $request->unidad_medida,
        'creado' => $request->creado,
    ]);

    // Redirigir a la página de ítems del rubro recién creado
    return redirect()->route('rubros.items', $rubro->id)
                     ->with('mensaje', 'Rubro creado correctamente. Ahora puedes agregar ítems.');
    }

    public function items($id)
    {
    $rubro = Rubro::findOrFail($id);
    return view('rubros.items', compact('rubro'));
    }
    // Los demás métodos puedes dejarlos vacíos por ahora o implementarlos cuando quieras

    public function show(Rubro $rubro)
    {
        //
    }

    public function edit(Rubro $rubro)
    {
        //
    }

    public function update(Request $request, Rubro $rubro)
    {
        //
    }

    public function destroy(Rubro $rubro)
    {
        //
    }
}

