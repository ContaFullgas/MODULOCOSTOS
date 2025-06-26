<?php
require 'vendor/autoload.php';
include 'db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Border;

$fecha = $_GET['fecha'] ?? '';
if (!$fecha) {
    die("Fecha no especificada.");
}

//Verificar que se hayan cargado xml
if (isset($_GET['validar']) && $_GET['validar'] == '1') {
    $fecha = $_GET['fecha'] ?? '';
    $stmt = $conn->prepare("SELECT COUNT(*) FROM precios_combustible WHERE DATE(fecha) = ? AND modificado_xml = 1");
    $stmt->bind_param('s', $fecha);
    $stmt->execute();
    $stmt->bind_result($cuenta);
    $stmt->fetch();
    $stmt->close();
    echo json_encode(['hayXml' => $cuenta > 0]);
    exit;
}

$query = "
    SELECT 
        pc.fecha,
        pc.siic_inteligas,
        pc.zona AS zona_original,
        e.zona_agrupada,
        pc.razon_social,
        pc.estacion,
        pc.vu_magna,
        pc.vu_premium,
        pc.vu_diesel,
        pc.costo_flete,
        pc.pf_magna,
        pc.pf_premium,
        pc.pf_diesel,
        pc.precio_magna,
        pc.precio_premium,
        pc.precio_diesel,
        pc.porcentaje_utilidad_magna,
        pc.porcentaje_utilidad_premium,
        pc.porcentaje_utilidad_diesel,
        pc.utilidad_litro_magna,
        pc.utilidad_litro_premium,
        pc.utilidad_litro_diesel
    FROM precios_combustible pc
    LEFT JOIN estaciones e ON pc.estacion = e.nombre
    WHERE DATE(pc.fecha) = '" . $conn->real_escape_string($fecha) . "'
    ORDER BY pc.id
";

$result = $conn->query($query);

// if ($result->num_rows === 0) {
//     echo '
//     <div id="tablaWrapper" data-hay-datos="0">
//         <div class="alert alert-warning alert-dismissible fade show" role="alert">
//             ⚠️ No hay registros de precios para la fecha seleccionada.
//             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
//         </div>
//     </div>';


//     exit; // Ya no sigas con la construcción de la tabla
// }

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Precios');

// Insertar columnas en blanco
$sheet->insertNewColumnBefore('E', 1);  // entre D y E
$sheet->insertNewColumnBefore('H', 1);  // entre G y H
$sheet->insertNewColumnBefore('I', 1);  // entre H y I
$sheet->insertNewColumnBefore('L', 1);  // entre K y L
$sheet->insertNewColumnBefore('K', 1);  // nueva columna entre J y K
$sheet->insertNewColumnBefore('N', 1);  // nueva columna entre M y N
$sheet->insertNewColumnBefore('R', 1);  // entre Q y R

// ENCABEZADOS
$sheet->mergeCells('A1:A2')->setCellValue('A1', 'FECHA');
$sheet->mergeCells('B1:B2')->setCellValue('B1', 'SIIC');
$sheet->mergeCells('C1:C2')->setCellValue('C1', 'ZONA');
$sheet->mergeCells('D1:D2')->setCellValue('D1', 'ESTACIÓN');

$sheet->mergeCells('F1:H1')->setCellValue('F1', 'PRECIO COSTO');
$sheet->setCellValue('F2', 'MAGNA');
$sheet->setCellValue('G2', 'PREMIUM');
$sheet->setCellValue('H2', 'DIESEL');

$sheet->mergeCells('J1:J2')->setCellValue('J1', 'COSTO DE FLETE');

$sheet->mergeCells('L1:N1')->setCellValue('L1', 'COSTO + FLETE');
$sheet->setCellValue('L2', 'P+F MAGNA');
$sheet->setCellValue('M2', 'P+F PREMIUM');
$sheet->setCellValue('N2', 'P+F DIESEL');

$sheet->mergeCells('P1:R1')->setCellValue('P1', 'PRECIO VENTA');
$sheet->setCellValue('P2', 'PRECIO MAGNA');
$sheet->setCellValue('Q2', 'PRECIO PREMIUM');
$sheet->setCellValue('R2', 'PRECIO DIESEL');

$sheet->mergeCells('T1:V1')->setCellValue('T1', '% DE UTILIDAD');
$sheet->setCellValue('T2', '% MAGNA');
$sheet->setCellValue('U2', '% PREMIUM');
$sheet->setCellValue('V2', '% DIESEL');

$sheet->mergeCells('X1:Z1')->setCellValue('X1', 'UTILIDAD POR LITRO');
$sheet->setCellValue('X2', '$ MAGNA');
$sheet->setCellValue('Y2', '$ PREMIUM');
$sheet->setCellValue('Z2', '$ DIESEL');

// ESTILOS DE ENCABEZADO
$headerColors = [
    'A1' => '444444', 'B1' => '444444', 'C1' => '444444', 'D1' => 'A55B4B',
    'F1:H1' => '261FB3', 'F2' => '399918', 'G2' => 'FF0000', 'H2' => '000000',
    'J1' => 'A55B4B',
    'L1:N1' => '261FB3', 'L2' => '399918', 'M2' => 'FF0000', 'N2' => '000000',
    'P1:R1' => '261FB3', 'P2' => '399918', 'Q2' => 'FF0000', 'R2' => '000000',
    'T1:V1' => '261FB3', 'T2' => '399918', 'U2' => 'FF0000', 'V2' => '000000',
    'X1:Z1' => '261FB3', 'X2' => '399918', 'Y2' => 'FF0000', 'Z2' => '000000',
];

