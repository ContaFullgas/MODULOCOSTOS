<?php
// upload.php ya no se utiliza este archivo de código, puede ser eliminado, fue cambiado por procesar_xml.php para usar AJAX
$resultado = null;
$error = null;

if (isset($_GET['ok']) && file_exists('temp_resultado.json')) {
    $resultado = json_decode(file_get_contents('temp_resultado.json'), true);
    unlink('temp_resultado.json'); // elimina el archivo tras usarlo
}

function parse_xml($ruta) {
    $xml = simplexml_load_file($ruta);
    if (!$xml) {
        return null;
    }
    $namespaces = $xml->getNamespaces(true);
    $receptor = $xml->children($namespaces['cfdi'])->Receptor;
    $nombreReceptor = (string) $receptor->attributes()['Nombre'];
    $rfcReceptor = (string) $receptor->attributes()['Rfc'];

    $conceptos = $xml->children($namespaces['cfdi'])->Conceptos;
    $concepto = $conceptos->children($namespaces['cfdi'])->Concepto[0];
    $cantidad = (string) $concepto->attributes()['Cantidad'];
    $importe = (string) $concepto->attributes()['Importe'];
    $claveProdServ = (string) $concepto->attributes()['ClaveProdServ'];

    return [
        'nombre' => $nombreReceptor,
        'rfc' => $rfcReceptor,
        'cantidad' => $cantidad,
        'importe' => $importe,
        'claveProdServ' => $claveProdServ
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['documento'])) {
    $uploads_dir = 'uploads';
    if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0777, true);

    $tmp_name = $_FILES['documento']['tmp_name'];
    $name = basename($_FILES['documento']['name']);
    $ruta = "$uploads_dir/$name";

    if (move_uploaded_file($tmp_name, $ruta)) {
        $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
        if ($extension === 'xml') {
            $resultado = parse_xml($ruta);
            if ($resultado !== null) {
                file_put_contents('temp_resultado.json', json_encode($resultado));
                header("Location: upload.php?ok=1");
                exit;
            } else {
                $error = "No se pudo analizar el archivo XML.";
            }
        } else {
            $error = "El archivo no es un XML válido.";
        }
    } else {
        $error = "Error al subir el archivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Subida de Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container py-4">
    <h1>Subir Facturas (XML)</h1>

    <form action="upload.php" method="post" enctype="multipart/form-data" class="mb-4" id="formUpload">
        <div class="mb-3">
            <input type="file" name="documento" class="form-control" accept=".pdf,.xml" required id="inputFile" />
        </div>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($resultado): ?>
        <div class="card mt-4">
            <div class="card-header">Datos extraídos del XML</div>
            <div class="card-body">
                <p><strong>Nombre del Receptor:</strong> <?= htmlspecialchars($resultado['nombre']) ?></p>
                <p><strong>RFC del Receptor:</strong> <?= htmlspecialchars($resultado['rfc']) ?></p>
                <p><strong>Cantidad:</strong> <?= htmlspecialchars($resultado['cantidad']) ?></p>
                <p><strong>Importe:</strong> <?= htmlspecialchars($resultado['importe']) ?></p>
                <?php
                $clave = $resultado['claveProdServ'];
                $tipoCombustible = match ($clave) {
                    '15101514' => 'Magna',
                    '15101515' => 'Premium',
                    '15101505' => 'Diesel',
                    default     => 'Desconocido',
                };
                ?>
                <p><strong>Combustible:</strong> <?= $tipoCombustible ?></p>
            </div>
        </div>
    <?php endif; ?>

    <script>
        document.getElementById('inputFile').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('formUpload').submit();
            }
        });
    </script>
</body>
</html>
