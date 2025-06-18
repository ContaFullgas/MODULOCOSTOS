<?php
include 'db.php';

$fecha_sel = $_GET['fecha'] ?? null;

// if ($fecha_sel && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_sel)) {
    // Si el usuario seleccionó una fecha válida
    $sql = "
        SELECT 
        pc.fecha,
        pc.siic_inteligas,
        pc.zona AS zona_original,
        e.zona_agrupada,
        pc.razon_social,
        pc.estacion,
        pc.vu_magna,
        pc.vu_premium,
        pc.vu_diesel,
        pc.costo_flete,
        pc.pf_magna,
        pc.pf_premium,
        pc.pf_diesel,
        pc.precio_magna,
        pc.precio_premium,
        pc.precio_diesel,
        pc.modificado
    FROM precios_combustible pc
    LEFT JOIN estaciones e ON pc.estacion = e.nombre
    WHERE DATE(pc.fecha) = '$fecha_sel'
    ORDER BY pc.id
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

//Para saber desde el index si hay datos o no
echo '<div id="tablaWrapper" data-hay-datos="' . ($result->num_rows > 0 ? '1' : '0') . '">';
?>

<?php if ($result->num_rows > 0): ?>

    

    <div class="table-responsive rounded-4">
        <table class="table table-bordered table-hover align-middle  text-center table-hover">
            <thead>
                <tr>
                    <th class="table-dark" rowspan="2">FECHA</th>
                    <th class="table-dark" rowspan="2">SIIC</th>
                    <th class="table-dark" rowspan="2">ZONA</th>
                    <!-- <th class="RazonSocial border border-white" style="background-color: #4F1C51; color: white;" rowspan="2">RAZÓN SOCIAL</th> -->
                    <th class="Estacion border border-white" style="background-color: #A55B4B; color: white;" rowspan="2">ESTACIÓN</th>
                    <th class="border border-white" colspan="3" style="background-color: #261FB3; color: white;">PRECIO COSTO</th>
                    <th class="" style="background-color: #A55B4B; color: white;" rowspan="2">COSTO DE FLETE</th>
                    <th class="border border-white" colspan="3" style="background-color: #261FB3; color: white;">COSTO + FLETE</th>
                    <th class="border border-white" colspan="3" style="background-color: #261FB3; color: white;">PRECIO VENTA</th>
                </tr>
                <tr>
                    <th class="Magna border border-white" style="background-color: #399918; color: white;">MAGNA</th>
                    <th class="Premium border border-white" style="background-color: #FF0000; color: white;">PREMIUM</th>
                    <th class="Diesel border border-white" style="background-color: black; color: white;">DIESEL</th>
                    <th class="Magna border border-white" style="background-color: #399918; color: white;">P+F MAGNA</th>
                    <th class="Premium border border-white" style="background-color: #FF0000; color: white;">P+F PREMIUM</th>
                    <th class="Diesel border border-white" style="background-color: black; color: white;">P+F DIESEL</th>
                    <th class="Magna border border-white" style="background-color: #399918; color: white;">PRECIO MAGNA</th>
                    <th class="Premium border border-white" style="background-color: #FF0000; color: white;">PRECIO PREMIUM</th>
                    <th class="Diesel border border-white" style="background-color: black; color: white;">PRECIO DIESEL</th>
                </tr>
            </thead>

            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <?php
                        $clase = ($row['modificado'] == 1) ? 'registro-modificado' : '';
                        // Si no hay zona agrupada (null), usar cadena vacía para evitar errores JS
                        $zonaAgrupada = $row['zona_agrupada'] ?? '';
                    ?>
                    <tr class="<?= $clase ?>" data-zona-agrupada="<?= htmlspecialchars($zonaAgrupada, ENT_QUOTES, 'UTF-8') ?>">
                        <td><?= htmlspecialchars(substr($row['fecha'] ?? '', 0, 10)) ?></td>
                        <td><?= htmlspecialchars($row['siic_inteligas'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['zona_original'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['estacion'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>

                        <td><?= $row['vu_magna'] !== null ? '$' . number_format($row['vu_magna'], 2) : '-' ?></td>
                        <td><?= $row['vu_premium'] !== null ? '$' . number_format($row['vu_premium'], 2) : '-' ?></td>
                        <td><?= $row['vu_diesel'] !== null ? '$' . number_format($row['vu_diesel'], 2) : '-' ?></td>
                        <td><?= $row['costo_flete'] !== null ? '$' . number_format($row['costo_flete'], 2) : '-' ?></td>

                        <td><?= $row['pf_magna'] !== null ? '$' . number_format($row['pf_magna'], 2) : '-' ?></td>
                        <td><?= $row['pf_premium'] !== null ? '$' . number_format($row['pf_premium'], 2) : '-' ?></td>
                        <td><?= $row['pf_diesel'] !== null ? '$' . number_format($row['pf_diesel'], 2) : '-' ?></td>

                        <td><?= $row['precio_magna'] !== null ? '$' . number_format($row['precio_magna'], 2) : '-' ?></td>
                        <td><?= $row['precio_premium'] !== null ? '$' . number_format($row['precio_premium'], 2) : '-' ?></td>
                        <td><?= $row['precio_diesel'] !== null ? '$' . number_format($row['precio_diesel'], 2) : '-' ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
           
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">No hay registros de precios para la fecha seleccionada.</div>
<?php endif;

//Para el selector de zonas
echo '</div>';
$conn->close();
?>

<!-- Estilos para remarcar los registros que se modificaron -->
<style>
    .registro-modificado,
        .registro-modificado td {
            background-color:rgb(254, 197, 10) !important; /* Amarillo claro */
            font-weight: bold;
        }
</style>
