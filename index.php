<?php
date_default_timezone_set('America/Mexico_City');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subida de Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="container py-4">
    <h1>Subir Facturas (XML)</h1>

    <form id="formUpload" class="mb-4">
        <div class="mb-3">
            <input type="file" name="documento" class="form-control" accept=".xml" required id="inputFile" />
        </div>
    </form>

    <div id="resultado"></div>

    <div class="d-flex align-items-end gap-3 mb-3 flex-wrap">
        <div>
            <label for="fecha" class="form-label">Selecciona fecha:</label>
            <input type="date" id="fecha" name="fecha" class="form-control">
        </div>

        <div>
            <label for="nueva_fecha" class="form-label">Generar nuevo día:</label>
            <div class="input-group">
                <input type="date" id="nueva_fecha" class="form-control">
                <button class="btn btn-outline-success" type="button" onclick="generarNuevoDia()">Generar Día</button>
            </div>
        </div>
    </div>

    <div id="resultado_generacion" class="mb-3"></div>

    <div id="tablaPrecios"></div>

    <script>
        // Función para cargar la tabla con la fecha seleccionada
        function cargarTablaPrecios(fecha = null) {
            let url = 'tabla_precios.php';
            if (fecha) {
                url += '?fecha=' + fecha;
            }
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('tablaPrecios').innerHTML = html;
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const fechaInput = document.getElementById('fecha');

            if (!fechaInput.value) {
                // Si no hay fecha seleccionada, cargar últimos 3 meses
                cargarTablaPrecios();
            } else {
                cargarTablaPrecios(fechaInput.value);
            }

            fechaInput.addEventListener('change', function () {
                cargarTablaPrecios(this.value);
            });
        });

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

                                const total = parseFloat(data.data.total);
                                const cantidad = parseFloat(data.data.cantidad);
                                const precioUnitario = (total / cantidad).toFixed(2);

                                const fechaCFDI = data.data.fecha.split('T')[0];

                                modal.hide();

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
                                        fecha: fechaCFDI
                                    })
                                })
                                .then(response => response.json())
                                .then(res => {
                                    console.log('Respuesta guardar_precio.php:', res);
                                    if (res.success) {
                                        // Recargar la tabla para la fecha actual o seleccionada
                                        cargarTablaPrecios(document.getElementById('fecha').value);
                                    } else {
                                        alert('Error al guardar: ' + res.error);
                                    }
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
                    div.innerHTML = 
                    `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error de red o servidor.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>`;
                }
            })
            .catch(err => {
                document.getElementById('resultado').innerHTML =
                    `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${data.error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>`;
                console.error(err);
            });
        });

    function generarNuevoDia() {
        const fecha = document.getElementById('nueva_fecha').value;
        if (!fecha) {
            alert('Selecciona una fecha válida para generar el nuevo día.');
            return;
        }

        fetch('generar_dia.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'fecha=' + encodeURIComponent(fecha)
        })
        .then(response => response.text())
        // .then(data => {
        //     document.getElementById('resultado_generacion').innerHTML =
        //         '<div class="alert alert-info">' + data + '</div>';
        .then(data => {
        document.getElementById('resultado_generacion').innerHTML =
            `<div class="alert alert-info alert-dismissible fade show" role="alert">
                ${data}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
            // Recargar la tabla si la fecha actual coincide con la generada
            if (document.getElementById('fecha').value === fecha) {
                cargarTablaPrecios(fecha);
            }
        })
        .catch(error => {
            document.getElementById('resultado_generacion').innerHTML =
                `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error en la solicitud.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>`;
            console.error(error);
        });
    }

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
