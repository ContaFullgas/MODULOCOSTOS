<?php
require 'db.php'; // tu archivo de conexión a BD

if (!isset($_POST['fecha']) || empty($_POST['fecha'])) {
    echo "Fecha no proporcionada.";
    exit;
}

$fecha = $_POST['fecha'];

// Ejecuta eliminaciones de ambas tablas si están relacionadas por fecha
$stmt1 = $conn->prepare("DELETE FROM precios_uuid WHERE precio_id IN (SELECT id FROM precios_combustible WHERE fecha = ?)");
$stmt1->execute([$fecha]);

$stmt2 = $conn->prepare("DELETE FROM precios_combustible WHERE fecha = ?");
$stmt2->execute([$fecha]);

echo "Registros del día $fecha eliminados correctamente.";
