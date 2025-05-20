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

                                // Mostrar los datos completos solo después de confirmar estación
                                document.getElementById('resultado').innerHTML = `
                                    <div class="card mt-4">
                                        <div class="card-header">Datos extraídos del XML</div>
                                        <div class="card-body">
                                            <p><strong>Nombre del Receptor:</strong> ${data.data.nombre}</p>
                                            <p><strong>Estación:</strong> ${estacionNombre}</p>
                                            <p><strong>Cantidad:</strong> ${data.data.cantidad}</p>
                                            <p><strong>Importe:</strong> ${data.data.importe}</p>
                                            <p><strong>Combustible:</strong> ${tipo}</p>
                                        </div>
                                    </div>`;

                                modal.hide();
                            };
                        })
                        .catch(() => {
                            alert('Error al obtener estaciones.');
                        });
                }
                 else {
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
