<?php
date_default_timezone_set('America/Mexico_City');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subida de Documentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="container-fluid py-4" style="background-color: #3a3d41; width: 90%;">
<div class="lighting-effect"></div>
<h1 class="d-flex justify-content-center" style="color: white;">PRECIOS COMBUSTIBLES FULLGAS</h1>
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
  <br>
  <h3 style="color: white;">Subir Facturas (XML)</h3>

  <div class="d-flex justify-content-between align-items-start flex-wrap">
    <!-- Input para subir XML -->
    <form id="formUpload" class="mb-3">
      <input type="file" name="documento" class="form-control w-100" accept=".xml" required id="inputFile" disabled style="max-width: 500px;" />
      <small id="mensaje_fecha" class="text-warning">Seleccione una fecha para cargar archivos XML.</small>
    </form>

    <!-- Botón Exportar Cambios alineado al mismo nivel -->
    <div class="d-flex flex-column align-items-end" style="margin-top: -30px;">
      <label class="form-label" style="color: white;">Exportar cambios:</label>
      <button class="boton-destacado btn btn-primary rounded-2 mb-4" style="width: 130px;" onclick="exportarExcel()" id="btnExportar">
        Exportar
      </button>
    </div>
  </div>

  <div class="d-flex flex-wrap gap-3 mb-4 align-items-end">
    <!-- Campo de fecha -->
    <div class="d-flex flex-column">
      <label for="fecha" class="form-label" style="color: white;">Selecciona fecha:</label>
      <input type="date" id="fecha" name="fecha" class="form-control" style="min-width: 200px;">
    </div>

    <!-- Selector de zona -->
    <div class="d-flex flex-column">
      <label for="selectorZona" class="form-label" style="color: white;">Filtrar por zona:</label>
      <select id="selectorZona" class="form-select" disabled style="min-width: 210px;">
        <option value="">Todas las zonas</option>
      </select>
    </div>

    <!-- Exportar día -->
    <div class="d-flex flex-column">
      <label class="form-label" style="color: white;">Exportar día:</label>
      <button class="boton-destacado btn btn-primary rounded-2" style="width: 130px;" id="btnExportarExcelDia">Exportar</button>
    </div>

    <!-- Borrar -->
    <div class="d-flex flex-column">
      <label class="form-label" style="color: white;">Borrar día:</label>
      <button class="boton-destacado btn btn-danger rounded-2" style="width: 130px;" onclick="eliminarRegistrosPorFecha()">Borrar</button>
    </div>

    <!-- Generar nuevo día -->
    <div class="d-flex flex-column">
      <label class="form-label" style="color: white;">Generar día:</label>
      <button class="boton-destacado btn btn-success" style="width: 130px;" type="button" onclick="generarNuevoDia()">Generar día</button>
    </div>

    <!-- Importar Excel -->
    <form id="formImportarExcel" enctype="multipart/form-data" class="d-flex flex-column">
      <label class="form-label" style="color: white;">Importar día:</label>
      <input type="file" name="archivo_excel" accept=".xlsx" required class="form-control" style="max-width: 500px;" />
      <input type="hidden" name="fecha" id="fecha_excel_hidden">
    </form>

  </div>

  <!-- Mensajes -->
  <div id="mensajeImportacion" class="mb-2"></div>
  <div id="resultado_exportacion" class="mb-2"></div>
  <div id="resultado_generacion" class="mb-3"></div>
  <div id="resultado_verificar_registros" class="mb-3"></div>

  <!-- Tabla -->
  <div id="tablaPrecios"></div>
</div>


  <!-- TAB MENSUAL -->
<div class="tab-pane fade" id="mensual" role="tabpanel">
  <!-- <div class="p-3"> -->
    <br>
    <h3 style="color: white;">Consulta Mensual de Promedios</h3>

    <!-- Contenedor de filtros y botones -->
    <div class="d-flex flex-wrap gap-3 mb-3">
      <!-- Campo de mes -->
      <div class="d-flex flex-column">
        <label for="mes" class="form-label" style="color: white;">Seleccionar mes:</label>
        <input type="month" id="mes" class="form-control" style="min-width: 200px;" />
      </div>

      <!-- Botón Exportar -->
      <div class="d-flex flex-column">
        <label class="form-label" style="color: white;">Exportar a Excel:</label>
        <button id="btnExportar" onclick="exportarExcelMensual()" class="boton-destacado btn btn-primary rounded-2" style="width: 130px;">
          Exportar
        </button>
      </div>

      <!-- Filtro por zona -->
      <div class="d-flex flex-column">
        <label for="selectorZonaMensual" class="form-label" style="color: white;">Filtrar por zona:</label>
        <select id="selectorZonaMensual" class="form-select" style="min-width: 210px;" disabled>
          <option value="">Todas las zonas</option>
        </select>
      </div>
    </div>

    <div id="resultado_verificar_registros_mes" class="mb-3"></div>
    <!-- Mensaje informativo -->
    <div id="mensajePromedios" class="alert alert-info mt-4">
      Selecciona un mes para consultar los promedios.
    </div>
  <!-- </div> -->
   
