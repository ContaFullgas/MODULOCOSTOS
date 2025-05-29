<?php
include 'db.php';

$fecha_sel = $_GET['fecha'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_sel)) {
    $fecha_sel = date('Y-m-d');
}

$sql = "
    SELECT 
        razon_social,
        estacion,
        diesel,
        magna,
        premium,
        fecha
    FROM precios_combustible
    WHERE DATE(fecha) = '$fecha_sel'
    ORDER BY razon_social, estacion
";

$result = $conn->query($sql);

if ($result === false) {
    echo "Error en la consulta: " . $conn->error;
    exit;
}
?>

<?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Razón Social</th>
                    <th>Estación</th>
                    <th>Diesel</th>
                    <th>Magna</th>
                    <th>Premium</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars(substr($row['fecha'], 0, 10)) ?></td>
                    <td><?= htmlspecialchars($row['razon_social']) ?></td>
                    <td><?= htmlspecialchars($row['estacion']) ?></td>
                    <td><?= $row['diesel'] !== null ? '$' . number_format($row['diesel'], 2) : '-' ?></td>
                    <td><?= $row['magna'] !== null ? '$' . number_format($row['magna'], 2) : '-' ?></td>
                    <td><?= $row['premium'] !== null ? '$' . number_format($row['premium'], 2) : '-' ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No hay registros de precios para la fecha seleccionada.</div>
<?php endif;

$conn->close();
?>
