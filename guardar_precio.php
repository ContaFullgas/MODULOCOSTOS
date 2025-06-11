<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// $conn = new mysqli('localhost', 'root', '', 'costos_raul_garcia');
// if ($conn->connect_error) {
//     echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos']);
//     exit;
// }
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

//Variable para poner el mensaje de acuerdo a la acción realizada
$mensaje = '';

$razon_social = $conn->real_escape_string($data['razon_social'] ?? '');
$estacion     = $conn->real_escape_string($data['estacion'] ?? '');
$precio       = floatval($data['precio'] ?? 0);
$tipo         = $conn->real_escape_string($data['tipo'] ?? '');
$uuid         = $conn->real_escape_string($data['uuid'] ?? '');
$fecha = $conn->real_escape_string($data['fecha'] ?? date('Y-m-d'));

// Se define el costo fijo del flete que se sumará al precio unitario
$flete = 0.25; // de momento fijo pero luego sera variable

// Obtener IVA de la estación
$sqlIva = "SELECT iva FROM estaciones WHERE nombre = '$estacion'";
$resIva = $conn->query($sqlIva);
if ($resIva && $resIva->num_rows > 0) {
    $rowIva = $resIva->fetch_assoc();
    $iva = floatval($rowIva['iva']);
} else {
    //Respuesta si no tiene iva, sin iva asignado
    echo json_encode(['success' => false, 'error' => 'SIN IVA ASIGNADO']);
    exit;
}

// Obtener IEPS para el tipo de combustible y año 2025
$sqlIeps = "SELECT valor FROM ieps WHERE tipo_combustible = '$tipo' AND anio = 2025 LIMIT 1";
$resIeps = $conn->query($sqlIeps);
if ($resIeps && $resIeps->num_rows > 0) {
    $rowIeps = $resIeps->fetch_assoc();
    $ieps = floatval($rowIeps['valor']);
} else {
    echo json_encode(['success' => false, 'error' => 'SIN VALOR DE IEPS ASIGNADO']);
    exit;
}

error_log("Revisando UUID: $uuid");
// Verificar UUID duplicado
$check = $conn->query("SELECT id FROM precios_uuid WHERE uuid = '$uuid'");
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'UUID ya registrado']);
    exit;
}

// Se calcula el precio con flete sumando el costo fijo al precio base
$precio_flete = $precio + $flete;

// Calcular precio de venta
$precioVenta = ($precio_flete * (1 + $iva)) + $ieps;

// Inicialización de variables que almacenarán los nombres de las columnas según el tipo de combustible
$campo = '';
$campo_flete = '';
// $campo_vu = '';
// $campo_pf = '';
$campo_pv = '';

// if ($tipo === 'Diesel') {
//     $campo_vu = 'vu_diesel';
//     $campo_pf = 'pf_diesel';
//     $campo_pv = 'precio_diesel';
// } elseif ($tipo === 'Magna') {
//     $campo_vu = 'vu_magna';
//     $campo_pf = 'pf_magna';
//     $campo_pv = 'precio_magna';
// } elseif ($tipo === 'Premium') {
//     $campo_vu = 'vu_premium';
//     $campo_pf = 'pf_premium';
//     $campo_pv = 'precio_premium';
// } else {
//     echo json_encode(['success' => false, 'error' => 'Tipo de combustible no válido']);
//     exit;
// }

if ($tipo === 'Diesel') {
    $campo = 'vu_diesel';
    $campo_flete = 'pf_diesel';
    $campo_pv = 'precio_diesel';
} elseif ($tipo === 'Magna') {
    $campo = 'vu_magna';
    $campo_flete = 'pf_magna';
    $campo_pv = 'precio_magna';
} elseif ($tipo === 'Premium') {
    $campo = 'vu_premium';
    $campo_flete = 'pf_premium';
    $campo_pv = 'precio_premium';
} else {
    echo json_encode(['success' => false, 'error' => 'Tipo de combustible no válido']);
    exit;
}


//De momento busca el registro para modificar de acuerdo a la fecha y el nombre de la estación    
//Se debe checar que en la base de datos las dos tablas coincidan con los nombres de estación
$sqlBuscar = "SELECT * FROM precios_combustible WHERE estacion = '$estacion' AND fecha = '$fecha'";

$resBuscar = $conn->query($sqlBuscar);

// Si ya existe el registro
if ($resBuscar->num_rows > 0) {
    $row = $resBuscar->fetch_assoc();       // Obtener los datos actuales del registro
    $precioId = $row['id'];                 // Guardar el ID del registro

    // Solo actualizar si el valor existente es nulo o diferente del nuevo precio
    if (is_null($row[$campo]) || floatval($row[$campo]) != $precio) {
        $conn->query("UPDATE precios_combustible 
                      SET $campo = $precio, $campo_flete = $precio_flete, costo_flete = $flete, $campo_pv = $precioVenta, modificado = 1, razon_social = '$razon_social'
                      WHERE id = $precioId");
        
        $mensaje = 'se ha encontrado coincidencia con un registro y este ha sido modificado.';
    }
    else {
        $mensaje = 'el valor es igual al del anterior registro, por lo tanto no se modifica.';
    }
} else {
    // Si no existe registro, se inserta uno nuevo con los datos y precios correspondientes
    $sqlInsert = "INSERT INTO precios_combustible 
                  (razon_social, estacion, fecha, $campo, $campo_flete, costo_flete, $campo_pv, modificado)
                  VALUES ('$razon_social', '$estacion', '$fecha', $precio, $precio_flete, $flete, $precioVenta, 1)";
    
    // Verifica si la inserción falló
    if (!$conn->query($sqlInsert)) {
        echo json_encode(['success' => false, 'error' => 'Error al insertar en precios_combustible: ' . $conn->error]);
        exit;
    }

    // Guarda el ID del nuevo registro insertado
    $precioId = $conn->insert_id;
    $mensaje = 'no se ha encontrado coincidencia con ningún registro, por lo tanto se ingresó uno nuevo.';
    
}

// Insertar UUID con referencia a precio_id
// $conn->query("INSERT INTO precios_uuid (uuid, precio_id) VALUES ('$uuid', $precioId)");
$sqlUuid = "INSERT INTO precios_uuid (uuid, precio_id) VALUES ('$uuid', $precioId)";
if (!$conn->query($sqlUuid)) {
    echo json_encode(['success' => false, 'error' => 'Error al insertar UUID: ' . $conn->error]);
    exit;
}


// echo json_encode(['success' => true]);
//Manda la respuesta de acuerdo al mensaje
echo json_encode(['success' => true, 'accion' => $mensaje]);