</div>

    <div class="table-responsive rounded-4" id="tablaPromediosContainer" style="display: none;">
        <table class="table table-bordered table-hover align-middle text-center mt-2" id="tablaPromedios" style="border-collapse: separate; border-spacing: 5px;">
            <thead>
                <tr>
                <th class="RazonSocial border border-white rounded-4  text-center align-middle" style="background-color: black; color: white;  padding: 10px;" rowspan="2">SIIC</th>
                <th class="RazonSocial border border-white rounded-4  text-center align-middle" style="background-color: #4F1C51; color: white; ; padding: 10px;" rowspan="2">ZONA</th>
                <th class="Estacion border border-white rounded-4 text-center align-middle" style="background-color: #A55B4B; color: white; padding: 10px;" rowspan="2">ESTACIÓN</th>
                <th class="border border-white rounded-4" colspan="4" style="background-color: #261FB3; color: white; padding: 10px;">PROMEDIO DE UTILIDAD</th>
                <th class="border border-white rounded-4" colspan="4" style="background-color: #261FB3; color: white; padding: 10px;">PROMEDIO DE UTILIDAD POR LITRO</th>
            
                </tr>
                <tr>
                <th class="Magna border border-white rounded-4 text-center align-middle" style="background-color: #399918; color: white; padding: 10px; ">MAGNA</th>
                <th class="Premium border border-white rounded-4 text-center align-middle" style="background-color: #FF0000; color: white; padding: 10px; ">PREMIUM</th>
                <th class="Diesel border border-white rounded-4 text-center align-middle" style="background-color: black; color: white; padding: 10px; ">DIESEL</th>
                <th class="Estacion border border-white rounded-4 text-center align-middle" style="border-right-width: 8px; background-color: #DCA06D; color: white; padding: 10px; ">PROMEDIO GENERAL POR ESTACION</th>
                <th class="Magna border border-white rounded-4 text-center align-middle" style="background-color: #399918; color: white; padding: 10px; ">MAGNA</th>
                <th class="Premium border border-white rounded-4 text-center align-middle" style="background-color: #FF0000; color: white; padding: 10px; ">PREMIUM</th>
                <th class="Diesel border border-white rounded-4 text-center align-middle" style="background-color: black; color: white; padding: 10px; ">DIESEL</th>
                <th class="Estacion border border-white rounded-4 text-center align-middle" style="border-right-width: 8px; background-color: #DCA06D; color: white; padding: 10px; ">UTILIDAD PROMEDIO</th>
                
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    </div>
  </div>
</div>

<!-- Botón flotante scroll -->
<button id="btnScroll" class="btn-scroll" title="Ir al final">↓</button>

<script>

