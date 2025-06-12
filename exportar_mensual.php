<?php
require 'vendor/autoload.php';
require 'db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Recibir rango de fechas
$inicio = $_GET['inicio'] ?? '';
$fin = $_GET['fin'] ?? '';

if (!$inicio || !$fin) {
    die('⚠️ Debes proporcionar fechas inicio y fin.');
}

// Consulta mensual con los cálculos (usa la que tienes ya probada)
$sql = "
SELECT 
    razon_social,
    estacion,
    siic_inteligas,
    zona,

    ROUND((precio_magna / NULLIF(pf_magna, 0) - 1) * 100, 2) AS vu_magna,
    ROUND((precio_premium / NULLIF(pf_premium, 0) - 1) * 100, 2) AS vu_premium,
    ROUND((precio_diesel / NULLIF(pf_diesel, 0) - 1) * 100, 2) AS vu_diesel,

    ROUND((
      COALESCE((precio_magna / NULLIF(pf_magna, 0) - 1) * 100, 0) +
      COALESCE((precio_premium / NULLIF(pf_premium, 0) - 1) * 100, 0) +
      COALESCE((precio_diesel / NULLIF(pf_diesel, 0) - 1) * 100, 0)
    ) / NULLIF(
      (CASE WHEN pf_magna > 0 THEN 1 ELSE 0 END +
       CASE WHEN pf_premium > 0 THEN 1 ELSE 0 END +
       CASE WHEN pf_diesel > 0 THEN 1 ELSE 0 END), 0), 2
    ) AS promedio_general_estacion,

    ROUND(precio_magna - pf_magna, 4) AS utilidad_magna,
    ROUND(precio_premium - pf_premium, 4) AS utilidad_premium,
    ROUND(precio_diesel - pf_diesel, 4) AS utilidad_diesel,

    ROUND((
      COALESCE(precio_magna - pf_magna, 0) +
      COALESCE(precio_premium - pf_premium, 0) +
      COALESCE(precio_diesel - pf_diesel, 0)
    ) / NULLIF(
      (CASE WHEN pf_magna > 0 THEN 1 ELSE 0 END +
       CASE WHEN pf_premium > 0 THEN 1 ELSE 0 END +
       CASE WHEN pf_diesel > 0 THEN 1 ELSE 0 END), 0), 4
    ) AS utilidad_promedio_litro

FROM precios_combustible
WHERE fecha BETWEEN ? AND ?
ORDER BY id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $inicio, $fin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('⚠️ No hay datos para el rango seleccionado.');
}

// Crear hoja Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Resumen Mensual');

// Encabezado general
$sheet->setCellValue('A2', "Resumen mensual del $inicio al $fin");
$sheet->mergeCells('A2:K2');
$sheet->getStyle('A2')->applyFromArray([
    'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => Color::COLOR_BLACK]],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFCCF2F4']]
]);

// Encabezado agrupado
$sheet->setCellValue('D3', 'PROMEDIO DE UTILIDAD (%)');
$sheet->mergeCells('D3:G3');
$sheet->getStyle('D3:G3')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF261FB3']]
]);

$sheet->setCellValue('H3', 'PROMEDIO DE UTILIDAD POR LITRO');
$sheet->mergeCells('H3:K3');
$sheet->getStyle('H3:K3')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF261FB3']]
]);

// Encabezados individuales
$headers = [
    'SIIC', 'ZONA', 'ESTACIÓN',
    'MAGNA', 'PREMIUM', 'DIESEL', 'PROMEDIO GENERAL',
    'MAGNA', 'PREMIUM', 'DIESEL', 'UTILIDAD PROMEDIO'
];
$sheet->fromArray($headers, NULL, 'A4');

// Estilos encabezados fijos (primeras 3 columnas)
$sheet->getStyle('A4:C4')->applyFromArray([
    'font' => ['bold' => true],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF000000']],
    'font' => ['color' => ['argb' => Color::COLOR_WHITE]],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

// Estilo columna ESTACIÓN específica (como en tabla, fondo #A55B4B)
$sheet->getStyle('C4')->applyFromArray([
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFA55B4B']],
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
]);

// Estilos para columnas Promedio Utilidad (D4:G4)
$sheet->getStyle('D4:G4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF261FB3']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

// Estilos para columnas Promedio Utilidad por Litro (H4:L4)
$sheet->getStyle('H4:K4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF261FB3']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

// Estilos de cada columna Promedio Utilidad (con colores particulares)
$sheet->getStyle('D4')->applyFromArray([
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF399918']], // verde
]);
$sheet->getStyle('E4')->applyFromArray([
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF0000']], // rojo
]);
$sheet->getStyle('F4')->applyFromArray([
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF000000']], // negro
]);

// Mismos colores para utilidad por litro
$sheet->getStyle('H4')->applyFromArray([
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF399918']], // verde
]);
$sheet->getStyle('I4')->applyFromArray([
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF0000']], // rojo
]);
$sheet->getStyle('J4')->applyFromArray([
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF000000']], // negro
]);

// Color para promedio general y utilidad promedio
$sheet->getStyle('G4')->applyFromArray([
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFA55B4B']], 
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
]);
$sheet->getStyle('K4')->applyFromArray([
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFA55B4B']],
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
]);

// Insertar datos a partir fila 5
$row = 5;

while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue("A{$row}", $data['siic_inteligas']);
    $sheet->setCellValue("B{$row}", $data['zona']);
    $sheet->setCellValue("C{$row}", $data['estacion']);

    $sheet->setCellValue("D{$row}", $data['vu_magna'] / 100);
    $sheet->setCellValue("E{$row}", $data['vu_premium'] / 100);
    $sheet->setCellValue("F{$row}", $data['vu_diesel'] / 100);
    $sheet->setCellValue("G{$row}", $data['promedio_general_estacion'] / 100);

    $sheet->setCellValue("H{$row}", $data['utilidad_magna']);
    $sheet->setCellValue("I{$row}", $data['utilidad_premium']);
    $sheet->setCellValue("J{$row}", $data['utilidad_diesel']);
    $sheet->setCellValue("K{$row}", $data['utilidad_promedio_litro']);

    $row++;
}

// Última fila con datos (la que acabamos de insertar)
$lastRow = $row - 1;

// Aplicar formato porcentaje con 2 decimales a columnas D a G
$sheet->getStyle("D5:G{$lastRow}")
      ->getNumberFormat()
      ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);

// Aplicar formato moneda ($) con 2 decimales a columnas H a K
$sheet->getStyle("H5:K{$lastRow}")
      ->getNumberFormat()
      ->setFormatCode('"$"#,##0.00');

// Aplicar borde inferior a la última fila con datos en las columnas A a K
$sheet->getStyle("A{$lastRow}:K{$lastRow}")->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Autoajustar ancho columnas
foreach (range('A', 'K') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Eliminar filas vacías con estilos después de la última fila con datos
$maxRow = 1000;
if ($lastRow < $maxRow) {
    $sheet->removeRow($lastRow + 1, $maxRow - $lastRow);
}

// Preparar salida del archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="resumen_mensual_'.$inicio.'_a_'.$fin.'.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
