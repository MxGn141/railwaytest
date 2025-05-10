<?php
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\PassportAuthController;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Http\Controllers\QrController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\DashboardController;

Route::middleware('auth:api')->get(
    '/user', function (Request $request) {
    return $request->user();
}
);

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::post('logout', [PassportAuthController::class, 'logout']);

Route::controller(EmpleadosController::class)->group(function() {
        Route::get('/empleados', 'index');
        Route::post('/empleados', 'store');
        Route::get('/empleados/{id}', 'show');
        Route::put('/empleados/{id}', 'update');
        Route::delete('/empleados/{id}', [EmpleadosController::class, 'destroy']);
});

Route::get('/generate-all-qr', [QrController::class, 'generateQRCodesForAll']);

Route::post('/asistencia', [AsistenciaController::class, 'registrar']);


Route::get('/asistencias', [AsistenciaController::class, 'listar']);

Route::get('/empleados/count', [DashboardController::class, 'totalEmpleados']);