//Funcion para boton para ir al final y al principio de la página, la función se ejecuta al cargar la página
function inicializarBotonScroll() {
    const btn = document.getElementById('btnScroll');

    if (!btn) return;

    btn.addEventListener('click', () => {
      if (btn.dataset.position === 'bottom') {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
      } else {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    });

    window.addEventListener('scroll', () => {
      const atBottom = window.innerHeight + window.scrollY >= document.body.scrollHeight - 5;
      if (atBottom) {
        btn.innerText = '↑';
        btn.title = 'Volver arriba';
        btn.dataset.position = 'top';
      } else if (window.scrollY < 100) {
        btn.innerText = '↓';
        btn.title = 'Ir al final';
        btn.dataset.position = 'bottom';
      }
    });

    // Estado inicial
    btn.innerText = '↓';
    btn.title = 'Ir al final';
    btn.dataset.position = 'bottom';
  }

  // Ejecutar al cargar la página el boton scroll que va de arriba hacia abajo, es el metodo de arriba
  document.addEventListener('DOMContentLoaded', inicializarBotonScroll);

//Metodo para ocultar información entre pestañas diaria y mensual
//Sin el, en el apartado diario se mostraba la tabla mensual, en lugar de solo la diaria
document.addEventListener('DOMContentLoaded', function () {
  const tabMensual = document.querySelector('#mensual-tab');
  const tabDiaria = document.querySelector('#diario-tab');
  const contenedorMensual = document.getElementById('tablaPromediosContainer');
  const tbodyMensual = document.querySelector('#tablaPromedios tbody');
  const mensajePromedios = document.getElementById('mensajePromedios');

  // Cuando se muestra el tab mensual
  tabMensual.addEventListener('shown.bs.tab', function () {
    const mes = document.getElementById('mes').value;
    if (mes) cargarPromedios();
  });

  // ✅ Cuando se activa otro tab (como diario), ocultamos los datos del mensual
  tabDiaria.addEventListener('shown.bs.tab', function () {
    contenedorMensual.style.display = 'none';
    tbodyMensual.innerHTML = '';
    mensajePromedios.classList.remove('alert-danger', 'alert-info');
    mensajePromedios.classList.add('d-none');
  });
});

//Para mostrar mensaje de seleccionar fecha
window.addEventListener('DOMContentLoaded', function () {
    const fechaInput = document.getElementById('fecha');
    const fecha = fechaInput?.value;
    // Llama siempre a cargarTablaPrecios, aunque la fecha sea null o vacía
    cargarTablaPrecios(fecha);
});

// Función para cargar la tabla con la fecha seleccionada
function cargarTablaPrecios(fecha = null, zona = null) {
    let url = 'tabla_precios.php';
    const params = [];

    if (fecha) params.push('fecha=' + encodeURIComponent(fecha));
    if (zona) params.push('zona=' + encodeURIComponent(zona));
    if (params.length) url += '?' + params.join('&');

    return fetch(url)
        .then(response => response.text())
        .then(html => {
            const tablaDiv = document.getElementById('tablaPrecios');
            tablaDiv.innerHTML = html;

            //Evalua si hay datos, si no hay desbloquea el selector de zona y si hay lo desbloquea
            const wrapper = tablaDiv.querySelector('#tablaWrapper');
            const hayDatos = wrapper?.dataset?.hayDatos === '1';
            const zonaInput = document.getElementById('selectorZona');
            zonaInput.disabled = !hayDatos;
            // zonaInput.style.display = hayDatos ? 'inline-block' : 'none';
        });
}

//Funcion para el selector de zonas
document.addEventListener('DOMContentLoaded', function () {
    const fechaInput = document.getElementById('fecha');
    const zonaInput = document.getElementById('selectorZona');

    // Ocultar o desactivar el selector al inicio
    // zonaInput.disabled = true;
    // zonaInput.style.display = 'none'; // <- si prefieres ocultarlo por completo

    const fecha = fechaInput.value;

    if (fecha) {
        cargarTablaPrecios(fecha).then(() => {
            // zonaInput.disabled = false;
            // zonaInput.style.display = 'inline-block';
            cargarZonas();
            filtrarPorZona();
        });
    }

    fechaInput.addEventListener('change', function () {
        const nuevaFecha = this.value;
        const zona = zonaInput.value;

        if (nuevaFecha) {
            zonaInput.disabled = false;
            // zonaInput.style.display = 'inline-block';
            cargarZonas(); // ahora sí
        } else {
            zonaInput.disabled = true;
            // zonaInput.style.display = 'none';
        }

        cargarTablaPrecios(nuevaFecha, zona).then(() => filtrarPorZona());
    });

    zonaInput.addEventListener('change', function () {
        const zona = this.value;
        const nuevaFecha = fechaInput.value;
        cargarTablaPrecios(nuevaFecha, zona).then(() => filtrarPorZona());
    });
});
    
//Funcion para cargar archivos xml
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

                            <p class="mt-3"><strong>Costo de flete:</strong></p>
                            <input type="number" id="inputFlete" class="form-control" placeholder="Ejemplo: 0.25" step="0.01" min="0">
                        `;

                        }

                        const modal = new bootstrap.Modal(document.getElementById('modalEstacion'));
                        modal.show();

                        document.getElementById('btnConfirmarEstacion').onclick = () => {
                            const select = document.getElementById('selectEstacion');

                            //Validaciones para las estaciones
                            if (!select) {
                                alert('No hay estación para seleccionar.');
                                return;
                            }

                            if (!select || select.value === "") {
                                alert('Por favor, seleccione una estación antes de continuar.');
                                return;
                            }

                            //Validaciones para el costo flete
                            const fleteInput = document.getElementById('inputFlete');
                            const fleteValue = fleteInput.value.trim();
                            // Validar que el usuario haya ingresado algo
                            if (fleteValue === '') {
                                alert('Por favor, ingresa un costo de flete antes de continuar.');
                                fleteInput.focus();
                                return;
                            }
                            const flete = parseFloat(fleteValue);
                            if (isNaN(flete) || flete < 0) {
                                alert('Ingresa un valor válido para el costo de flete (mayor o igual a 0).');
                                fleteInput.focus();
                                return;
                            }

                            const estacionId = select.value;
                            const estacionNombre = select.options[select.selectedIndex].text;
                            const total = parseFloat(data.data.total);
                            const cantidad = parseFloat(data.data.cantidad);
                            const precioUnitario = (total / cantidad).toFixed(2);
                            const fechaCFDI = data.data.fecha.split('T')[0];
                            const fechaSeleccionada = document.getElementById('fecha').value;
                            // const flete = parseFloat(document.getElementById('inputFlete').value) || 0.25;

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
                                    fecha: fechaCFDI,
                                    flete: flete
                                })
                            })
                            .then(response => response.json())
                            .then(res => {
                                console.log('Respuesta guardar_precio.php:', res);
                                if (res.success) {
                                    // Recargar la tabla para la fecha actual o seleccionada
                                    alert('Acción: ' + res.accion);

                                    const fecha = document.getElementById('fecha').value;
                                    //Carga los promedios diarios despues de que devuelve la promesa de terminacion el método cargarTablaPrecios
                                    cargarTablaPrecios(fecha).then(() => filtrarPorZona());
                                    // cargarZonas();

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
            // alert('Selecciona una fecha válida para generar el nuevo día.');
            document.getElementById('resultado_verificar_registros').innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Selecciona una fecha válida para generar el nuevo día.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
            return;
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
                  cargarTablaPrecios(fecha).then(() => {
                      //Promesa para cargar los promedios una vez que la tabla se alla terminado de cargar completamente
                      filtrarPorZona();
                  });
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
        // alert('Selecciona una fecha para exportar los cambios.');
        document.getElementById('resultado_verificar_registros').innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Selecciona una fecha para exportar los cambios.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
            return;
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
        // alert('Selecciona una fecha para borrar los registros.');
        document.getElementById('resultado_verificar_registros').innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Selecciona una fecha para borrar los registros.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
            return;
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

            cargarTablaPrecios(fecha).then(() => cargarZonas()); // Recargar tabla
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

    document.getElementById('selectorZonaMensual').addEventListener('change', function () {
        cargarPromedios();
    });

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

// function cargarPromedios() {
//   const mes = document.getElementById('mes').value;
//   const zona = document.getElementById('selectorZonaMensual').value;
//   const mensaje = document.getElementById('mensajePromedios');
//   const tabla = document.getElementById('tablaPromedios');
//   const contenedorTabla = document.getElementById('tablaPromediosContainer');
//   const selectorMensual = document.getElementById('selectorZonaMensual');

//   if (!mes) {
//     //Desactiva el selector de zona
//     selectorMensual.disabled = true;
//     mensaje.textContent = 'Por favor selecciona un mes válido.';
//     mensaje.classList.replace('d-none', 'alert-info');
//     contenedorTabla.style.display = 'none';
//     return;
//   }

//   fetch('api_promedios_mes.php', {
//     method: 'POST',
//     headers: { 'Content-Type': 'application/json' },
//     // body: JSON.stringify({ mes })
//     // body: JSON.stringify({ mes, selectorZonaMensual })
//     body: JSON.stringify({ mes, selectorZonaMensual: zona })
//   })
//   .then(res => res.json())
//   .then(data => {
//     if (data.length === 0) {
//       //Desactiva el selector de zona
//       selectorMensual.disabled = true;
//       mensaje.textContent = 'No hay registros de precios para el mes seleccionado.';
//       mensaje.classList.replace('d-none', 'alert-info');
//       contenedorTabla.style.display = 'none';
//     } else {
//       const tbody = tabla.querySelector('tbody');
//       tbody.innerHTML = '';
//       //Activa el selector de zona
//       selectorMensual.disabled = false;
//       data.forEach(row => {
//         tbody.innerHTML += `
//           <tr>
//             <td>${row.siic_inteligas ?? ''}</td>
//             <td>${row.zona_original ?? ''}</td>
//             <td>${row.estacion ?? ''}</td>
//             <td>${row.vu_magna !== null ? + Number(row.vu_magna).toFixed(2) + '%' : '-'}</td>
//             <td>${row.vu_premium !== null ? + Number(row.vu_premium).toFixed(2) + '%' : '-'}</td>
//             <td>${row.vu_diesel !== null ? + Number(row.vu_diesel).toFixed(2) + '%' : '-'}</td>
//             <td>${row.promedio_general_estacion !== null ? + Number(row.promedio_general_estacion).toFixed(2) + '%' : '-'}</td>

//             <td>${row.utilidad_magna !== null ? '$' + Number(row.utilidad_magna).toFixed(2) : '-'}</td>
//             <td>${row.utilidad_premium !== null ? '$' + Number(row.utilidad_premium).toFixed(2) : '-'}</td>
//             <td>${row.utilidad_diesel !== null ? '$' + Number(row.utilidad_diesel).toFixed(2) : '-'}</td>
//             <td>${row.utilidad_promedio_litro !== null ? '$' + Number(row.utilidad_promedio_litro).toFixed(2) : '-'}</td>
//           </tr>
//         `;
//       });
//       mensaje.classList.add('d-none');
//       contenedorTabla.style.display = 'block';
      
//     }
//   })
//   .catch(err => {
//     console.error(err);
//     mensaje.textContent = 'Error al cargar los datos.';
//     mensaje.classList.replace('d-none', 'alert-danger');
//     contenedorTabla.style.display = 'none';
//   });
// }

function cargarPromedios() {
  const mes = document.getElementById('mes').value;
  const zona = document.getElementById('selectorZonaMensual').value;
  const mensaje = document.getElementById('mensajePromedios');
  const tabla = document.getElementById('tablaPromedios');
  const contenedorTabla = document.getElementById('tablaPromediosContainer');
  const selectorMensual = document.getElementById('selectorZonaMensual');

  if (!mes) {
    //Desactiva el selector de zona
    selectorMensual.disabled = true;
    mensaje.textContent = 'Por favor selecciona un mes válido.';
    mensaje.classList.replace('d-none', 'alert-info');
    contenedorTabla.style.display = 'none';
    return;
  }

  fetch('api_promedios_mes.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ mes, selectorZonaMensual: zona })
  })
    .then(res => res.json())
    .then(data => {
      if (data.length === 0) {
        //Desactiva el selector de zona
        selectorMensual.disabled = true;
        mensaje.textContent = 'No hay registros de precios para el mes seleccionado.';
        mensaje.classList.replace('d-none', 'alert-info');
        contenedorTabla.style.display = 'none';
        return;
      }

      const tbody = tabla.querySelector('tbody');
      tbody.innerHTML = '';
      //Activa el selector de zona
      selectorMensual.disabled = false;

      // Inicializa totales
      let total = {
        vu_magna: 0, vu_premium: 0, vu_diesel: 0, promedio_general_estacion: 0,
        utilidad_magna: 0, utilidad_premium: 0, utilidad_diesel: 0, utilidad_promedio_litro: 0
      };
      let count = data.length;

      data.forEach(row => {
        // Suma totales
        total.vu_magna += parseFloat(row.vu_magna || 0);
        total.vu_premium += parseFloat(row.vu_premium || 0);
        total.vu_diesel += parseFloat(row.vu_diesel || 0);
        total.promedio_general_estacion += parseFloat(row.promedio_general_estacion || 0);
        total.utilidad_magna += parseFloat(row.utilidad_magna || 0);
        total.utilidad_premium += parseFloat(row.utilidad_premium || 0);
        total.utilidad_diesel += parseFloat(row.utilidad_diesel || 0);
        total.utilidad_promedio_litro += parseFloat(row.utilidad_promedio_litro || 0);

        // Inserta fila de datos
        tbody.innerHTML += `
          <tr>
            <td>${row.siic_inteligas ?? ''}</td>
            <td>${row.zona_original ?? ''}</td>
            <td>${row.estacion ?? ''}</td>
            <td>${row.vu_magna !== null ? Number(row.vu_magna).toFixed(2) + '%' : '-'}</td>
            <td>${row.vu_premium !== null ? Number(row.vu_premium).toFixed(2) + '%' : '-'}</td>
            <td>${row.vu_diesel !== null ? Number(row.vu_diesel).toFixed(2) + '%' : '-'}</td>
            <td>${row.promedio_general_estacion !== null ? Number(row.promedio_general_estacion).toFixed(2) + '%' : '-'}</td>
            <td>${row.utilidad_magna !== null ? '$' + Number(row.utilidad_magna).toFixed(2) : '-'}</td>
            <td>${row.utilidad_premium !== null ? '$' + Number(row.utilidad_premium).toFixed(2) : '-'}</td>
            <td>${row.utilidad_diesel !== null ? '$' + Number(row.utilidad_diesel).toFixed(2) : '-'}</td>
            <td>${row.utilidad_promedio_litro !== null ? '$' + Number(row.utilidad_promedio_litro).toFixed(2) : '-'}</td>
          </tr>
        `;
      });

      // Agrega fila de promedios
      tbody.innerHTML += `
        <tr style="font-weight: bold; background-color: #f2f2f2;">
          <td colspan="3">Promedio</td>
          <td>${(total.vu_magna / count).toFixed(2)}%</td>
          <td>${(total.vu_premium / count).toFixed(2)}%</td>
          <td>${(total.vu_diesel / count).toFixed(2)}%</td>
          <td>${(total.promedio_general_estacion / count).toFixed(2)}%</td>
          <td>$${(total.utilidad_magna / count).toFixed(2)}</td>
          <td>$${(total.utilidad_premium / count).toFixed(2)}</td>
          <td>$${(total.utilidad_diesel / count).toFixed(2)}</td>
          <td>$${(total.utilidad_promedio_litro / count).toFixed(2)}</td>
        </tr>
      `;

      mensaje.classList.add('d-none');
      contenedorTabla.style.display = 'block';
    })
    .catch(err => {
      console.error(err);
      mensaje.textContent = 'Error al cargar los datos.';
      mensaje.classList.replace('d-none', 'alert-danger');
      contenedorTabla.style.display = 'none';
    });
}


