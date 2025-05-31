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

$razon_social = $conn->real_escape_string($data['razon_social'] ?? '');
$estacion     = $conn->real_escape_string($data['estacion'] ?? '');
$precio       = floatval($data['precio'] ?? 0);
$tipo         = $conn->real_escape_string($data['tipo'] ?? '');
$uuid         = $conn->real_escape_string($data['uuid'] ?? '');
$fecha = $conn->real_escape_string($data['fecha'] ?? date('Y-m-d'));

error_log("Revisando UUID: $uuid");
// Verificar UUID duplicado
$check = $conn->query("SELECT id FROM precios_uuid WHERE uuid = '$uuid'");
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'UUID ya registrado']);
    exit;
}

// Se define el costo fijo del flete que se sumará al precio unitario
$flete = 0.25;

// Inicialización de variables que almacenarán los nombres de las columnas según el tipo de combustible
$campo = '';
$campo_flete = '';

// Asignar los nombres de los campos en la base de datos de acuerdo al tipo de combustible
if ($tipo === 'Diesel') {
    $campo = 'vu_diesel';               // Columna del precio unitario Diesel
    $campo_flete = 'pf_diesel';   // Columna del precio Diesel con flete
} elseif ($tipo === 'Magna') {
    $campo = 'vu_magna';
    $campo_flete = 'pf_magna';
} elseif ($tipo === 'Premium') {
    $campo = 'vu_premium';
    $campo_flete = 'pf_premium';
} else {
    // Si el tipo no es válido, se responde con error y se detiene la ejecución
    echo json_encode(['success' => false, 'error' => 'Tipo de combustible no válido']);
    exit;
}

// Se calcula el precio con flete sumando el costo fijo al precio base
$precio_flete = $precio + $flete;

// Se busca si ya existe un registro con la misma razón social, estación y fecha
$sqlBuscar = "SELECT * FROM precios_combustible WHERE razon_social = '$razon_social' AND estacion = '$estacion' AND fecha = '$fecha'";
$resBuscar = $conn->query($sqlBuscar);

// Si ya existe el registro
if ($resBuscar->num_rows > 0) {
    $row = $resBuscar->fetch_assoc();       // Obtener los datos actuales del registro
    $precioId = $row['id'];                 // Guardar el ID del registro

    // Solo actualizar si el valor existente es nulo o diferente del nuevo precio
    if (is_null($row[$campo]) || floatval($row[$campo]) != $precio) {
        $conn->query("UPDATE precios_combustible 
                      SET $campo = $precio, $campo_flete = $precio_flete, costo_flete = $flete
                      WHERE id = $precioId");
    }
} else {
    // Si no existe registro, se inserta uno nuevo con los datos y precios correspondientes
    $sqlInsert = "INSERT INTO precios_combustible 
                  (razon_social, estacion, fecha, $campo, $campo_flete, costo_flete)
                  VALUES ('$razon_social', '$estacion', '$fecha', $precio, $precio_flete, $flete)";
    
    // Verifica si la inserción falló
    if (!$conn->query($sqlInsert)) {
        echo json_encode(['success' => false, 'error' => 'Error al insertar en precios_combustible: ' . $conn->error]);
        exit;
    }

    // Guarda el ID del nuevo registro insertado
    $precioId = $conn->insert_id;
}

// Insertar UUID con referencia a precio_id
// $conn->query("INSERT INTO precios_uuid (uuid, precio_id) VALUES ('$uuid', $precioId)");
$sqlUuid = "INSERT INTO precios_uuid (uuid, precio_id) VALUES ('$uuid', $precioId)";
if (!$conn->query($sqlUuid)) {
    echo json_encode(['success' => false, 'error' => 'Error al insertar UUID: ' . $conn->error]);
    exit;
}


echo json_encode(['success' => true]);
