<?php
include 'db.php'; // O el nombre de tu archivo de conexión

$sql = "SELECT DISTINCT zona_agrupada FROM estaciones WHERE zona_agrupada IS NOT NULL ORDER BY zona_agrupada";
$result = $conn->query($sql);

$zonas = [];
while ($row = $result->fetch_assoc()) {
    $zonas[] = $row['zona_agrupada'];
}

echo json_encode($zonas);
?>