//Exportar excel mensual
function exportarExcelMensual() {
  const mes = document.getElementById('mes').value;
  const btn = document.getElementById('btnExportar');

  if (!mes) {
    // alert('Selecciona un mes para exportar.');
    document.getElementById('resultado_verificar_registros_mes').innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Selecciona un mes para exportar.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
            return;
    return;
  }

  btn.disabled = true;
  btn.innerHTML = 'Generando...';

  // La variable 'mes' tiene formato "YYYY-MM", convertimos a rango inicio-fin
  const inicio = mes + '-01';
  // Obtener último día del mes seleccionado
  const [year, month] = mes.split('-');
  const ultimoDia = new Date(year, month, 0).getDate(); // 0 da el último día del mes anterior
  const fin = `${year}-${month}-${ultimoDia}`;

  fetch(`exportar_mensual.php?inicio=${encodeURIComponent(inicio)}&fin=${encodeURIComponent(fin)}`)
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
      a.download = `resumen_mensual_${mes}.xlsx`;
      document.body.appendChild(a);
      a.click();
      a.remove();
    })
    .catch(error => {
      // alert(error.message || 'No hay datos para exportar o ocurrió un error.');
      document.getElementById('resultado_verificar_registros_mes').innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                No hay datos para exportar o ocurrió un error.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
            return;
    })
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = 'Exportar Excel';
    });
}

