<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    use HasFactory;

    protected $fillable = [
        'cedula', 'nombre', 'apellido', 'telefono', 'correo',
        'cargo', 'departamento', 'fecha_de_ingreso', 'qr_code_path'
    ];

    // Opcional: Define el nombre de la tabla si no sigue las convenciones (pluralización)
    protected $table = 'empleados';
}