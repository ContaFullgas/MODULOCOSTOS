<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subida de Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1>Subir Facturas (XML)</h1>

    <form id="formUpload" class="mb-4">
        <div class="mb-3">
            <input type="file" name="documento" class="form-control" accept=".xml" required id="inputFile" />
        </div>
    </form>

    <div id="resultado"></div>

    <script>
        document.getElementById('inputFile').addEventListener('change', function () {
            const fileInput = this;
            if (fileInput.files.length === 0) return;

            const formData = new FormData();
            formData.append('documento', fileInput.files[0]);

            fetch('procesar_xml.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const div = document.getElementById('resultado');

                if (data.success) {
                    const tipo = {
                        '15101514': 'Magna',
                        '15101515': 'Premium',
                        '15101505': 'Diesel'
                    }[data.data.claveProdServ] || 'Desconocido';

                    // Obtener estaciones y mostrar el modal primero
                    fetch(`get_estaciones.php?rfc=${encodeURIComponent(data.data.rfc)}`)
                        .then(response => response.json())
                        .then(estaciones => {
                            const modalBody = document.querySelector('#modalEstacion .modal-body');

                            if (estaciones.error) {
                                modalBody.innerHTML = `<p class="text-danger">Error: ${estaciones.error}</p>`;
                            } else if (estaciones.length === 0) {
                                modalBody.innerHTML = '<p>No se encontraron estaciones para este RFC.</p>';
                            } else {
                                const options = estaciones.map(est =>
                                    `<option value="${est.id}">${est.nombre}</option>`).join('');
                                modalBody.innerHTML = `
                                    <p><strong>Seleccione una estación:</strong></p>
                                    <select id="selectEstacion" class="form-select">${options}</select>`;
                            }

                            const modal = new bootstrap.Modal(document.getElementById('modalEstacion'));
                            modal.show();

                            document.getElementById('btnConfirmarEstacion').onclick = () => {
                                const select = document.getElementById('selectEstacion');
                                if (!select) {
                                    alert('No hay estación para seleccionar.');
                                    return;
                                }

                                const estacionId = select.value;
                                const estacionNombre = select.options[select.selectedIndex].text;

                                const importe = parseFloat(data.data.importe);
                                const cantidad = parseFloat(data.data.cantidad);
                                const precioUnitario = (importe / cantidad).toFixed(2);

                                const fechaCFDI = data.data.fecha.split('T')[0];

                                // Mostrar los datos, conservando los comentarios HTML
                                document.getElementById('resultado').innerHTML = `
                                    <div class="card mt-4">
                                        <div class="card-header">Datos extraídos del XML</div>
                                        <div class="card-body">
                                            <p><strong>Razón social:</strong> ${data.data.nombre}</p>
                                        <!-- <p><strong>Fecha del CFDI:</strong> ${data.data.fecha}</p> -->
                                             <p><strong>Fecha del CFDI:</strong> ${fechaCFDI}</p> 
                                        <!-- <p><strong>RFC del Receptor:</strong> ${data.data.rfc}</p> -->
                                            <p><strong>Estación:</strong> ${estacionNombre}</p>
                                        <!-- <p><strong>Cantidad:</strong> ${data.data.cantidad}</p> -->
                                        <!-- <p><strong>Importe:</strong> ${data.data.importe}</p> -->
                                            <p><strong>Precio Unitario:</strong> ${precioUnitario}</p>
                                            <p><strong>Combustible:</strong> ${tipo}</p>
                                        <!-- <p><strong>UUID:</strong> ${data.data.uuid}</p> -->
                                        </div>
                                    </div>`;

                                modal.hide();

                                // Llamar a guardar_precio.php
                                fetch('guardar_precio.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        razon_social: data.data.nombre,
                                        estacion: estacionNombre,
                                        precio: precioUnitario,
                                        tipo: tipo,
                                        uuid: data.data.uuid,
                                        fecha: fechaCFDI  // <--- Agregar esto
                                    })
                                })
                                .then(response => response.json())
                                .then(res => {
                                    console.log('Respuesta guardar_precio.php:', res);
                                    if (res.success) {
                                        // alert('Precio guardado exitosamente.');
                                        
                                    } else {
                                        alert('Error al guardar: ' + res.error);
                                    }
                                    //Resetear input
                                    document.getElementById('inputFile').value = '';
                                })
                                .catch(() => {
                                    alert('Error al enviar los datos al servidor.');
                                });
                            };
                        })
                        .catch(() => {
                            alert('Error al obtener estaciones.');
                        });
                } else {
                    div.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                }
            })
            .catch(err => {
                document.getElementById('resultado').innerHTML =
                    '<div class="alert alert-danger">Error de red o servidor.</div>';
                console.error(err);
            });
        });
    </script>

    <!-- Modal -->
    <div class="modal fade" id="modalEstacion" tabindex="-1" aria-labelledby="modalEstacionLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEstacionLabel">Seleccionar estación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body"></div>
          <div class="modal-footer">
            <button type="button" id="btnConfirmarEstacion" class="btn btn-primary">Confirmar</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
