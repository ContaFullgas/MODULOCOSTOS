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

// Definir campo según tipo de combustible
$campo = '';
if ($tipo === 'Diesel') {
    $campo = 'diesel';
} elseif ($tipo === 'Magna') {
    $campo = 'magna';
} elseif ($tipo === 'Premium') {
    $campo = 'premium';
} else {
    echo json_encode(['success' => false, 'error' => 'Tipo de combustible no válido']);
    exit;
}

// Buscar registro existente en precios_combustible
// $sqlBuscar = "SELECT * FROM precios_combustible WHERE razon_social = '$razon_social' AND estacion = '$estacion'";
// $resBuscar = $conn->query($sqlBuscar);
$sqlBuscar = "SELECT * FROM precios_combustible WHERE razon_social = '$razon_social' AND estacion = '$estacion' AND fecha = '$fecha'";
$resBuscar = $conn->query($sqlBuscar);


if ($resBuscar->num_rows > 0) {
    // Existe registro
    $row = $resBuscar->fetch_assoc();
    $precioId = $row['id'];

    // Actualizar solo si el campo está vacío o diferente
    if (is_null($row[$campo]) || floatval($row[$campo]) != $precio) {
        $conn->query("UPDATE precios_combustible SET $campo = $precio WHERE id = $precioId");
    }
} else {
    // Insertar nuevo registro
    // $conn->query("INSERT INTO precios_combustible (razon_social, estacion, $campo) VALUES ('$razon_social', '$estacion', $precio)");
    // Insertar nuevo registro con fecha
    $conn->query("INSERT INTO precios_combustible (razon_social, estacion, fecha, $campo) VALUES ('$razon_social', '$estacion', '$fecha', $precio)");
    $precioId = $conn->insert_id;
}

// Insertar UUID con referencia a precio_id
$conn->query("INSERT INTO precios_uuid (uuid, precio_id) VALUES ('$uuid', $precioId)");

echo json_encode(['success' => true]);
