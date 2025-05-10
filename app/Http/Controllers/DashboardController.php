<?php
namespace App\Http\Controllers;

use App\Models\Empleados;
use App\Models\Asistencia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function totalEmpleados()
    {
        $total = Empleados::count();
        return response()->json(['total' => $total]);
    }

    public function estadisticasAsistencias()
    {
        $hoy = Carbon::today();
        $inicioSemana = Carbon::now()->startOfWeek();
        $inicioMes = Carbon::now()->startOfMonth();

        $dia = Asistencia::whereDate('fecha', $hoy)->count();
        $semana = Asistencia::whereBetween('fecha', [$inicioSemana, Carbon::now()])->count();
        $mes = Asistencia::whereBetween('fecha', [$inicioMes, Carbon::now()])->count();

        return response()->json(['dia' => $dia, 'semana' => $semana, 'mes' => $mes]);
    }
}