// Ejecutar automáticamente al volver al tab mensual si ya hay un mes seleccionado
// document.querySelector('a[href="#mensual"]').addEventListener('shown.bs.tab', function () {
//   const mes = document.getElementById('mes').value;
//   if (mes) {
//     cargarPromedios();
//     alert("Promedios actualizados");
//   }
// });

// let yaConsultado = false; // Bandera: indica si el usuario ya consultó

  document.addEventListener('DOMContentLoaded', function () {
    const tabMensual = document.querySelector('#mensual-tab');
    const inputMes = document.getElementById('mes');

    //Cargar zonas mensual
    cargarZonasMensual();
    // const btnConsultar = document.querySelector('button[onclick="cargarPromedios()"]');

    // 1. Cuando el usuario entra al tab mensual se actualizan de nuevo los valores por si se realizo modificaciones en el apartado diario
    tabMensual.addEventListener('shown.bs.tab', function () {
      const mes = inputMes.value;
    //   if (mes && yaConsultado) {
        
        // alert("Promedios actualizados");
        cargarPromedios();
    //   }
    });

    // 2. Cuando el usuario presiona "Consultar"
    // btnConsultar.addEventListener('click', function () {
    //   yaConsultado = true;
    // });

    // 3. Cuando el usuario cambia el mes manualmente
    inputMes.addEventListener('change', function () {
    //   yaConsultado = false; // Volver a requerir "Consultar"
      
      //Reiniciar input selector de zona y después volver a cargar promedios
      selectorZonaMensual.selectedIndex = 0;
      cargarPromedios();
    });
  });

