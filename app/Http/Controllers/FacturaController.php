<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\DetalleFactura;
use Carbon\Carbon;

class FacturaController extends Controller
{
    // Muestra el formulario y el listado de facturas del mes
   public function formUpload(Request $request)
{
    $mes = $request->query('mes', Carbon::now()->month);
    $año = $request->query('año', Carbon::now()->year);

    $facturasDelMes = Factura::whereMonth('fecha_autorizacion', $mes)
        ->whereYear('fecha_autorizacion', $año)
        ->orderBy('fecha_autorizacion', 'desc')
        ->get();

    // Calcular fechas anterior y siguiente
    $fechaActual = Carbon::create($año, $mes, 1);
    $mesAnterior = $fechaActual->copy()->subMonth();
    $mesSiguiente = $fechaActual->copy()->addMonth();

    return view('facturas.upload', [
        'facturasDelMes' => $facturasDelMes,
        'mes' => $mes,
        'año' => $año,
        'nombreMes' => $fechaActual->translatedFormat('F'),
        'mesAnterior' => ['mes' => $mesAnterior->month, 'año' => $mesAnterior->year],
        'mesSiguiente' => ['mes' => $mesSiguiente->month, 'año' => $mesSiguiente->year],
    ]);
}


    // Procesa el archivo XML
    public function upload(Request $request)
    {
        $request->validate([
            'xml_file' => 'required|file|mimes:xml',
        ]);

        $xml = simplexml_load_file($request->file('xml_file')->getRealPath());
        $facturaXML = simplexml_load_string($xml->comprobante);
        $claveAcceso = (string) $facturaXML->infoTributaria->claveAcceso;

        $facturaExistente = Factura::where('clave_acceso', $claveAcceso)->first();
        if ($facturaExistente) {
            return redirect()
                ->route('facturas.show', $facturaExistente->id)
                ->with('info', 'Este documento ya fue cargado. Aquí puedes revisarlo.');
        }

        // Guardar factura
        $factura = Factura::create([
            'numero_autorizacion' => (string) $xml->numeroAutorizacion,
            'fecha_autorizacion' => Carbon::parse((string) $xml->fechaAutorizacion)->format('Y-m-d H:i:s'),
            'ambiente' => (string) $xml->ambiente,
            'clave_acceso' => $claveAcceso,
            'ruc_emisor' => (string) $facturaXML->infoTributaria->ruc,
            'razon_social_emisor' => (string) $facturaXML->infoTributaria->razonSocial,
            'nombre_comercial_emisor' => (string) $facturaXML->infoTributaria->nombreComercial ?? null,
            'ruc_comprador' => (string) $facturaXML->infoFactura->identificacionComprador,
            'razon_social_comprador' => (string) $facturaXML->infoFactura->razonSocialComprador,
            'fecha_emision' => Carbon::createFromFormat('d/m/Y', (string) $facturaXML->infoFactura->fechaEmision),
            'total_sin_impuestos' => (float) $facturaXML->infoFactura->totalSinImpuestos,
            'total_descuento' => (float) $facturaXML->infoFactura->totalDescuento,
            'importe_total' => (float) $facturaXML->infoFactura->importeTotal,
            'moneda' => (string) $facturaXML->infoFactura->moneda,
        ]);

        // Guardar detalles
        foreach ($facturaXML->detalles->detalle as $detalle) {
            $factura->detalles()->create([
                'codigo' => (string) $detalle->codigoPrincipal,
                'descripcion' => (string) $detalle->descripcion,
                'cantidad' => (float) $detalle->cantidad,
                'precio_unitario' => (float) $detalle->precioUnitario,
                'descuento' => (float) $detalle->descuento,
                'precio_total_sin_impuesto' => (float) $detalle->precioTotalSinImpuesto,
                'impuesto_valor' => (float) $detalle->impuestos->impuesto->valor,
            ]);
        }

        return redirect()->route('facturas.show', $factura->id)->with('success', 'Factura cargada correctamente.');
    }

    // Muestra la factura
    public function show(Factura $factura)
    {
        $factura->load('detalles');
        return view('facturas.show', compact('factura'));
    }
}
