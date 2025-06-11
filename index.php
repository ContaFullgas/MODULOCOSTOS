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
<h1>PRECIOS COMBUSTIBLES FULLGAS</h1>
<ul class="nav nav-tabs mt-4" id="myTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="diario-tab" data-bs-toggle="tab" data-bs-target="#diario" type="button" role="tab">Vista Diaria</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="mensual-tab" data-bs-toggle="tab" data-bs-target="#mensual" type="button" role="tab">Vista Mensual</button>
  </li>
</ul>

<div class="tab-content" id="myTabsContent">
  <!-- TAB DIARIO -->
  <div class="tab-pane fade show active" id="diario" role="tabpanel">
    <!-- Aquí va tu contenido existente de vista diaria -->
    <br>
    <h3>Subir Facturas (XML)</h3>

    <form id="formUpload" class="mb-4">
        <div class="mb-3">
            <input type="file" name="documento" class="form-control w-50" accept=".xml" required id="inputFile" disabled/>
            <small id="mensaje_fecha" class="text-danger">Seleccione una fecha para cargar archivos XML.</small>
        </div>
    </form>

    <div id="resultado"></div>

        

    <div class="d-flex flex-wrap gap-3 mb-3">
        <!-- Campo de fecha -->
        <div class="d-flex flex-column">
            <label for="fecha" class="form-label">Selecciona fecha:</label>
            <input type="date" id="fecha" name="fecha" class="form-control" style="min-width: 200px;">
        </div>

        <!-- Botones agrupados -->
        <div class="d-flex flex-wrap gap-3">
            <!-- Exportar -->
            <div class="d-flex flex-column">
                <label class="form-label">Exportar cambios:</label>
                <button class="btn btn-outline-primary" style="width: 130px;" onclick="exportarExcel()" id="btnExportar">
                    Exportar
                </button>
            </div>

            <!-- Borrar -->
            <div class="d-flex flex-column">
                <label class="form-label">Borrar día:</label>
                <button class="btn btn-outline-danger" style="width: 130px;" onclick="eliminarRegistrosPorFecha()">
                    Borrar
                </button>
            </div>

            <!-- Generar nuevo día -->
            <div class="d-flex flex-column">
                <label class="form-label">Generar nuevo día:</label>
                <button class="btn btn-outline-success" style="width: 130px;" type="button" onclick="generarNuevoDia()">
                    Generar día
                </button>
            </div>
        </div>
    </div>



    <div id="resultado_exportacion" class="mb-2"></div>
    <div id="resultado_generacion" class="mb-3"></div>
    <div id="resultado_verificar_registros" class="mb-3"></div>
    <div id="tablaPrecios"></div>
  </div>

  <!-- TAB MENSUAL -->
  <div class="tab-pane fade" id="mensual" role="tabpanel">
    <div class="p-3">
      <h3>Consulta Mensual de Promedios</h3>
      <div class="form-group">
        <label for="mes">Seleccionar mes:</label>
        <input type="month" id="mes" class="form-control w-25" />
        <button onclick="cargarPromedios()" class="btn btn-primary mt-2">Consultar</button>
      </div>

      <div id="mensajePromedios" class="alert alert-info mt-4">
        Selecciona un mes para consultar los promedios.
      </div>

    <div class="table-responsive mt-3" id="tablaPromediosContainer" style="display: none;">
        <table class="table table-bordered table-hover align-middle text-center mt-4" id="tablaPromedios">
            <thead>
                <tr>
                <th class="table-dark" rowspan="2">SIIC</th>
                <th class="table-dark" rowspan="2">ZONA</th>
                <th class="Estacion border border-white" style="background-color: #A55B4B; color: white;" rowspan="2">ESTACIÓN</th>
                <th class="border border-white" colspan="3" style="background-color: #261FB3; color: white;">PROMEDIO PRECIO COSTO</th>
            
                </tr>
                <tr>
                <th class="Magna border border-white" style="background-color: #399918; color: white;">MAGNA</th>
                <th class="Premium border border-white" style="background-color: #FF0000; color: white;">PREMIUM</th>
                <th class="Diesel border border-white" style="background-color: black; color: white;">DIESEL</th>
                
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    </div>
  </div>
