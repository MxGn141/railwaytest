<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empleados; // Modelo correcto
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class GenerateEmployeeQRCodes extends Command
{
    protected $signature = 'generate:employee-qrcodes';
    protected $description = 'Genera códigos QR para todos los empleados';

    public function handle()
    {
        $empleados = Empleados::all(); // Usar el modelo correcto

        foreach ($empleados as $empleado) {
            // Construir URL para el empleado
            $url = url("/empleados/{$empleado->id}");

            // Generar el QR
            $qrCode = new QrCode($url);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Guardar el QR en el sistema de archivos
            $filename = "qrcodes/empleado_{$empleado->id}.png";
            Storage::disk('public')->put($filename, $result->getString());

            // Actualizar el registro del empleado (opcional)
            $empleado->update(['qr_code_path' => $filename]);

            $this->info("QR generado para: {$empleado->nombre}");
        }

        $this->info('Todos los códigos QR han sido generados.');
    }
}