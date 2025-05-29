<?php
include 'db.php';

$fecha_sel = $_GET['fecha'] ?? null;

// if ($fecha_sel && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_sel)) {
    // Si el usuario seleccionó una fecha válida
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
// } else {
//     // Si no hay fecha (primera carga): mostrar últimos 3 meses
//     $fechaLimite = date('Y-m-d', strtotime('-3 months'));
//     $sql = "
//         SELECT 
//             razon_social,
//             estacion,
//             diesel,
//             magna,
//             premium,
//             fecha
//         FROM precios_combustible
//         WHERE fecha >= '$fechaLimite'
//         ORDER BY fecha DESC, razon_social, estacion
//     ";
// }


$result = $conn->query($sql);

if ($result === false) {
    echo "Error en la consulta: " . $conn->error;
    exit;
}
?>

<?php if ($result->num_rows > 0): ?>
    <div class="table-responsive rounded-4">
        <table class="table table-bordered table-hover align-middle  text-center table-hover">
            <thead>
                <tr>
                    <th class="table-dark" rowspan="2">Fecha</th>
                    <th class="RazonSocial border border-white" style="background-color: #4F1C51; color: white;" rowspan="2">RAZÓN SOCIAL</th>
                    <th class="Estacion border border-white" style="background-color: #A55B4B; color: white;" rowspan="2">ESTACIÓN</th>
                    <th class="border border-white" colspan="3" style="background-color: #261FB3; color: white;">PRECIO COSTO</th>
                </tr>
                <tr>
                    <th class="Magna border border-white" style="background-color: #399918; color: white;">MAGNA</th>
                    <th class="Premium border border-white" style="background-color: #FF0000; color: white;">PREMIUM</th>
                    <th class="Diesel border border-white" style="background-color: black; color: white;">DIESEL</th>
                </tr>
            </thead>

            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars(substr($row['fecha'], 0, 10)) ?></td>
                    <td><?= htmlspecialchars($row['razon_social']) ?></td>
                    <td><?= htmlspecialchars($row['estacion']) ?></td>
                    <td><?= $row['magna'] !== null ? '$' . number_format($row['magna'], 2) : '-' ?></td>
                    <td><?= $row['premium'] !== null ? '$' . number_format($row['premium'], 2) : '-' ?></td>
                    <td><?= $row['diesel'] !== null ? '$' . number_format($row['diesel'], 2) : '-' ?></td>
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
