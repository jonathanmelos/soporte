<?php

namespace App\Http\Controllers;
use App\Models\RubroRequerimiento;
use App\Models\Requerimientos;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RequerimientosController extends Controller
{
    public function index()
    {
        $datos['requerimientos'] = Requerimientos::with('cliente')->where('estado', 'pendiente')->paginate(10);
        return view('requerimientos.index', $datos);
    }

    public function create()
    {
        $clientes = Cliente::all(); // Puede ser útil para autocompletado
        return view('requerimientos.create', [
            'clientes' => $clientes,
            'requerimiento' => new Requerimientos()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_nombre' => 'required|string|max:255',
            'contacto' => 'required|string|max:255',
            'trabajo' => 'required|string',
            // Validación básica para al menos un rubro (puedes mejorarla)
            'nombre_rubro.*' => 'required|string|max:255',
            'unidad.*' => 'nullable|string|max:50',
            'cantidad.*' => 'nullable|numeric',
            'nota.*' => 'nullable|string|max:255',
            'archivo.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,avi|max:20480' // opcional: imágenes o videos
        ]);
    
        // Buscar o crear cliente
        $cliente = Cliente::firstOrCreate(
            ['empresa' => $request->input('cliente_nombre')]
        );
    
        // Crear requerimiento
        $datosRequerimientos = $request->except('_token', 'cliente_nombre');
        $datosRequerimientos['id_cliente'] = $cliente->id_cliente;
    
        if ($request->hasFile('foto')) {
            $datosRequerimientos['foto'] = $request->file('foto')->store('uploads', 'public');
        }
    
        $requerimiento = Requerimientos::create($datosRequerimientos);
    
        // Guardar los rubros si existen
        if ($request->has('nombre_rubro')) {
            foreach ($request->input('nombre_rubro') as $index => $nombreRubro) {
                $rubro = new RubroRequerimiento();
                $rubro->id_requerimientos = $requerimiento->id_requerimientos; // clave foránea
                $rubro->nombre_rubro = $nombreRubro;
                $rubro->unidad = $request->input('unidad')[$index] ?? null;
                $rubro->cantidad = $request->input('cantidad')[$index] ?? null;
                $rubro->nota = $request->input('nota')[$index] ?? null;
    
                if ($request->hasFile("archivo.$index")) {
                    $rubro->archivo = $request->file("archivo")[$index]->store('rubros', 'public');
                }
    
                $rubro->save();
            }
        }
    
        return redirect('requerimientos')->with('mensaje', 'Nuevo requerimiento creado con éxito. Cliente: ' . $cliente->empresa);
    }

    public function show(Requerimientos $requerimientos)
    {
        //
    }

    public function edit(Requerimientos $requerimiento)
    {
        $clientes = Cliente::all(); // Para autocompletado
        return view('requerimientos.edit', compact('requerimiento', 'clientes'));
    }

    public function update(Request $request, Requerimientos $requerimiento)
    {
        $request->validate([
            'cliente_nombre' => 'required|string|max:255',
            'contacto' => 'required|string|max:255',
            'trabajo' => 'required|string',
            'nombre_rubro.*' => 'required|string|max:255',
            'unidad.*' => 'nullable|string|max:50',
            'cantidad.*' => 'nullable|numeric',
            'nota.*' => 'nullable|string|max:255',
            'archivo.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,avi|max:20480'
        ]);
    
        // Buscar o crear cliente
        $cliente = Cliente::firstOrCreate([
            'empresa' => $request->input('cliente_nombre')
        ]);
    
        // Actualizar requerimiento
        $datosRequerimiento = $request->except(['_token', '_method', 'cliente_nombre', 'id_rubro']);
        $datosRequerimiento['id_cliente'] = $cliente->id_cliente;
    
        $requerimiento->update($datosRequerimiento);
    
        // IDs de rubros recibidos
        $rubrosIdsEnFormulario = $request->input('id_rubro', []);
    
        // Obtener rubros actuales
        $rubrosActuales = $requerimiento->rubros;
    
        // Eliminar rubros que ya no están en el formulario
        foreach ($rubrosActuales as $rubroActual) {
            if (!in_array($rubroActual->id_rubro_requerimiento, $rubrosIdsEnFormulario)) {
                $rubroActual->delete();
            }
        }
    
        // Recorrer rubros del formulario y actualizarlos o crearlos
        foreach ($request->input('nombre_rubro') as $index => $nombreRubro) {
            $idRubro = $rubrosIdsEnFormulario[$index] ?? null;
    
            // Buscar o crear rubro
            $rubro = RubroRequerimiento::where('id_requerimientos', $requerimiento->id_requerimientos)
                                        ->where('nombre_rubro', $nombreRubro)
                                        ->first();
    
            if ($rubro) {
                // Si el rubro existe, lo actualizamos
                $rubro->unidad = $request->input('unidad')[$index] ?? null;
                $rubro->cantidad = $request->input('cantidad')[$index] ?? null;
                $rubro->nota = $request->input('nota')[$index] ?? null;
            } else {
                // Si el rubro no existe, creamos uno nuevo
                $rubro = new RubroRequerimiento();
                $rubro->id_requerimientos = $requerimiento->id_requerimientos;
                $rubro->nombre_rubro = $nombreRubro;
            }
    
            if ($request->hasFile("archivo.$index")) {
                $rubro->archivo = $request->file("archivo")[$index]->store('rubros', 'public');
            }
    
            $rubro->save();
        }
    
        return redirect()->route('requerimientos.index')->with('mensaje', 'Requerimiento actualizado con éxito');
    }
    

    public function destroy(Requerimientos $requerimientos)
    {
        //
    }

    public function descartar(Requerimientos $requerimiento)
    {
        return view('requerimientos.descartar', compact('requerimiento'));
    }

    public function actualizar($id_requerimientos)
    {
        $requerimientos = Requerimientos::findOrFail($id_requerimientos);
        $requerimientos->estado = 'aprobado';
        $requerimientos->save();

        return redirect()->route('proyecto.create', [
            'id_requerimientos' => $requerimientos->id_requerimientos,
            'contacto' => $requerimientos->contacto,
            'empresa' => $requerimientos->cliente->empresa ?? 'Sin cliente'
        ]);
    }
} 