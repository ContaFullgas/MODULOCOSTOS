<?php
header('Content-Type: application/json');

if (!isset($_GET['rfc'])) {
    echo json_encode(['error' => 'No RFC proporcionado']);
    exit;
}

$rfc = $_GET['rfc'];

$conn = new mysqli('localhost', 'root', '', 'costos_raul_garcia');
if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

$stmt = $conn->prepare("SELECT id, nombre FROM estaciones WHERE rfc_receptor = ?");
$stmt->bind_param("s", $rfc);
$stmt->execute();
$result = $stmt->get_result();

$estaciones = [];
while ($row = $result->fetch_assoc()) {
    $estaciones[] = $row;
}

echo json_encode($estaciones);

$stmt->close();
$conn->close();
