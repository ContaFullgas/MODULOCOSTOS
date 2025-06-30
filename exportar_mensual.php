<?php
require 'vendor/autoload.php';
require 'db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$inicio = $_GET['inicio'] ?? '';
$fin = $_GET['fin'] ?? '';

if (!$inicio || !$fin) {
    die('⚠️ Debes proporcionar fechas inicio y fin.');
}

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
ORDER BY zona, id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $inicio, $fin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('⚠️ No hay datos para el rango seleccionado.');
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Resumen Mensual');

$sheet->setCellValue('A2', "Resumen mensual del $inicio al $fin");
$sheet->mergeCells('A2:M2');
$sheet->getStyle('A2')->applyFromArray([
    'font' => ['bold' => true, 'size' => 14],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFCCF2F4']]
]);

$sheet->setCellValue('E3', 'PROMEDIO DE UTILIDAD (%)');
$sheet->mergeCells('E3:H3');
$sheet->getStyle('E3:H3')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF261FB3']]
]);

$sheet->setCellValue('J3', 'PROMEDIO DE UTILIDAD POR LITRO');
$sheet->mergeCells('J3:M3');
$sheet->getStyle('J3:M3')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF261FB3']]
]);

$headers = [
    'SIIC', 'ZONA', 'ESTACIÓN', '',
    'MAGNA', 'PREMIUM', 'DIESEL', 'PROMEDIO GENERAL', '',
    'MAGNA', 'PREMIUM', 'DIESEL', 'UTILIDAD PROMEDIO'
];
$sheet->fromArray($headers, NULL, 'A4');

// Colorear encabezados individuales con sus respectivos colores
$sheet->getStyle('E4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('399918'); // MAGNA verde
$sheet->getStyle('F4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000'); // PREMIUM rojo
$sheet->getStyle('G4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('555555'); // DIESEL gris
$sheet->getStyle('H4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('A55B4B'); // PROMEDIO GENERAL café

$sheet->getStyle('J4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('399918'); // MAGNA verde
$sheet->getStyle('K4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000'); // PREMIUM rojo
$sheet->getStyle('L4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('555555'); // DIESEL gris
$sheet->getStyle('M4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('A55B4B'); // UTILIDAD PROMEDIO café


$sheet->getStyle('A4:C4')->applyFromArray([
    'font' => ['bold' => true],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF000000']],
    'font' => ['color' => ['argb' => Color::COLOR_WHITE]],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
]);

// $sheet->getStyle('E4:H4')->applyFromArray([
//     'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
//     'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF261FB3']],
// ]);
// $sheet->getStyle('J4:M4')->applyFromArray([
//     'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
//     'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF261FB3']],
// ]);

// Encabezados MAGNA, PREMIUM, DIESEL y PROMEDIO GENERAL
$sheet->getStyle('E4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF399918']] // verde
]);
$sheet->getStyle('F4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF0000']] // rojo
]);
$sheet->getStyle('G4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF000000']] // gris
]);
$sheet->getStyle('H4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFA55B4B']] // café
]);

// Encabezados UTILIDAD por litro MAGNA, PREMIUM, DIESEL y PROMEDIO
$sheet->getStyle('J4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF399918']] // verde
]);
$sheet->getStyle('K4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF0000']] // rojo
]);
$sheet->getStyle('L4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF000000']] // gris
]);
$sheet->getStyle('M4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFA55B4B']] // café
]);


$row = 5;
$zona_actual = '';

while ($data = $result->fetch_assoc()) {
    $zona = $data['zona'] ?? 'SIN ZONA';
    if ($zona !== $zona_actual) {
        $sheet->mergeCells("A{$row}:M{$row}");
        $sheet->setCellValue("A{$row}", $zona);
        $sheet->getStyle("A{$row}:M{$row}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFBFBFBF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $row++;
        $zona_actual = $zona;
    }

    $sheet->setCellValue("A{$row}", $data['siic_inteligas']);
    $sheet->setCellValue("B{$row}", $data['zona']);
    $sheet->setCellValue("C{$row}", $data['estacion']);
    $sheet->setCellValue("E{$row}", $data['vu_magna'] / 100);
    $sheet->setCellValue("F{$row}", $data['vu_premium'] / 100);
    $sheet->setCellValue("G{$row}", $data['vu_diesel'] / 100);
    $sheet->setCellValue("H{$row}", $data['promedio_general_estacion'] / 100);
    $sheet->setCellValue("J{$row}", $data['utilidad_magna']);
    $sheet->setCellValue("K{$row}", $data['utilidad_premium']);
    $sheet->setCellValue("L{$row}", $data['utilidad_diesel']);
    $sheet->setCellValue("M{$row}", $data['utilidad_promedio_litro']);

    // Colores por celda
    $sheet->getStyle("A{$row}:C{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9EAF7');
    $sheet->getStyle("E{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD5EAD5');
    $sheet->getStyle("F{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF4CCCC');
    $sheet->getStyle("G{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9D9D9');
    $sheet->getStyle("J{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD5EAD5');
    $sheet->getStyle("K{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF4CCCC');
    $sheet->getStyle("L{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9D9D9');

    $row++;
}

$lastRow = $row - 1;
$sheet->getStyle("E5:H{$lastRow}")
      ->getNumberFormat()
      ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE_00);
$sheet->getStyle("J5:M{$lastRow}")
      ->getNumberFormat()
      ->setFormatCode('"$"#,##0.00');

foreach (range('A', 'M') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"resumen_mensual_{$inicio}_a_{$fin}.xlsx\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
