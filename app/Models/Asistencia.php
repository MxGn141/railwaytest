<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias'; // Define el nombre de la tabla

    // 🔥 Agregamos 'estado' en fillable para que Laravel lo guarde correctamente
    protected $fillable = ['empleado_id', 'fecha', 'hora_entrada', 'hora_salida', 'estado'];

    // ✅ Corrección en la relación con empleados (sin doble 'return')
    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'empleado_id');
    }
}