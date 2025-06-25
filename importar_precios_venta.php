<?php
require 'vendor/autoload.php';
include 'db.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// var_dump($_FILES);
// exit;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_excel'])) {
    $archivo = $_FILES['archivo_excel']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($archivo);
        $sheet = $spreadsheet->getActiveSheet();

        $actualizados = 0;
        $fila = 3; // Comienza en la fila 3 (después de encabezados)
        while (true) {
            $fecha = $sheet->getCell("A$fila")->getValue();
            $estacion = $sheet->getCell("D$fila")->getValue();

            // Si no hay fecha ni estación, asumimos fin del archivo
            if (!$fecha && !$estacion) break;

            // Si la fila solo tiene texto (como "Estatal Campeche"), la saltamos
            if (!$estacion || $estacion === '') {
                $fila++;
                continue;
            }

            $precio_magna = $sheet->getCell("P$fila")->getCalculatedValue();
            $precio_premium = $sheet->getCell("Q$fila")->getCalculatedValue();
            $precio_diesel = $sheet->getCell("R$fila")->getCalculatedValue();

            $stmt = $conn->prepare("
                UPDATE precios_combustible
                SET precio_magna = ?, precio_premium = ?, precio_diesel = ?
                WHERE DATE(fecha) = ? AND estacion = ?
            ");
            $stmt->bind_param("ddsss", $precio_magna, $precio_premium, $precio_diesel, $fecha, $estacion);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $actualizados++;
            }

            $fila++;
        }

        unlink($archivo); // Elimina el archivo temporal

        echo json_encode([
            'success' => true,
            'actualizados' => $actualizados
        ]);
        exit;

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Error al procesar el archivo: ' . $e->getMessage()
        ]);
        exit;
    }
}

echo json_encode([
    'success' => false,
    'error' => 'No se recibió ningún archivo válido.'
]);

?>
