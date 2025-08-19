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
    b.estacion,
    MAX(b.razon_social)   AS razon_social,
    MAX(b.siic_inteligas) AS siic_inteligas,
    -- Usaremos esta como 'zona' para el título de grupo en el Excel
    MAX(b.zona_agrupada)  AS zona,

    -- Promedios % por estación
    ROUND(AVG(b.vu_magna),   2) AS vu_magna,
    ROUND(AVG(b.vu_premium), 2) AS vu_premium,
    ROUND(AVG(b.vu_diesel),  2) AS vu_diesel,

    -- Promedio general % por estación (promedio del promedio-por-fila)
    ROUND(AVG(b.promedio_por_fila), 2) AS promedio_general_estacion,

    -- Promedios $ por estación
    ROUND(AVG(b.utilidad_magna),   4) AS utilidad_magna,
    ROUND(AVG(b.utilidad_premium), 4) AS utilidad_premium,
    ROUND(AVG(b.utilidad_diesel),  4) AS utilidad_diesel,

    -- Promedio $ general por estación
    ROUND(AVG(b.utilidad_prom_fila), 4) AS utilidad_promedio_litro

  FROM (
    SELECT
      pc.id,
      pc.fecha,
      pc.estacion,
      pc.razon_social,
      pc.siic_inteligas,
      pc.zona AS zona_original,
      e.zona_agrupada,

      /* % utilidad por combustible: NULL si inválido/negativo */
      CASE
        WHEN pc.precio_magna IS NULL OR pc.pf_magna IS NULL OR pc.pf_magna <= 0 OR (pc.precio_magna - pc.pf_magna) < 0 THEN NULL
        ELSE (pc.precio_magna / pc.pf_magna - 1) * 100
      END AS vu_magna,
      CASE
        WHEN pc.precio_premium IS NULL OR pc.pf_premium IS NULL OR pc.pf_premium <= 0 OR (pc.precio_premium - pc.pf_premium) < 0 THEN NULL
        ELSE (pc.precio_premium / pc.pf_premium - 1) * 100
      END AS vu_premium,
      CASE
        WHEN pc.precio_diesel IS NULL OR pc.pf_diesel IS NULL OR pc.pf_diesel <= 0 OR (pc.precio_diesel - pc.pf_diesel) < 0 THEN NULL
        ELSE (pc.precio_diesel / pc.pf_diesel - 1) * 100
      END AS vu_diesel,

      /* Promedio % por fila (solo válidos) */
      (
        (COALESCE(
           CASE WHEN pc.precio_magna IS NULL OR pc.pf_magna IS NULL OR pc.pf_magna <= 0 OR (pc.precio_magna - pc.pf_magna) < 0 THEN NULL
                ELSE (pc.precio_magna / pc.pf_magna - 1) * 100 END, 0)
         + COALESCE(
           CASE WHEN pc.precio_premium IS NULL OR pc.pf_premium IS NULL OR pc.pf_premium <= 0 OR (pc.precio_premium - pc.pf_premium) < 0 THEN NULL
                ELSE (pc.precio_premium / pc.pf_premium - 1) * 100 END, 0)
         + COALESCE(
           CASE WHEN pc.precio_diesel IS NULL OR pc.pf_diesel IS NULL OR pc.pf_diesel <= 0 OR (pc.precio_diesel - pc.pf_diesel) < 0 THEN NULL
                ELSE (pc.precio_diesel / pc.pf_diesel - 1) * 100 END, 0)
        ) /
        NULLIF(
          ( (pc.precio_magna   IS NOT NULL AND pc.pf_magna   IS NOT NULL AND pc.pf_magna   > 0 AND (pc.precio_magna   - pc.pf_magna)   >= 0) +
            (pc.precio_premium IS NOT NULL AND pc.pf_premium IS NOT NULL AND pc.pf_premium > 0 AND (pc.precio_premium - pc.pf_premium) >= 0) +
            (pc.precio_diesel  IS NOT NULL AND pc.pf_diesel  IS NOT NULL AND pc.pf_diesel  > 0 AND (pc.precio_diesel  - pc.pf_diesel)  >= 0)
          ), 0)
      ) AS promedio_por_fila,

      /* $ utilidad por litro: NULL si inválido/negativo */
      CASE
        WHEN pc.precio_magna IS NULL OR pc.pf_magna IS NULL OR pc.pf_magna <= 0 OR (pc.precio_magna - pc.pf_magna) < 0 THEN NULL
        ELSE (pc.precio_magna - pc.pf_magna)
      END AS utilidad_magna,
      CASE
        WHEN pc.precio_premium IS NULL OR pc.pf_premium IS NULL OR pc.pf_premium <= 0 OR (pc.precio_premium - pc.pf_premium) < 0 THEN NULL
        ELSE (pc.precio_premium - pc.pf_premium)
      END AS utilidad_premium,
      CASE
        WHEN pc.precio_diesel IS NULL OR pc.pf_diesel IS NULL OR pc.pf_diesel <= 0 OR (pc.precio_diesel - pc.pf_diesel) < 0 THEN NULL
        ELSE (pc.precio_diesel - pc.pf_diesel)
      END AS utilidad_diesel,

      /* Promedio $ por fila (solo válidos) */
      (
        (COALESCE(
           CASE WHEN pc.precio_magna IS NULL OR pc.pf_magna IS NULL OR pc.pf_magna <= 0 OR (pc.precio_magna - pc.pf_magna) < 0 THEN NULL
                ELSE (pc.precio_magna - pc.pf_magna) END, 0)
         + COALESCE(
           CASE WHEN pc.precio_premium IS NULL OR pc.pf_premium IS NULL OR pc.pf_premium <= 0 OR (pc.precio_premium - pc.pf_premium) < 0 THEN NULL
                ELSE (pc.precio_premium - pc.pf_premium) END, 0)
         + COALESCE(
           CASE WHEN pc.precio_diesel IS NULL OR pc.pf_diesel IS NULL OR pc.pf_diesel <= 0 OR (pc.precio_diesel - pc.pf_diesel) < 0 THEN NULL
                ELSE (pc.precio_diesel - pc.pf_diesel) END, 0)
        ) /
        NULLIF(
          ( (pc.precio_magna   IS NOT NULL AND pc.pf_magna   IS NOT NULL AND pc.pf_magna   > 0 AND (pc.precio_magna   - pc.pf_magna)   >= 0) +
            (pc.precio_premium IS NOT NULL AND pc.pf_premium IS NOT NULL AND pc.pf_premium > 0 AND (pc.precio_premium - pc.pf_premium) >= 0) +
            (pc.precio_diesel  IS NOT NULL AND pc.pf_diesel  IS NOT NULL AND pc.pf_diesel  > 0 AND (pc.precio_diesel  - pc.pf_diesel)  >= 0)
          ), 0)
      ) AS utilidad_prom_fila

    FROM precios_combustible pc
    LEFT JOIN (
      /* Vista deduplicada por estación para evitar duplicados del JOIN */
      SELECT nombre, MAX(zona_agrupada) AS zona_agrupada
      FROM estaciones
      GROUP BY nombre
    ) e ON pc.estacion = e.nombre
    WHERE pc.fecha BETWEEN ? AND ?
  ) AS b
  GROUP BY b.estacion
  ORDER BY MIN(b.id)
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

    $sheet->setCellValue("E{$row}", ($data['vu_magna'] ?? 0) / 100);
    $sheet->setCellValue("F{$row}", ($data['vu_premium'] ?? 0) / 100);
    $sheet->setCellValue("G{$row}", ($data['vu_diesel'] ?? 0) / 100);
    $sheet->setCellValue("H{$row}", ($data['promedio_general_estacion'] ?? 0) / 100);

    $sheet->setCellValue("J{$row}", $data['utilidad_magna'] ?? 0);
    $sheet->setCellValue("K{$row}", $data['utilidad_premium'] ?? 0);
    $sheet->setCellValue("L{$row}", $data['utilidad_diesel'] ?? 0);
    $sheet->setCellValue("M{$row}", $data['utilidad_promedio_litro'] ?? 0);


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
