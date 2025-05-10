<?php

namespace App\Http\Controllers;

use App\Models\Empleados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadosController extends Controller
{
 
    public function index()
    {
       $empleado = Empleados::all();
       return $empleado;
    }

   
    public function store(Request $request)
    {
        $empleados = new Empleados();
        $empleados->cedula = $request->cedula;
        $empleados->nombre = $request->nombre;
        $empleados->apellido = $request->apellido;
        $empleados->telefono = $request->telefono;
        $empleados->correo = $request->correo;
        $empleados->cargo = $request->cargo;
        $empleados->departamento = $request->departamento;
        $empleados->fecha_de_ingreso = $request->fecha_de_ingreso;
    
        $empleados->save();
    
        // Llamar al método para generar el QR
        app(QrController::class)->generateQRCodesForAll();
    
        return response()->json(['message' => 'Empleado creado y QR generado']);
    }
    

    
    public function show(string $id)
    {
        $empleados = Empleados::find($id);
        return $empleados; 
    }

  
    public function update(Request $request, string $id)
    {
       $empleados = Empleados::findOrFail($request->id);
       $empleados->cedula = $request->cedula;
       $empleados->nombre = $request->nombre;
       $empleados->apellido = $request->apellido;
       $empleados->telefono = $request->telefono;
       $empleados->correo = $request->correo;
       $empleados->cargo = $request->cargo;
       $empleados->departamento = $request->departamento;
       $empleados->fecha_de_ingreso = $request->fecha_de_ingreso;

       $empleados->save();
       return $empleados;
    }

   
    public function destroy(string $id)
{
    // Buscar el empleado antes de eliminarlo
    $empleado = Empleados::find($id);

    if (!$empleado) {
        return response()->json(['error' => 'Empleado no encontrado'], 404);
    }

    // Verificar si tiene un código QR y eliminarlo del almacenamiento
    if ($empleado->qr_code_path) {
        Storage::disk('public')->delete($empleado->qr_code_path);
    }

    // Eliminar el empleado de la base de datos
    $empleado->delete();

    return response()->json(['message' => 'Empleado y su código QR eliminados correctamente']);
}
}
