<?php
require 'db.php'; // Asegúrate de tener tu conexión

if (!isset($_POST['fecha']) || empty($_POST['fecha'])) {
    echo 'Fecha no válida.';
    exit;
}

$fechaNueva = $_POST['fecha'];

// Verificar si ya existe
$check = $conn->prepare("SELECT COUNT(*) FROM precios_combustible WHERE fecha = ?");
$check->bind_param("s", $fechaNueva);
$check->execute();
$check->bind_result($existe);
$check->fetch();
$check->close();

if ($existe > 0) {
    echo "Ya existe un registro para la fecha $fechaNueva.";
    exit;
}

// Obtener la última fecha existente
$res = $conn->query("SELECT fecha FROM precios_combustible ORDER BY fecha DESC LIMIT 1");
if ($row = $res->fetch_assoc()) {
    $ultimaFecha = $row['fecha'];

    // Copiar datos de la última fecha con nueva fecha
    //Los precios se venta de los 3 combustibles y la utilidad por litro se insertan como nulos
    $sql = "INSERT INTO precios_combustible (fecha, siic_inteligas, zona, razon_social, estacion, vu_magna, vu_premium, vu_diesel, costo_flete, pf_magna, pf_premium, pf_diesel, precio_magna, precio_premium, precio_diesel, porcentaje_utilidad_magna, porcentaje_utilidad_premium, porcentaje_utilidad_diesel, utilidad_litro_magna, utilidad_litro_premium, utilidad_litro_diesel)
            SELECT ?, siic_inteligas, zona, razon_social, estacion, vu_magna, vu_premium, vu_diesel, costo_flete, pf_magna, pf_premium, pf_diesel, NULL, NULL, NULL, porcentaje_utilidad_magna, porcentaje_utilidad_premium, porcentaje_utilidad_diesel, NULL, NULL, NULL
            FROM precios_combustible
            WHERE fecha = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fechaNueva, $ultimaFecha);
    if ($stmt->execute()) {
        echo "Nuevo día generado correctamente con fecha $fechaNueva.";
    } else {
        echo "Error al generar el nuevo día.";
    }
    $stmt->close();
} else {
    echo "No hay datos anteriores para copiar.";
}
?>
