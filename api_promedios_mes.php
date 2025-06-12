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
    id,               -- ID del registro (clave primaria)
    fecha,            -- Fecha del registro
    razon_social,     -- Razón social de la estación
    estacion,         -- Nombre o identificador de la estación
    siic_inteligas,   -- Código SIIC
    zona,             -- Zona geográfica de la estación

    -- Porcentaje de utilidad por litro para cada combustible
    -- Se calcula como: (precio_venta / (costo + flete)) - 1, convertido a porcentaje
    ROUND((precio_magna / NULLIF(pf_magna, 0) - 1) * 100, 2) AS vu_magna,
    ROUND((precio_premium / NULLIF(pf_premium, 0) - 1) * 100, 2) AS vu_premium,
    ROUND((precio_diesel / NULLIF(pf_diesel, 0) - 1) * 100, 2) AS vu_diesel,

    -- Promedio general de utilidad en porcentaje por estación
    -- Promedia los tres combustibles si tienen datos válidos
    ROUND((
      COALESCE((precio_magna / NULLIF(pf_magna, 0) - 1) * 100, 0) +
      COALESCE((precio_premium / NULLIF(pf_premium, 0) - 1) * 100, 0) +
      COALESCE((precio_diesel / NULLIF(pf_diesel, 0) - 1) * 100, 0)
    ) / NULLIF(
      -- Contador de cuántos combustibles tienen datos válidos
      (CASE WHEN pf_magna > 0 THEN 1 ELSE 0 END +
       CASE WHEN pf_premium > 0 THEN 1 ELSE 0 END +
       CASE WHEN pf_diesel > 0 THEN 1 ELSE 0 END), 0), 2
    ) AS promedio_general_estacion,

    -- Utilidad monetaria por litro para cada combustible
    -- Se calcula como: precio_venta - (costo + flete)
    ROUND(precio_magna - pf_magna, 4) AS utilidad_magna,
    ROUND(precio_premium - pf_premium, 4) AS utilidad_premium,
    ROUND(precio_diesel - pf_diesel, 4) AS utilidad_diesel,

    -- Promedio general de la utilidad monetaria (por litro) considerando los tres combustibles
    ROUND((
      COALESCE(precio_magna - pf_magna, 0) +
      COALESCE(precio_premium - pf_premium, 0) +
      COALESCE(precio_diesel - pf_diesel, 0)
    ) / NULLIF(
      -- Nuevamente, contamos cuántos valores son válidos para evitar dividir entre cero
      (CASE WHEN pf_magna > 0 THEN 1 ELSE 0 END +
       CASE WHEN pf_premium > 0 THEN 1 ELSE 0 END +
       CASE WHEN pf_diesel > 0 THEN 1 ELSE 0 END), 0), 4
    ) AS utilidad_promedio_litro

  FROM precios_combustible
  WHERE fecha BETWEEN '$inicio' AND '$fin'  -- Solo registros dentro del mes seleccionado
  ORDER BY id  -- Se ordenan cronológicamente por su ID de registro
";


$res = $conn->query($sql);
$datos = [];

while ($row = $res->fetch_assoc()) {
  $datos[] = $row;
}

echo json_encode($datos);
?>
