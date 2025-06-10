<?php
include 'db.php'; // Asegúrate de que exista y se conecte bien

$fecha = $_POST['fecha'] ?? '';
$response = ['existe' => false];

if (!empty($fecha)) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM precios_combustible WHERE fecha = ?");
    $stmt->bind_param("s", $fecha);
    $stmt->execute();
    $stmt->bind_result($cantidad);
    $stmt->fetch();
    $stmt->close();

    if ($cantidad > 0) {
        $response['existe'] = true;
    }
}

echo json_encode($response);