</div>



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
                            // modalBody.innerHTML = `
                            //     <p><strong>Seleccione una estación:</strong></p>
                            //     <select id="selectEstacion" class="form-select">${options}</select>`;
                            
                            //Formatear fecha para que se muestre en el formato dia-mes-año
                            let fechaOriginal = data.data.fecha.split('T')[0];
                            let partesFecha = fechaOriginal.split('-'); // [AAAA, MM, DD]
                            let fechaCFDIModal = `${partesFecha[2]}/${partesFecha[1]}/${partesFecha[0]}`;

                            modalBody.innerHTML = `
                            <p><strong>Fecha:</strong> ${fechaCFDIModal}</p>
                            <p><strong>Razón social:</strong> ${data.data.nombre}</p>
                            <!-- <p><strong>RFC receptor:</strong> ${data.data.rfc}</p> -->
                            <p><strong>Tipo de combustible:</strong> ${tipo}</p>
                            <!-- <p><strong>Cantidad:</strong> ${data.data.cantidad}</p> -->
                            <!-- <p><strong>Total:</strong> $${data.data.total}</p> -->
                            <p><strong>Precio unitario:</strong> $${(parseFloat(data.data.total) / parseFloat(data.data.cantidad)).toFixed(2)}</p>
                            <p><strong>UUID:</strong> ${data.data.uuid}</p>
                            <hr>
                            <p><strong>Seleccione una estación:</strong></p>
                            <select id="selectEstacion" class="form-select">
                                <option value="" disabled selected>Seleccione una estación</option>
                                ${options}
                            </select>

                        `;

                        }

                        const modal = new bootstrap.Modal(document.getElementById('modalEstacion'));
                        modal.show();

                        document.getElementById('btnConfirmarEstacion').onclick = () => {
                            const select = document.getElementById('selectEstacion');

                            if (!select) {
                                alert('No hay estación para seleccionar.');
                                return;
                            }

                            if (!select || select.value === "") {
                                alert('Por favor, seleccione una estación antes de continuar.');
                                return;
                            }

                            const estacionId = select.value;
                            const estacionNombre = select.options[select.selectedIndex].text;
                            const total = parseFloat(data.data.total);
                            const cantidad = parseFloat(data.data.cantidad);
                            const precioUnitario = (total / cantidad).toFixed(2);
                            const fechaCFDI = data.data.fecha.split('T')[0];
                            const fechaSeleccionada = document.getElementById('fecha').value;

                            if (fechaCFDI !== fechaSeleccionada) {
                                alert(`La fecha del XML (${fechaCFDI}) no coincide con la fecha seleccionada (${fechaSeleccionada}), no es posible subir la información.`);
                                // modal.hide();
                                document.getElementById('inputFile').value = '';
                                return;
                            }

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
                                    alert('Acción: ' + res.accion);
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
        const fecha = document.getElementById('fecha').value;
        if (!fecha) {
            alert('Selecciona una fecha válida para generar el nuevo día.');
            return;
        }

        const confirmar = confirm(`¿Estás seguro de que deseas generar el nuevo día para la fecha ${fecha}?`);
        if (!confirmar) {
            return; // El usuario canceló la operación
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

            // Desbloquear el input después de generar el nuevo día
            document.getElementById("inputFile").disabled = false;
            mensaje_fecha.textContent = ""; // borrar mensaje si hay registros

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

    
    function exportarExcel() {
    const fecha = document.getElementById('fecha').value;
    const btn = document.getElementById('btnExportar');

    if (!fecha) {
        alert('Selecciona una fecha para exportar los cambios.');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = 'Generando...';

    fetch(`exportar_excel.php?fecha=${encodeURIComponent(fecha)}`)
        .then(response => {
            if (response.headers.get('Content-Type').includes('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
                return response.blob();
            } else {
                return response.text().then(text => { throw new Error(text); });
            }
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `cambios_${fecha}.xlsx`;
            document.body.appendChild(a);
            a.click();
            a.remove();
        })
        .catch(error => {
            document.getElementById('resultado_exportacion').innerHTML = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    ${error.message || 'No hay datos para exportar o ocurrió un error.'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>`;
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = 'Exportar';
        });
}

//Eliminar registros en la fecha seleccionada
function eliminarRegistrosPorFecha() {
    const fecha = document.getElementById('fecha').value;

    if (!fecha) {
        alert('Selecciona una fecha para borrar los registros.');
        return;
    }

    // Paso 1: Verificar si existen registros para esa fecha
    fetch('verificar_registros.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'fecha=' + encodeURIComponent(fecha)
    })
    .then(response => response.json())
    .then(data => {
        if (!data.existe) {
            // alert('No hay registros para esta fecha.');
            document.getElementById('resultado_verificar_registros').innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                No hay registros para esta fecha.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
            return;
        }

        // Confirmación antes de borrar
        if (!confirm(`¿Estás seguro de que deseas eliminar todos los registros del día ${fecha}? Esta acción no se puede deshacer.`)) {
            return;
        }

        // Paso 2: Eliminar registros
        fetch('eliminar_dia.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'fecha=' + encodeURIComponent(fecha)
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('resultado_generacion').innerHTML =
                `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    ${data}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>`;

            // Bloquear input y actualizar mensaje
            document.getElementById("inputFile").disabled = true;
            document.getElementById("mensaje_fecha").textContent = "No hay registros para esta fecha. Genera un nuevo día antes de cargar XML.";

            cargarTablaPrecios(fecha); // Recargar tabla
        })
        .catch(error => {
            console.error('Error al eliminar registros:', error);
            alert('Ocurrió un error al intentar eliminar los registros.');
        });

    })
    .catch(error => {
        console.error('Error al verificar registros:', error);
        alert('Ocurrió un error al verificar si hay registros.');
    });
}


document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalEstacion');
    const inputArchivo = document.getElementById('inputFile');

    // Cuando el modal se cierra
    modal.addEventListener('hidden.bs.modal', function () {
      // Limpiar input de archivo
      inputArchivo.value = '';
    });
  });

  //Evaluar que el input solo este desbloqueado cuando existan registros
  document.getElementById("fecha").addEventListener("change", function () {
    const fecha = this.value;

    fetch("verificar_registros.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "fecha=" + encodeURIComponent(fecha)
    })
    .then(response => response.json())
    .then(data => {
        if (data.existe) {
            document.getElementById("inputFile").disabled = false;
            mensaje_fecha.textContent = ""; // borrar mensaje si hay registros
        } else {
            document.getElementById("inputFile").disabled = true;
            // alert("No hay registros para esta fecha. Genera un nuevo día antes de cargar XML.");
            mensaje_fecha.textContent = "No hay registros para esta fecha. Genera un nuevo día antes de cargar XML.";
        }
    });
});

//Funcion para cargar promedios de estaciones
// function cargarPromedios() {
//   const mes = document.getElementById('mes').value;
//   if (!mes) return alert('Selecciona un mes');

//   fetch('api_promedios_mes.php', {
//     method: 'POST',
//     body: JSON.stringify({ mes }),
//     headers: { 'Content-Type': 'application/json' }
//   })
//   .then(res => res.json())
//   .then(data => {
//     const tbody = document.querySelector('#tablaPromedios tbody');
//     tbody.innerHTML = '';

//     if (data.length === 0) {
//       tbody.innerHTML = '<tr><td colspan="14" class="text-center">No hay datos para el mes seleccionado</td></tr>';
//       return;
//     }

//     data.forEach(row => {
//       const tr = document.createElement('tr');
//       tr.innerHTML = `
//         <td>${row.siic_inteligas ?? ''}</td>
//         <td>${row.zona ?? ''}</td>
//         <td>${row.estacion ?? ''}</td>
//         <td>${row.vu_magna !== null ? '$' + Number(row.vu_magna).toFixed(2) : '-'}</td>
//         <td>${row.vu_premium !== null ? '$' + Number(row.vu_premium).toFixed(2) : '-'}</td>
//         <td>${row.vu_diesel !== null ? '$' + Number(row.vu_diesel).toFixed(2) : '-'}</td>
        
//       `;
//       tbody.appendChild(tr);
//     });
//   });
// }

function cargarPromedios() {
  const mes = document.getElementById('mes').value;
  const mensaje = document.getElementById('mensajePromedios');
  const tabla = document.getElementById('tablaPromedios');
  const contenedorTabla = document.getElementById('tablaPromediosContainer');

  if (!mes) {
    mensaje.textContent = 'Por favor selecciona un mes válido.';
    mensaje.classList.replace('d-none', 'alert-info');
    contenedorTabla.style.display = 'none';
    return;
  }

  fetch('api_promedios_mes.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ mes })
  })
  .then(res => res.json())
  .then(data => {
    if (data.length === 0) {
      mensaje.textContent = 'No hay registros de precios para el mes seleccionado.';
      mensaje.classList.replace('d-none', 'alert-info');
      contenedorTabla.style.display = 'none';
    } else {
      const tbody = tabla.querySelector('tbody');
      tbody.innerHTML = '';
      data.forEach(row => {
        tbody.innerHTML += `
          <tr>
            <td>${row.siic_inteligas ?? ''}</td>
            <td>${row.zona ?? ''}</td>
            <td>${row.estacion ?? ''}</td>
            <td>${row.vu_magna !== null ? '$' + Number(row.vu_magna).toFixed(2) : '-'}</td>
            <td>${row.vu_premium !== null ? '$' + Number(row.vu_premium).toFixed(2) : '-'}</td>
            <td>${row.vu_diesel !== null ? '$' + Number(row.vu_diesel).toFixed(2) : '-'}</td>
          </tr>
        `;
      });
      mensaje.classList.add('d-none');
      contenedorTabla.style.display = 'block';
    }
  })
  .catch(err => {
    console.error(err);
    mensaje.textContent = 'Error al cargar los datos.';
    mensaje.classList.replace('d-none', 'alert-danger');
    contenedorTabla.style.display = 'none';
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

          <!-- Datos del XML
            <div class="mb-3 border rounded p-3 bg-light">
            <h6 class="text-secondary">Datos extraídos del XML</h6>
            <p><strong>Razón Social:</strong> <span id="razonSocial">-</span></p>
            <p><strong>Tipo de Combustible:</strong> <span id="tipoCombustible">-</span></p>
            <p><strong>Precio Unitario:</strong> <span id="precioUnitario">-</span></p>
            <p><strong>UUID:</strong> <span id="uuidFactura">-</span></p>
            </div> -->

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" id="btnConfirmarEstacion" class="btn btn-primary">Confirmar</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
