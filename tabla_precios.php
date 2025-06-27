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

        pc.porcentaje_utilidad_magna,
        pc.porcentaje_utilidad_premium,
        pc.porcentaje_utilidad_diesel,
        pc.utilidad_litro_magna,
        pc.utilidad_litro_premium,
        pc.utilidad_litro_diesel,

        pc.modificado_xml,
        pc.modificado_excel


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
        <table class="table table-bordered table-hover align-middle  text-center table-hover" style="border-collapse: separate; border-spacing: 5px;">
            <thead>
                <tr>
                    <th class="RazonSocial border border-white rounded-4  text-center align-middle" style="background-color: black; color: white;  padding: 10px; font-family: 'Oswald', sans-serif;" rowspan="2">FECHA</th>
                    <th class="RazonSocial border border-white rounded-4  text-center align-middle" style="background-color: #4F1C51; color: white; ; padding: 10px; font-family: 'Oswald', sans-serif;" rowspan="2">SIIC</th>
                    <th class="Estacion border border-white rounded-4 text-center align-middle" style="background-color: #A55B4B; color: white; padding: 10px; font-family: 'Oswald', sans-serif;" rowspan="2">ZONA</th>
                    <!-- <th class="RazonSocial border border-white" style="background-color: #4F1C51; color: white;" rowspan="2">RAZÓN SOCIAL</th> -->
                    <th class="Estacion border border-white rounded-4 text-center align-middle" style="border-right-width: 8px; background-color: #DCA06D; color: white; padding: 10px; font-family: 'Oswald', sans-serif;" rowspan="2">ESTACIÓN</th>
                    <th class="border border-white rounded-4" colspan="3" style="background-color: #261FB3; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PRECIO COSTO</th>
                    <th class="Estacion border border-white rounded-4 text-center align-middle" style="border-right-width: 8px; background-color: #DCA06D; color: white; padding: 10px; font-family: 'Oswald', sans-serif;" rowspan="2">COSTO DE FLETE</th>
                    <th class="border border-white rounded-4" colspan="3" style="background-color: #261FB3; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">COSTO + FLETE</th>
                    <th class="border border-white rounded-4" colspan="3" style="background-color: #261FB3; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PRECIO VENTA</th>
                    <th class="border border-white rounded-4" colspan="3" style="background-color: #261FB3; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">% DE UTILIDAD</th>
                    <th class="border border-white rounded-4" colspan="3" style="background-color: #261FB3; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">UTILIDAD POR LITRO</th>
                </tr>
                <tr>
                    <th class="Magna border border-white rounded-4 text-center align-middle" style="background-color: #399918; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">MAGNA</th>
                    <th class="Premium border border-white rounded-4 text-center align-middle" style="background-color: #FF0000; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PREMIUM</th>
                    <th class="Diesel border border-white rounded-4 text-center align-middle" style="background-color: black; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">DIESEL</th>
                    <th class="Magna border border-white rounded-4 text-center align-middle" style="background-color: #399918; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">P+F MAGNA</th>
                    <th class="Premium border border-white rounded-4 text-center align-middle" style="background-color: #FF0000; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">P+F PREMIUM</th>
                    <th class="Diesel border border-white rounded-4 text-center align-middle" style="background-color: black; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">P+F DIESEL</th>
                    <th class="Magna border border-white rounded-4 text-center align-middle" style="background-color: #399918; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PRECIO MAGNA</th>
                    <th class="Premium border border-white rounded-4 text-center align-middle" style="background-color: #FF0000; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PRECIO PREMIUM</th>
                    <th class="Diesel border border-white rounded-4 text-center align-middle" style="background-color: black; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PRECIO DIESEL</th>
                    <th class="Magna border border-white rounded-4 text-center align-middle" style="background-color: #399918; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">% MAGNA</th>
                    <th class="Premium border border-white rounded-4 text-center align-middle" style="background-color: #FF0000; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">% PREMIUM</th>
                    <th class="Diesel border border-white rounded-4 text-center align-middle" style="background-color: black; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">% DIESEL</th>
                    <th class="Magna border border-white rounded-4 text-center align-middle" style="background-color: #399918; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">$ MAGNA</th>
                    <th class="Premium border border-white rounded-4 text-center align-middle" style="background-color: #FF0000; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">$ PREMIUM</th>
                    <th class="Diesel border border-white rounded-4 text-center align-middle" style="background-color: black; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">$ DIESEL</th>
                </tr>
            </thead>

            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <?php

                        $clases = [];
                        $titulo = '';

                        if ($row['modificado_xml'] == 1 && $row['modificado_excel'] == 1) {
                            $clases[] = 'modificado-ambos';
                            $titulo = 'Modificado por XML y Excel';
                        } elseif ($row['modificado_xml'] == 1) {
                            $clases[] = 'modificado-xml';
                            $titulo = 'Modificado por XML';
                        } elseif ($row['modificado_excel'] == 1) {
                            $clases[] = 'modificado-excel';
                            $titulo = 'Modificado por Excel';
                        }

                        $claseFinal = implode(' ', $clases);


                        // Si no hay zona agrupada (null), usar cadena vacía para evitar errores JS
                        $zonaAgrupada = $row['zona_agrupada'] ?? '';
                    ?>
                    <tr class="<?= $claseFinal ?>" title="<?= htmlspecialchars($titulo) ?>" data-zona-agrupada="<?= htmlspecialchars($zonaAgrupada, ENT_QUOTES, 'UTF-8') ?>">
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

                        <td><?= $row['porcentaje_utilidad_magna'] !== null ? number_format($row['porcentaje_utilidad_magna'], 2) . '%' : '-' ?></td>
                        <td><?= $row['porcentaje_utilidad_premium'] !== null ? number_format($row['porcentaje_utilidad_premium'], 2) . '%' : '-' ?></td>
                        <td><?= $row['porcentaje_utilidad_diesel'] !== null ? number_format($row['porcentaje_utilidad_diesel'], 2) . '%' : '-' ?></td>

                        <td><?= $row['utilidad_litro_magna'] !== null ? '$' . number_format($row['utilidad_litro_magna'], 2) : '-' ?></td>
                        <td><?= $row['utilidad_litro_premium'] !== null ? '$' . number_format($row['utilidad_litro_premium'], 2) : '-' ?></td>
                        <td><?= $row['utilidad_litro_diesel'] !== null ? '$' . number_format($row['utilidad_litro_diesel'], 2) : '-' ?></td>
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
    .modificado-xml td {
        background-color: #ffe0b2 !important; /* Naranja claro */
        font-weight: bold;
    }

    .modificado-excel td {
        background-color: #c8e6c9 !important; /* Verde claro */
        font-weight: bold;
    }

    .modificado-ambos td {
        background-color: #ef9a9a !important; /* Rojo claro */
        font-weight: bold;
    }
</style>