function cargarZonas() {
    const select = document.getElementById('selectorZona');
    if (!select) return; // No hacer nada si no existe

    fetch('api_zonas.php')
        .then(res => res.json())
        .then(zonas => {
            // Limpiar opciones actuales (menos la primera)
            select.options.length = 1;
            zonas.forEach(z => {
                const option = document.createElement('option');
                option.value = z;
                option.textContent = z;
                select.appendChild(option);
            });
        })
        .catch(err => console.error('Error al cargar zonas:', err));
}

function cargarZonasMensual() {
  fetch('api_zonas.php') // Este archivo debe devolver zonas distintas
    .then(res => res.json())
    .then(zonas => {
      const select = document.getElementById('selectorZonaMensual');
      zonas.forEach(z => {
        const opt = document.createElement('option');
        opt.value = z;
        opt.textContent = z;
        select.appendChild(opt);
      });
    });
}


// Función para filtrar tabla y mostrar promedios diarios
function filtrarPorZona() {
  const zonaSeleccionada = document.getElementById('selectorZona').value.toLowerCase();
  const filas = document.querySelectorAll('#tablaPrecios tbody tr');

  let suma = {
    vu_magna: 0,
    vu_premium: 0,
    vu_diesel: 0,
    costo_flete: 0,
    pf_magna: 0,
    pf_premium: 0,
    pf_diesel: 0,
    precio_magna: 0,
    precio_premium: 0,
    precio_diesel: 0,
    porcentaje_utilidad_magna: 0,
    porcentaje_utilidad_premium: 0,
    porcentaje_utilidad_diesel: 0,
    utilidad_litro_magna: 0,
    utilidad_litro_premium: 0,
    utilidad_litro_diesel: 0
  };

  let cuenta = 0;

  filas.forEach(fila => {
    const zonaAgrupada = (fila.getAttribute('data-zona-agrupada') || '').toLowerCase();
    const mostrar = !zonaSeleccionada || zonaAgrupada === zonaSeleccionada;

    fila.style.display = mostrar ? '' : 'none';

    if (mostrar) {
      const celdas = fila.querySelectorAll('td');

      // Usa los índices correctos según tu tabla
      suma.vu_magna       += parseFloat(celdas[4].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.vu_premium     += parseFloat(celdas[5].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.vu_diesel      += parseFloat(celdas[6].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.costo_flete    += parseFloat(celdas[7].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.pf_magna       += parseFloat(celdas[8].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.pf_premium     += parseFloat(celdas[9].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.pf_diesel      += parseFloat(celdas[10].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.precio_magna   += parseFloat(celdas[11].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.precio_premium += parseFloat(celdas[12].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.precio_diesel  += parseFloat(celdas[13].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.porcentaje_utilidad_magna   += parseFloat(celdas[14].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.porcentaje_utilidad_premium += parseFloat(celdas[15].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.porcentaje_utilidad_diesel  += parseFloat(celdas[16].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.utilidad_litro_magna        += parseFloat(celdas[17].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.utilidad_litro_premium      += parseFloat(celdas[18].textContent.replace(/[^0-9.-]+/g, "")) || 0;
      suma.utilidad_litro_diesel       += parseFloat(celdas[19].textContent.replace(/[^0-9.-]+/g, "")) || 0;

      cuenta++;
    }
  });

  const tbody = document.querySelector('#tablaPrecios tbody');

  // Elimina fila promedio previa si existe
  const filasExistentes = tbody.querySelectorAll('tr');
  if (filasExistentes.length > 0 && filasExistentes[filasExistentes.length - 1].textContent.includes('Promedios')) {
    tbody.removeChild(filasExistentes[filasExistentes.length - 1]);
  }

  // Agrega fila promedio nueva
  const filaPromedio = document.createElement('tr');
  filaPromedio.innerHTML = `
    <td colspan="4"><strong>Promedios</strong></td>
    <td><strong>$${(suma.vu_magna / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.vu_premium / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.vu_diesel / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.costo_flete / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.pf_magna / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.pf_premium / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.pf_diesel / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.precio_magna / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.precio_premium / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.precio_diesel / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>${(suma.porcentaje_utilidad_magna / cuenta || 0).toFixed(2)}%</strong></td>
    <td><strong>${(suma.porcentaje_utilidad_premium / cuenta || 0).toFixed(2)}%</strong></td>
    <td><strong>${(suma.porcentaje_utilidad_diesel / cuenta || 0).toFixed(2)}%</strong></td>
    <td><strong>$${(suma.utilidad_litro_magna / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.utilidad_litro_premium / cuenta || 0).toFixed(2)}</strong></td>
    <td><strong>$${(suma.utilidad_litro_diesel / cuenta || 0).toFixed(2)}</strong></td>

  `;
  tbody.appendChild(filaPromedio);
}

//Metodo para exportar la tabla completa del día seleccionado en el selector de fecha
// document.getElementById('btnExportarExcelDia').addEventListener('click', function () {
//     const fecha = document.getElementById('fecha').value;
//     if (!fecha) {
//         alert('Por favor, selecciona una fecha.');
//         return;
//     }
    
//     // Verifica si la tabla tiene datos
//     const tablaWrapper = document.querySelector('#tablaPrecios #tablaWrapper');
//     const hayDatos = tablaWrapper?.dataset?.hayDatos === '1';

//     if (!hayDatos) {
//         document.getElementById('mensajeImportacion').innerHTML = `
//         <div class="alert alert-warning alert-dismissible fade show" role="alert">
//           ⚠️ No hay registros disponibles para exportar en la fecha seleccionada.
//           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
//         </div>`;
//         return;
//     }

//     // Redirige al script que genera el Excel
//     window.location.href = `exportar_para_modificar_precio_venta.php?fecha=${encodeURIComponent(fecha)}`;
// });

//Metodo para exportar la tabla completa del día seleccionado en el selector de fecha usando fetch
document.getElementById('btnExportarExcelDia').addEventListener('click', function () {
    const fecha = document.getElementById('fecha').value;
    const btn = document.getElementById('btnExportarExcelDia');

    if (!fecha) {
        // alert('Por favor, selecciona una fecha.');
        document.getElementById('resultado_verificar_registros').innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Por favor, selecciona una fecha.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
            return;
    }

    const tablaWrapper = document.querySelector('#tablaPrecios #tablaWrapper');
    const hayDatos = tablaWrapper?.dataset?.hayDatos === '1';

    if (!hayDatos) {
        document.getElementById('mensajeImportacion').innerHTML = `
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          ⚠️ No hay registros disponibles para exportar en la fecha seleccionada.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>`;
        return;
    }

    // Validar si hay XML antes de exportar
    fetch(`exportar_para_modificar_precio_venta.php?validar=1&fecha=${encodeURIComponent(fecha)}`)
        .then(response => response.json())
        .then(data => {
            if (!data.hayXml) {
                if (!confirm("⚠️ Aún no se ha cargado ningún XML. ¿Está seguro de realizar la exportación?")) {
                    return; // Canceló
                }
            }

            // Continuar con la exportación
            btn.disabled = true;
            btn.innerHTML = 'Generando...';

            fetch(`exportar_para_modificar_precio_venta.php?fecha=${encodeURIComponent(fecha)}`)
                .then(response => {
                    if (response.headers.get('Content-Type').includes('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
                        return response.blob();
                    } else {
                        return response.text().then(text => { throw new Error(text); });
                    }
                })
                .then(blob => {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `precios_${fecha}.xlsx`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    URL.revokeObjectURL(url);
                })
                .catch(error => {
                    document.getElementById('mensajeImportacion').innerHTML = `
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            ${error.message || 'No se pudo generar el archivo.'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>`;
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = 'Exportar Excel';
                });
        });
});


//Importar excel
// document.getElementById('formImportarExcel').addEventListener('submit', function (e) {
//     e.preventDefault(); // Evita el envío normal

//     const form = e.target;
//     const formData = new FormData(form);
//     // const mensaje = document.getElementById('mensajeImportacion');
//     // mensaje.textContent = 'Procesando...';

//     fetch('importar_precios_venta.php', {
//         method: 'POST',
//         body: formData
//     })
//     .then(resp => resp.json())
//     .then(data => {
//         if (data.success) {
//             if (data.actualizados > 0) {
//               // mensaje.textContent = `✅ Se actualizaron ${data.actualizados} registros.`;
//               document.getElementById('mensajeImportacion').innerHTML =
//                 `<div class="alert alert-warning alert-dismissible fade show" role="alert">
//                     ✅ Se actualizaron ${data.actualizados} registros.
//                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
//                 </div>`;
//             } else {
//               // mensaje.textContent = `⚠️ El archivo fue procesado pero no se actualizó ningún registro.`;
//               document.getElementById('mensajeImportacion').innerHTML =
//                 `<div class="alert alert-warning alert-dismissible fade show" role="alert">
//                     ⚠️ El archivo fue procesado pero no se actualizó ningún registro.
//                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
//                 </div>`;
//             }
//             // mensaje.style.color = 'green';
//             // Obtener la fecha desde el input oculto
//             const fechaInput = document.getElementById('fecha');
//             const fecha = fechaInput ? fechaInput.value : null;
//             // Recargar tabla automáticamente con la fecha (y zona si quieres)
//             cargarTablaPrecios(fecha).then(() => {
//             // zonaInput.disabled = false;
//             // zonaInput.style.display = 'inline-block';
//             cargarZonas();
//             filtrarPorZona();
//         });

//         form.reset(); // Limpia el formulario después de importar y recargar

//         } else {
//             // mensaje.textContent = `❌ Error: ${data.error}`;
//             // mensaje.style.color = 'red';
//         }
//     })
//     .catch(error => {
//         console.error('Error:', error);
//         // mensaje.textContent = '❌ Error al subir el archivo.';
//         // mensaje.style.color = 'red';
//     });
// });

//   document.getElementById('formImportarExcel').addEventListener('submit', function (e) {
//     e.preventDefault(); // Evita el envío normal

//     const form = e.target;
//     const formData = new FormData(form);
//     const mensaje = document.getElementById('mensajeImportacion');

//     // Mensaje temporal de carga
//     mensaje.innerHTML = `
//         <div class="alert alert-info alert-dismissible fade show" role="alert">
//             ⏳ Procesando archivo...
//             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
//         </div>
//     `;

//     fetch('importar_precios_venta.php', {
//         method: 'POST',
//         body: formData
//     })
//     .then(resp => resp.json())
//     .then(data => {
//         if (data.success) {
//             if (data.actualizados > 0) {
//                 mensaje.innerHTML = `
//                     <div class="alert alert-success alert-dismissible fade show" role="alert">
//                         ✅ Se actualizaron ${data.actualizados} registros.
//                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
//                     </div>
//                 `;
//             } else {
//                 mensaje.innerHTML = `
//                     <div class="alert alert-warning alert-dismissible fade show" role="alert">
//                         ⚠️ El archivo fue procesado pero no se actualizó ningún registro.
//                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
//                     </div>
//                 `;
//             }

//             const fechaInput = document.getElementById('fecha');
//             const fecha = fechaInput ? fechaInput.value : null;

//             cargarTablaPrecios(fecha).then(() => {
//                 cargarZonas();
//                 filtrarPorZona();
//             });

//             form.reset(); // Limpiar formulario
//         } else {
//             mensaje.innerHTML = `
//                 <div class="alert alert-danger alert-dismissible fade show" role="alert">
//                     ❌ Error: ${data.error}
//                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
//                 </div>
//             `;
//         }
//     })
//     .catch(error => {
//         console.error('Error:', error);
//         mensaje.innerHTML = `
//             <div class="alert alert-danger alert-dismissible fade show" role="alert">
//                 ❌ Error al subir el archivo. Intenta nuevamente.
//                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
//             </div>
//         `;
//     });
// });

//Importar excel diario
document.querySelector('input[name="archivo_excel"]').addEventListener('change', function () {

    //Validar que no se importe si la fecha no tiene registros
    const tablaWrapper = document.querySelector('#tablaPrecios #tablaWrapper');
    const hayDatos = tablaWrapper?.dataset?.hayDatos === '1';
    if (!hayDatos) {
        const mensajeContenedor = document.getElementById('mensajeImportacion');
        mensajeContenedor.innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                ⚠️ No puedes importar porque no hay registros para la fecha seleccionada.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
        // Limpiar el input
        this.value = '';
        return;
    }

    const form = document.getElementById('formImportarExcel');
    const formData = new FormData(form);
    const mensajeContenedor = document.getElementById('mensajeImportacion');

    mensajeContenedor.innerHTML = `<div class="alert alert-info" role="alert">⏳ Procesando archivo...</div>`;

    fetch('importar_precios_venta.php', {
        method: 'POST',
        body: formData
    })
    .then(resp => resp.json())
    .then(data => {
        if (data.success) {
            if (data.actualizados > 0) {
                mensajeContenedor.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ✅ Se actualizaron ${data.actualizados} registros.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>`;
            } else {
                mensajeContenedor.innerHTML = `
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        ⚠️ El archivo fue procesado pero no se actualizó ningún registro.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>`;
            }

            const fecha = document.getElementById('fecha').value || null;
            cargarTablaPrecios(fecha).then(() => {
                
                filtrarPorZona();
                // cargarZonas();
            });

            // Limpiar input
            form.reset();
        } else {
            mensajeContenedor.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ❌ Error: ${data.error}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>`;
                // Limpiar input
                form.reset();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mensajeContenedor.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ❌ Error al subir el archivo.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
    });
});

//Sincronizar valor de la fecha del excel con el input(importar_precios_venta.php actualmente no usa $_POST['fecha'], porque la fecha se toma desde el Excel (columna A), así que puedes eliminar el input hidden y el código JS de sincronización si quieres.
//Pero si en el futuro quieres validar que el Excel corresponde a la fecha seleccionada en el sistema, entonces sí deberías conservarlo.)
document.getElementById('fecha').addEventListener('change', function () {
    document.getElementById('fecha_excel_hidden').value = this.value;
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
    <script src="./mouse.js"></script>
</body>
</html>
