<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$mes = $data['mes'] ?? '';

if (!$mes) {
  echo json_encode([]);
  exit;
}

$inicio = $mes . "-01";
$fin = date("Y-m-t", strtotime($inicio));

$sql = "
  SELECT 
    razon_social,
    estacion,
    siic_inteligas,
    zona,
    ROUND(AVG(vu_magna), 2) AS vu_magna,
    ROUND(AVG(vu_premium), 2) AS vu_premium,
    ROUND(AVG(vu_diesel), 2) AS vu_diesel
  FROM precios_combustible
  WHERE fecha BETWEEN '$inicio' AND '$fin'
  GROUP BY razon_social, estacion, siic_inteligas, zona
  ORDER BY id
";

$res = $conn->query($sql);
$datos = [];

while ($row = $res->fetch_assoc()) {
  $datos[] = $row;
}

echo json_encode($datos);
?>
