<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$mes = $data['mes'] ?? '';
$zona = $data['selectorZonaMensual'] ?? '';

if (!$mes) {
  echo json_encode([]);
  exit;
}

$inicio = $mes . "-01";
$fin = date("Y-m-t", strtotime($inicio));

$whereZona = $zona ? " AND e.zona_agrupada = '" . $conn->real_escape_string($zona) . "'" : '';

$sql = "
  SELECT
    b.estacion,
    MAX(b.razon_social)   AS razon_social,
    MAX(b.siic_inteligas) AS siic_inteligas,
    MAX(b.zona_original)  AS zona_original,
    MAX(b.zona_agrupada)  AS zona_agrupada,

    -- Promedios % por estación (AVG ignora NULLs)
    ROUND(AVG(b.vu_magna),   2) AS vu_magna,
    ROUND(AVG(b.vu_premium), 2) AS vu_premium,
    ROUND(AVG(b.vu_diesel),  2) AS vu_diesel,

    -- Promedio general % por estación (promedio del promedio-por-fila)
    ROUND(AVG(b.promedio_por_fila), 2) AS promedio_general_estacion,

    -- Promedios $ por estación
    ROUND(AVG(b.utilidad_magna),   4) AS utilidad_magna,
    ROUND(AVG(b.utilidad_premium), 4) AS utilidad_premium,
    ROUND(AVG(b.utilidad_diesel),  4) AS utilidad_diesel,

    -- Promedio $ general por estación (promedio del promedio-por-fila)
    ROUND(AVG(b.utilidad_prom_fila), 4) AS utilidad_promedio_litro

  FROM (
    SELECT
      pc.id,
      pc.fecha,
      pc.estacion,
      pc.razon_social,
      pc.siic_inteligas,
      pc.zona AS zona_original,
      e.zona_agrupada,

      /* % utilidad por combustible: NULL si inválido/negativo */
      CASE
        WHEN pc.precio_magna IS NULL OR pc.pf_magna IS NULL OR pc.pf_magna <= 0 OR (pc.precio_magna - pc.pf_magna) < 0 THEN NULL
        ELSE (pc.precio_magna / pc.pf_magna - 1) * 100
      END AS vu_magna,
      CASE
        WHEN pc.precio_premium IS NULL OR pc.pf_premium IS NULL OR pc.pf_premium <= 0 OR (pc.precio_premium - pc.pf_premium) < 0 THEN NULL
        ELSE (pc.precio_premium / pc.pf_premium - 1) * 100
      END AS vu_premium,
      CASE
        WHEN pc.precio_diesel IS NULL OR pc.pf_diesel IS NULL OR pc.pf_diesel <= 0 OR (pc.precio_diesel - pc.pf_diesel) < 0 THEN NULL
        ELSE (pc.precio_diesel / pc.pf_diesel - 1) * 100
      END AS vu_diesel,

      /* Promedio % por fila (solo válidos) */
      (
        (COALESCE(
           CASE WHEN pc.precio_magna IS NULL OR pc.pf_magna IS NULL OR pc.pf_magna <= 0 OR (pc.precio_magna - pc.pf_magna) < 0 THEN NULL
                ELSE (pc.precio_magna / pc.pf_magna - 1) * 100 END, 0)
         + COALESCE(
           CASE WHEN pc.precio_premium IS NULL OR pc.pf_premium IS NULL OR pc.pf_premium <= 0 OR (pc.precio_premium - pc.pf_premium) < 0 THEN NULL
                ELSE (pc.precio_premium / pc.pf_premium - 1) * 100 END, 0)
         + COALESCE(
           CASE WHEN pc.precio_diesel IS NULL OR pc.pf_diesel IS NULL OR pc.pf_diesel <= 0 OR (pc.precio_diesel - pc.pf_diesel) < 0 THEN NULL
                ELSE (pc.precio_diesel / pc.pf_diesel - 1) * 100 END, 0)
        ) /
        NULLIF(
          ( (pc.precio_magna   IS NOT NULL AND pc.pf_magna   IS NOT NULL AND pc.pf_magna   > 0 AND (pc.precio_magna   - pc.pf_magna)   >= 0) +
            (pc.precio_premium IS NOT NULL AND pc.pf_premium IS NOT NULL AND pc.pf_premium > 0 AND (pc.precio_premium - pc.pf_premium) >= 0) +
            (pc.precio_diesel  IS NOT NULL AND pc.pf_diesel  IS NOT NULL AND pc.pf_diesel  > 0 AND (pc.precio_diesel  - pc.pf_diesel)  >= 0)
          ), 0)
      ) AS promedio_por_fila,

      /* $ utilidad por litro: NULL si inválido/negativo */
      CASE
        WHEN pc.precio_magna IS NULL OR pc.pf_magna IS NULL OR pc.pf_magna <= 0 OR (pc.precio_magna - pc.pf_magna) < 0 THEN NULL
        ELSE (pc.precio_magna - pc.pf_magna)
      END AS utilidad_magna,
      CASE
        WHEN pc.precio_premium IS NULL OR pc.pf_premium IS NULL OR pc.pf_premium <= 0 OR (pc.precio_premium - pc.pf_premium) < 0 THEN NULL
        ELSE (pc.precio_premium - pc.pf_premium)
      END AS utilidad_premium,
      CASE
        WHEN pc.precio_diesel IS NULL OR pc.pf_diesel IS NULL OR pc.pf_diesel <= 0 OR (pc.precio_diesel - pc.pf_diesel) < 0 THEN NULL
        ELSE (pc.precio_diesel - pc.pf_diesel)
      END AS utilidad_diesel,

      /* Promedio $ por fila (solo válidos) */
      (
        (COALESCE(
           CASE WHEN pc.precio_magna IS NULL OR pc.pf_magna IS NULL OR pc.pf_magna <= 0 OR (pc.precio_magna - pc.pf_magna) < 0 THEN NULL
                ELSE (pc.precio_magna - pc.pf_magna) END, 0)
         + COALESCE(
           CASE WHEN pc.precio_premium IS NULL OR pc.pf_premium IS NULL OR pc.pf_premium <= 0 OR (pc.precio_premium - pc.pf_premium) < 0 THEN NULL
                ELSE (pc.precio_premium - pc.pf_premium) END, 0)
         + COALESCE(
           CASE WHEN pc.precio_diesel IS NULL OR pc.pf_diesel IS NULL OR pc.pf_diesel <= 0 OR (pc.precio_diesel - pc.pf_diesel) < 0 THEN NULL
                ELSE (pc.precio_diesel - pc.pf_diesel) END, 0)
        ) /
        NULLIF(
          ( (pc.precio_magna   IS NOT NULL AND pc.pf_magna   IS NOT NULL AND pc.pf_magna   > 0 AND (pc.precio_magna   - pc.pf_magna)   >= 0) +
            (pc.precio_premium IS NOT NULL AND pc.pf_premium IS NOT NULL AND pc.pf_premium > 0 AND (pc.precio_premium - pc.pf_premium) >= 0) +
            (pc.precio_diesel  IS NOT NULL AND pc.pf_diesel  IS NOT NULL AND pc.pf_diesel  > 0 AND (pc.precio_diesel  - pc.pf_diesel)  >= 0)
          ), 0)
      ) AS utilidad_prom_fila

    FROM precios_combustible pc
    LEFT JOIN (
      /* Vista deduplicada por estación para evitar duplicados del JOIN */
      SELECT nombre, MAX(zona_agrupada) AS zona_agrupada
      FROM estaciones
      GROUP BY nombre
    ) e ON pc.estacion = e.nombre
    WHERE pc.fecha BETWEEN '$inicio' AND '$fin' $whereZona
  ) AS b
  GROUP BY b.estacion
  ORDER BY MIN(b.id)
";



$res = $conn->query($sql);
$datos = [];

while ($row = $res->fetch_assoc()) {
  $datos[] = $row;
}

echo json_encode($datos);
?>
