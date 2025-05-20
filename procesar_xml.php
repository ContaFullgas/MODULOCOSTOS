<?php
header('Content-Type: application/json');

function parse_xml($ruta) {
    $xml = simplexml_load_file($ruta);
    if (!$xml) return null;

    $namespaces = $xml->getNamespaces(true);
    $receptor = $xml->children($namespaces['cfdi'])->Receptor;
    $nombreReceptor = (string) $receptor->attributes()['Nombre'];
    $rfcReceptor = (string) $receptor->attributes()['Rfc'];

    $conceptos = $xml->children($namespaces['cfdi'])->Conceptos;
    $concepto = $conceptos->children($namespaces['cfdi'])->Concepto[0];

    $cantidad = (string) $concepto->attributes()['Cantidad'];
    $importe = (string) $concepto->attributes()['Importe'];
    $claveProdServ = (string) $concepto->attributes()['ClaveProdServ'];

    // UUID desde TimbreFiscalDigital
    $uuid = '';
    $complemento = $xml->children($namespaces['cfdi'])->Complemento;
    if ($complemento) {
        $timbre = $complemento->children($namespaces['tfd'])->TimbreFiscalDigital;
        if ($timbre) {
            $uuid = (string) $timbre->attributes()['UUID'];
        }
    }

    return [
        'nombre' => $nombreReceptor,
        'rfc' => $rfcReceptor,
        'cantidad' => $cantidad,
        'importe' => $importe,
        'claveProdServ' => $claveProdServ,
        'uuid' => $uuid
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
            unlink($ruta);

            if ($resultado !== null) {
                echo json_encode(['success' => true, 'data' => $resultado]);
                exit;
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo analizar el archivo XML.']);
                exit;
            }
        } else {
            unlink($ruta);
            echo json_encode(['success' => false, 'error' => 'El archivo no es un XML válido.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al subir el archivo.']);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Solicitud inválida.']);
