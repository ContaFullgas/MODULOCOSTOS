<?php
$conn = new mysqli('localhost', 'root', '', 'costos_raul_garcia');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos']);
    exit;
}
?>
