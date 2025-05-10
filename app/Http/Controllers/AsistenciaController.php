<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Empleados;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AsistenciaController extends Controller
{
    public function registrar(Request $request)
    {
        try {
            $empleado = Empleados::find($request->empleado_id);
    
            if (!$empleado) {
                return response()->json(['error' => 'Empleado no encontrado'], 404);
            }
    
            $fechaHoy = now()->toDateString();
            $horaEntrada = now();
            $horaLimite = Carbon::createFromTime(8, 0, 0); // 🔥 Hora límite para ser puntual
    
            // 🔎 Verificar si el empleado ya registró asistencia hoy
            $asistenciaHoy = Asistencia::where('empleado_id', $empleado->id)
                                       ->where('fecha', $fechaHoy)
                                       ->first();
    
            if ($asistenciaHoy && $asistenciaHoy->hora_salida) {
                return response()->json(['error' => 'El empleado ya registró su entrada y salida hoy'], 400);
            }
    
            if ($asistenciaHoy && !$asistenciaHoy->hora_salida) {
                $asistenciaHoy->update(['hora_salida' => now()]);
                return response()->json(['mensaje' => 'Salida registrada', 'hora_salida' => $asistenciaHoy->hora_salida]);
            }
    
            // ✅ Si no tiene asistencia registrada, guardar estado y hora de entrada
            $estado = $horaEntrada->greaterThan($horaLimite) ? 'Tarde' : 'A tiempo';
    
            $asistencia = Asistencia::create([
                'empleado_id' => $empleado->id,
                'fecha' => $fechaHoy,
                'hora_entrada' => $horaEntrada,
                'estado' => $estado, // 🔥 Guardamos el estado en la base de datos
            ]);
    
            return response()->json([
                'mensaje' => 'Entrada registrada',
                'estado' => $asistencia->estado, // 🔥 Confirmamos que se guardó correctamente
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

public function listar()
{
    try {
        $asistencias = Asistencia::with('empleado:id,nombre,apellido,departamento')->get(); // 🔥 Traemos info del empleado

        return response()->json($asistencias);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}