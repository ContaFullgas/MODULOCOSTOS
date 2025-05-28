<?php
// $conn = new mysqli('localhost', 'root', '', 'costos_raul_garcia');
// if ($conn->connect_error) {
//     echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos']);
//     exit;
// }

include 'db.php';

$sql = "
    SELECT 
        razon_social,
        estacion,
        diesel,
        magna,
        premium
    FROM precios_combustible
    ORDER BY razon_social, estacion
";

$result = $conn->query($sql);

if ($result === false) {
    echo "Error en la consulta: " . $conn->error;
    exit;
}

if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
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
    <div class="alert alert-info">No hay registros de precios aún.</div>
<?php endif;

$conn->close();

?>
