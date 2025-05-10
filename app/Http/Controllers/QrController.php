<?php
namespace App\Http\Controllers;

use App\Models\Empleados; // Importa el modelo de empleados
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class QrController extends Controller
{
    public function generateQRCodesForAll()
    {
        // Obtiene todos los empleados de la base de datos
        $empleados = Empleados::all();

        foreach ($empleados as $empleado) {
            // Construir la URL que el QR va a redirigir (frontend React)
            $url = "http://192.168.100.9:5173/empleados/{$empleado->id}";

            // Generar el código QR
            $qrCode = new QrCode($url);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Guardar el QR como imagen en el almacenamiento público
            $filename = "qrcodes/empleado_{$empleado->id}.png";
            Storage::disk('public')->put($filename, $result->getString());

            // Actualizar la ruta del QR en la base de datos
            $empleado->update(['qr_code_path' => $filename]);
        }

        // Retornar una respuesta indicando éxito
        return response()->json(['message' => 'Códigos QR generados para todos los empleados']);
    }
}