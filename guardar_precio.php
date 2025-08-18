<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'No se recibieron datos JSON válidos']);
    exit;
}

$mensaje = '';

$razon_social = $conn->real_escape_string($data['razon_social'] ?? '');
$estacion     = $conn->real_escape_string($data['estacion'] ?? '');
$precio       = floatval($data['precio'] ?? 0); // VU (sin flete)
$tipo         = $conn->real_escape_string($data['tipo'] ?? '');
$uuid         = $conn->real_escape_string($data['uuid'] ?? '');
$fecha        = $conn->real_escape_string($data['fecha'] ?? date('Y-m-d'));
$flete        = isset($data['flete']) ? floatval($data['flete']) : 0.25;

// Validación mínima
if (!$razon_social || !$estacion || !$tipo || !$uuid) {
    echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios: razon_social, estacion, tipo o uuid']);
    exit;
}

// Evitar UUID duplicado
$check = $conn->query("SELECT id FROM precios_uuid WHERE uuid = '$uuid'");
if ($check === false) {
    echo json_encode(['success' => false, 'error' => 'Error en consulta UUID: ' . $conn->error]);
    exit;
}
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'UUID ya registrado']);
    exit;
}

// Mapear columnas según tipo
if ($tipo === 'Diesel') {
    $campo_vu = 'vu_diesel';
    $campo_pf = 'pf_diesel';
    $campo_pv = 'precio_diesel';
    $campo_util = 'utilidad_litro_diesel';
    $campo_por  = 'porcentaje_utilidad_diesel';
} elseif ($tipo === 'Magna') {
    $campo_vu = 'vu_magna';
    $campo_pf = 'pf_magna';
    $campo_pv = 'precio_magna';
    $campo_util = 'utilidad_litro_magna';
    $campo_por  = 'porcentaje_utilidad_magna';
} elseif ($tipo === 'Premium') {
    $campo_vu = 'vu_premium';
    $campo_pf = 'pf_premium';
    $campo_pv = 'precio_premium';
    $campo_util = 'utilidad_litro_premium';
    $campo_por  = 'porcentaje_utilidad_premium';
} else {
    echo json_encode(['success' => false, 'error' => 'Tipo de combustible no válido']);
    exit;
}

// -----------------------------
// Función: Recalcular posteriores
// -----------------------------
// Reglas:
// - Día base ($fechaInicio):
//     * Si VU y PF son válidos -> guardar referencia (vu y flete).
//     * Si faltan -> referencia nula (corta cadena).
// - Días posteriores (fecha > $fechaInicio) y NO modificados (xml/excel = 0):
//     * Si NO hay referencia -> utilidad/% a NULL (no tocar VU/PF existentes).
//     * Si hay referencia -> propagar VU/PF desde referencia y calcular utilidad/% con precio_*.
//       - utilidad = precio - pf
//       - % = ((precio/pf)-1)*100
//       - Si precio es NULL, pf <= 0, o utilidad < 0 -> utilidad/% = NULL.
// - Guardar NULL reales (no 0.00) en utilidad/%.
function recalcularPreciosPosterioresDesdeFecha($conn, $estacion, $fechaInicio) {
    $tipos = ['diesel', 'magna', 'premium'];
    $ref = []; // ['comb' => ['vu' => float|NULL, 'flete' => float|NULL]]

    $sql = "SELECT * FROM precios_combustible WHERE estacion = ? AND fecha >= ? ORDER BY fecha ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $estacion, $fechaInicio);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $fechaActual = $row['fecha'];
        $idFila = (int)$row['id'];

        foreach ($tipos as $c) {
            $c_vu   = "vu_$c";
            $c_pf   = "pf_$c";
            $c_pv   = "precio_$c";
            $c_util = "utilidad_litro_$c";
            $c_por  = "porcentaje_utilidad_$c";

            // Día base: definir referencia
            if ($fechaActual === $fechaInicio) {
                if (!is_null($row[$c_vu]) && !is_null($row[$c_pf])) {
                    $ref[$c] = [
                        'vu'    => (float)$row[$c_vu],
                        'flete' => (float)$row[$c_pf] - (float)$row[$c_vu],
                    ];
                } else {
                    // Día base sin datos válidos: cortar cadena
                    $ref[$c] = ['vu' => null, 'flete' => null];
                }
                continue;
            }

            // Respeta únicamente filas con costo fijado por XML en ese día.
            // Si fue modificado por Excel, SÍ recalculamos (actualizamos VU/PF y utilidad/%).
            if ((int)$row['modificado_xml'] === 1) {
                continue;
            }


            // Si no hay referencia -> limpiar utilidad/% y seguir (no tocar VU/PF)
            if (!isset($ref[$c]) || is_null($ref[$c]['vu']) || is_null($ref[$c]['flete'])) {
                $sqlNull = "UPDATE precios_combustible SET $c_util = NULL, $c_por = NULL WHERE id = $idFila";
                $conn->query($sqlNull);
                continue;
            }

            // Hay referencia: propagar VU/PF de referencia
            $nuevo_vu = $ref[$c]['vu'];
            $nuevo_pf = $ref[$c]['vu'] + $ref[$c]['flete'];

            // Calcular util/% con precio_* del registro según reglas (como Excel)
            $precioVenta = $row[$c_pv]; // puede ser NULL
            $utilidad = null;
            $porcentaje = null;

            if (!is_null($precioVenta) && !is_null($nuevo_pf) && $nuevo_pf > 0) {
                $utilidadCalc = (float)$precioVenta - (float)$nuevo_pf;
                if ($utilidadCalc >= 0) {
                    $utilidad = $utilidadCalc;
                    $porcentaje = ((float)$precioVenta / (float)$nuevo_pf - 1) * 100.0;
                } // si negativa, se quedan NULL
            }

            // Guardar NULL reales en utilidad/% (no 0.00)
            $util_sql = is_null($utilidad)   ? "NULL" : sprintf("%.4f", $utilidad);
            $por_sql  = is_null($porcentaje) ? "NULL" : sprintf("%.4f", $porcentaje);
            $vu_sql   = sprintf("%.4f", $nuevo_vu);
            $pf_sql   = sprintf("%.4f", $nuevo_pf);

            $sqlUp = "UPDATE precios_combustible 
                      SET $c_vu = $vu_sql, $c_pf = $pf_sql, $c_util = $util_sql, $c_por = $por_sql
                      WHERE id = $idFila";
            $conn->query($sqlUp);
        }
    }

    $stmt->close();
}