foreach ($headerColors as $range => $color) {
    $sheet->getStyle($range)->applyFromArray([
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
        'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);
}

$fila = 3;
$zona_actual = '';

while ($row = $result->fetch_assoc()) {
    $zona = $row['zona_agrupada'] ?? 'SIN ZONA';

    if ($zona !== $zona_actual) {
        $sheet->mergeCells("A$fila:Z$fila")->setCellValue("A$fila", $zona);
        $sheet->getStyle("A$fila:Z$fila")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'BFBFBF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
        $fila++;
        $zona_actual = $zona;
    }

    $sheet->setCellValue("A$fila", $row['fecha']);
    $sheet->setCellValue("B$fila", $row['siic_inteligas']);
    $sheet->setCellValue("C$fila", $row['zona_original']);
    $sheet->setCellValue("D$fila", $row['estacion']);
    $sheet->setCellValue("F$fila", $row['vu_magna']);
    $sheet->setCellValue("G$fila", $row['vu_premium']);
    $sheet->setCellValue("H$fila", $row['vu_diesel']);
    $sheet->setCellValue("J$fila", $row['costo_flete']);
    $sheet->setCellValue("L$fila", $row['pf_magna']);
    $sheet->setCellValue("M$fila", $row['pf_premium']);
    $sheet->setCellValue("N$fila", $row['pf_diesel']);
    $sheet->setCellValue("P$fila", $row['precio_magna']);
    $sheet->setCellValue("Q$fila", $row['precio_premium']);
    $sheet->setCellValue("R$fila", $row['precio_diesel']);
    $sheet->setCellValue("T$fila", $row['porcentaje_utilidad_magna'] / 100);
    $sheet->setCellValue("U$fila", $row['porcentaje_utilidad_premium'] / 100);
    $sheet->setCellValue("V$fila", $row['porcentaje_utilidad_diesel'] / 100);
    $sheet->setCellValue("X$fila", $row['utilidad_litro_magna']);
    $sheet->setCellValue("Y$fila", $row['utilidad_litro_premium']);
    $sheet->setCellValue("Z$fila", $row['utilidad_litro_diesel']);

    //Colorear registros
    foreach (['A', 'B', 'C', 'D'] as $col) {
    $sheet->getStyle("{$col}{$fila}")->applyFromArray([
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FFDEEAF6'] // Azul claro
        ],
    ]);
}

    // Colorear y aplicar bordes solo a celdas con contenido, excluyendo columnas en blanco
    $colores = [
        'F' => 'FFC8E6C9', 'G' => 'FFFFCDD2', 'H' => 'FFEEEEEE',
        'L' => 'FFC8E6C9', 'M' => 'FFFFCDD2', 'N' => 'FFEEEEEE',
        'P' => 'FFC8E6C9', 'Q' => 'FFFFCDD2', 'R' => 'FFEEEEEE',
        'T' => 'FFC8E6C9', 'U' => 'FFFFCDD2', 'V' => 'FFEEEEEE',
        'X' => 'FFC8E6C9', 'Y' => 'FFFFCDD2', 'Z' => 'FFEEEEEE',
    ];
    foreach ($colores as $col => $color) {
        $sheet->getStyle("{$col}{$fila}")->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $color]],
        ]);
    }

    // Bordes sólo en columnas con contenido (no en columnas en blanco)
    foreach (array_merge(['A','B','C','D','F','G','H','J','L','M','N','P','Q','R','T','U','V','X','Y','Z']) as $col) {
        $sheet->getStyle("{$col}{$fila}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
    }

    $fila++;
}

// PROTECCIÓN
$sheet->getProtection()->setSheet(true);
foreach (range('A', 'Z') as $col) {
    for ($i = 3; $i < $fila; $i++) {
        $locked = in_array($col, ['P', 'Q', 'R']) ? Protection::PROTECTION_UNPROTECTED : Protection::PROTECTION_PROTECTED;
        $sheet->getStyle("$col$i")->getProtection()->setLocked($locked);
    }
}

// FORMATOS
$formato_dinero = '"$" #,##0.00';
$formato_porcentaje = '0.00%';
$columnas_dinero = ['F','G','H','J','L','M','N','P','Q','R','X','Y','Z'];
$columnas_porcentaje = ['T','U','V'];

foreach ($columnas_dinero as $col) {
    for ($i = 3; $i < $fila; $i++) {
        $sheet->getStyle("$col$i")->getNumberFormat()->setFormatCode($formato_dinero);
    }
}
foreach ($columnas_porcentaje as $col) {
    for ($i = 3; $i < $fila; $i++) {
        $sheet->getStyle("$col$i")->getNumberFormat()->setFormatCode($formato_porcentaje);
    }
}
foreach (['P','Q','R'] as $col) {
    for ($i = 3; $i < $fila; $i++) {
        $validation = $sheet->getCell("$col$i")->getDataValidation();
        $validation->setType(DataValidation::TYPE_DECIMAL);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Entrada inválida');
        $validation->setError('Solo se permiten números en esta celda.');
        $validation->setPromptTitle('Dato requerido');
    }
}

foreach (range('A', 'Z') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

//Fijar encabezados
$sheet->freezePane('A3');
// Congelar encabezados y columnas A-D
$sheet->freezePane('E3');


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ingresar_precio_venta_'.$fecha.'.xlsx"');
header('Cache-Control: max-age=0');
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
