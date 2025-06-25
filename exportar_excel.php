<?php
require 'vendor/autoload.php';
require 'db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Validar la fecha
$fecha = $_GET['fecha'] ?? '';
if (!$fecha) {
    die('⚠️ Fecha no proporcionada.');
}

// Consulta registros modificados de esa fecha
$query = "SELECT siic_inteligas, zona, estacion, precio_magna, precio_premium, precio_diesel 
          FROM precios_combustible 
          WHERE modificado_excel = 1 AND fecha = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $fecha);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('⚠️ No hay registros modificados para esa fecha.');
}

// Crear hoja Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Precios Modificados');

// Encabezado general
$sheet->setCellValue('A2', 'Precios autorizados para el ' . $fecha);
$sheet->mergeCells('A2:F2');
$sheet->getStyle('A2')->applyFromArray([
    'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => Color::COLOR_BLACK]],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFCCF2F4']
    ]
]);

// Encabezado agrupado
$sheet->setCellValue('D3', 'PRECIOS AUTORIZADOS');
$sheet->mergeCells('D3:F3');
$sheet->getStyle('D3:F3')->applyFromArray([
    'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => Color::COLOR_BLACK]],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
]);

// Encabezados individuales
$headers = ['SIIC', 'ZONA', 'ESTACION', 'PRECIO MAGNA', 'PRECIO PREMIUM', 'PRECIO DIESEL'];
$sheet->fromArray($headers, NULL, 'A4');

// Estilos de encabezados
$sheet->getStyle('A4:C4')->applyFromArray([
    'font' => ['bold' => true],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9D9D9']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

$sheet->getStyle('D4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4CAF50']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

$sheet->getStyle('E4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF44336']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

$sheet->getStyle('F4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF000000']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

// Insertar datos
$row = 5;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue("A{$row}", $data['siic_inteligas']);
    $sheet->setCellValue("B{$row}", $data['zona']);
    $sheet->setCellValue("C{$row}", $data['estacion']);
    $sheet->setCellValue("D{$row}", $data['precio_magna']);
    $sheet->setCellValue("E{$row}", $data['precio_premium']);
    $sheet->setCellValue("F{$row}", $data['precio_diesel']);

    // Aplicar formato de dinero $#,##0.00
        $sheet->getStyle("D{$row}:F{$row}")
              ->getNumberFormat()
              ->setFormatCode('"$"#,##0.00');


    // Estilos por columna de precios
    $sheet->getStyle("D{$row}")->applyFromArray([
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFC8E6C9']],
    ]);
    $sheet->getStyle("E{$row}")->applyFromArray([
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFCDD2']],
    ]);
    $sheet->getStyle("F{$row}")->applyFromArray([
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEEEEEE']],
    ]);

    // Bordes de toda la fila
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);

    $row++;
}

// Autoajustar columnas
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Cerrar conexión
$conn->close();

// Descargar archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"precios_modificados_{$fecha}.xlsx\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