// -----------------------------
// Guardar/actualizar el día base
// -----------------------------
$precio_flete = $precio + $flete;

$sqlBuscar = "SELECT * FROM precios_combustible WHERE estacion = '$estacion' AND fecha = '$fecha'";
$resBuscar = $conn->query($sqlBuscar);
if ($resBuscar === false) {
    echo json_encode(['success' => false, 'error' => 'Error al buscar registro: ' . $conn->error]);
    exit;
}

if ($resBuscar->num_rows === 0) {
    // NO insertamos si no existe el día base
    echo json_encode([
        'success' => false,
        'error' => 'No existe registro base para esa estación y fecha. Genera el día primero.'
    ]);
    exit;
}

$row = $resBuscar->fetch_assoc();
$precioId = (int)$row['id'];

// Actualizar solo si cambió VU o PF del día base
if (is_null($row[$campo_vu]) || (float)$row[$campo_vu] != $precio || (float)$row[$campo_pf] != $precio_flete) {
    $sqlUpdate = "UPDATE precios_combustible 
                  SET $campo_vu = $precio, $campo_pf = $precio_flete, costo_flete = $flete, modificado_xml = 1, razon_social = '$razon_social'
                  WHERE id = $precioId";
    $resUpdate = $conn->query($sqlUpdate);
    if ($resUpdate === false) {
        echo json_encode(['success' => false, 'error' => 'Error al actualizar registro: ' . $conn->error]);
        exit;
    }

    // Recalcular posteriores
    recalcularPreciosPosterioresDesdeFecha($conn, $estacion, $fecha);

    $mensaje = 'Registro base actualizado y posteriores recalculados.';
} else {
    $mensaje = 'El valor es igual al del anterior registro; no se modificó el día base.';
}

// Registrar UUID SOLO si hubo registro base (existe $precioId)
if (isset($precioId)) {
    $sqlUuid = "INSERT INTO precios_uuid (uuid, precio_id) VALUES ('$uuid', $precioId)";
    $resUuid = $conn->query($sqlUuid);
    if ($resUuid === false) {
        echo json_encode(['success' => false, 'error' => 'Error al insertar UUID: ' . $conn->error]);
        exit;
    }
}

echo json_encode(['success' => true, 'accion' => $mensaje]);
