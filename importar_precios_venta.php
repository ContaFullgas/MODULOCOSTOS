<?php
require 'vendor/autoload.php';
include 'db.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

// var_dump($_FILES);
// exit;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_excel'])) {
    $archivo = $_FILES['archivo_excel']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($archivo);
        $sheet = $spreadsheet->getActiveSheet();

        $fechaSeleccionada = $_POST['fecha'] ?? null;

        // Validar que la fecha del Excel (celda A4) coincida con la seleccionada
        $celdaFecha = $sheet->getCell("A4");
        $valorFecha = $celdaFecha->getValue();

        // Si la celda está vacía o es 0, es inválida
        if (empty($valorFecha) || $valorFecha === 0) {
            echo json_encode([
                'success' => false,
                'error' => "La celda A4 está vacía o no contiene una fecha válida. Valor leído: '$valorFecha'"
            ]);
            exit;
        }

        try {
            if (Date::isDateTime($celdaFecha)) {
                $fechaExcelFormato = Date::excelToDateTimeObject($valorFecha)->format('Y-m-d');
            } else {
                $fechaExcelFormato = date('Y-m-d', strtotime($valorFecha));
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => "No se pudo interpretar la fecha de la celda A4. Valor: '$valorFecha'. Error: " . $e->getMessage()
            ]);
            exit;
        }


        if ($fechaSeleccionada && $fechaExcelFormato) {
            if ($fechaSeleccionada !== $fechaExcelFormato) {
                echo json_encode([
                    'success' => false,
                    'error' => "La fecha del Excel ($fechaExcelFormato) no coincide con la fecha seleccionada ($fechaSeleccionada)."
                ]);
                exit;
            }
        }


        $actualizados = 0;
        $fila = 4; // Comienza en la fila 4 (después de encabezados)
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

                // Buscar la fecha anterior más cercana con datos para esta estación
                $stmtPrev = $conn->prepare("
                    SELECT precio_magna, precio_premium, precio_diesel 
                    FROM precios_combustible 
                    WHERE estacion = ? AND fecha < ? 
                    ORDER BY fecha DESC 
                    LIMIT 1
                ");
                $stmtPrev->bind_param("ss", $estacion, $fecha);
                $stmtPrev->execute();
                $resPrev = $stmtPrev->get_result();

                $cambioReal = false;

                if ($resPrev && $resPrev->num_rows > 0) {
                    $anterior = $resPrev->fetch_assoc();

                    // Comparar valores, permitir diferencias menores (por ejemplo, 0.001) por redondeos
                    if (
                        abs(floatval($anterior['precio_magna']) - floatval($precio_magna)) > 0.001 ||
                        abs(floatval($anterior['precio_premium']) - floatval($precio_premium)) > 0.001 ||
                        abs(floatval($anterior['precio_diesel']) - floatval($precio_diesel)) > 0.001
                    ) {
                        $cambioReal = true;
                    }
                } else {
                    // Si no hay datos anteriores, consideramos que sí hay un cambio
                    // $cambioReal = true;
                }

                if ($cambioReal) {
                    $stmtMod = $conn->prepare("
                        UPDATE precios_combustible
                        SET modificado_excel = 1
                        WHERE DATE(fecha) = ? AND estacion = ?
                    ");
                    $stmtMod->bind_param("ss", $fecha, $estacion);
                    $stmtMod->execute();
                }
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
