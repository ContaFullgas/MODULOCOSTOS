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
        selectorMensual.disabled = true;
        mensaje.textContent = 'No hay registros de precios para el mes seleccionado.';
        mensaje.classList.replace('d-none', 'alert-info');
        contenedorTabla.style.display = 'none';
        return;
      }

      selectorMensual.disabled = false;
      mensaje.classList.add('d-none');
      contenedorTabla.style.display = 'block';

      const tbody = tabla.querySelector('tbody');
      tbody.innerHTML = '';

      const zonaSeleccionada = zona.trim();
      const zonas = {};

      // Agrupar por zona si no hay filtro
      if (!zonaSeleccionada) {
        data.forEach(row => {
          const z = row.zona_agrupada ?? 'Sin Zona';
          if (!zonas[z]) zonas[z] = [];
          zonas[z].push(row);
        });
      } else {
        zonas[zonaSeleccionada] = data;
      }

      // Totales generales
      const total = {
        vu_magna: 0, vu_premium: 0, vu_diesel: 0, promedio_general_estacion: 0,
        utilidad_magna: 0, utilidad_premium: 0, utilidad_diesel: 0, utilidad_promedio_litro: 0
      };
      let count = 0;

      Object.entries(zonas).forEach(([nombreZona, registros]) => {
        // if (!zonaSeleccionada) {
          // Encabezado de zona
          const encabezadoZona = document.createElement('tr');
          encabezadoZona.innerHTML = `
            <td colspan="11" class="text-start fw-bold bg-secondary text-white" style="font-size: 1.1rem; font-family: 'Oswald', sans-serif;">
              ${nombreZona}
            </td>
          `;
          tbody.appendChild(encabezadoZona);
        // }

        registros.forEach(row => {
          // Sumar a totales generales
          total.vu_magna += parseFloat(row.vu_magna || 0);
          total.vu_premium += parseFloat(row.vu_premium || 0);
          total.vu_diesel += parseFloat(row.vu_diesel || 0);
          total.promedio_general_estacion += parseFloat(row.promedio_general_estacion || 0);
          total.utilidad_magna += parseFloat(row.utilidad_magna || 0);
          total.utilidad_premium += parseFloat(row.utilidad_premium || 0);
          total.utilidad_diesel += parseFloat(row.utilidad_diesel || 0);
          total.utilidad_promedio_litro += parseFloat(row.utilidad_promedio_litro || 0);
          count++;

          const fila = document.createElement('tr');
          fila.innerHTML = `
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
          `;
          tbody.appendChild(fila);
        });
      });

      // Fila de promedio general
      const filaPromedio = document.createElement('tr');
      filaPromedio.style = 'font-weight: bold; background-color: #f2f2f2;';
      filaPromedio.innerHTML = `
        <td colspan="3">Promedio</td>
        <td>${(total.vu_magna / count).toFixed(2)}%</td>
        <td>${(total.vu_premium / count).toFixed(2)}%</td>
        <td>${(total.vu_diesel / count).toFixed(2)}%</td>
        <td>${(total.promedio_general_estacion / count).toFixed(2)}%</td>
        <td>$${(total.utilidad_magna / count).toFixed(2)}</td>
        <td>$${(total.utilidad_premium / count).toFixed(2)}</td>
        <td>$${(total.utilidad_diesel / count).toFixed(2)}</td>
        <td>$${(total.utilidad_promedio_litro / count).toFixed(2)}</td>
      `;
      tbody.appendChild(filaPromedio);
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

  let zonaActual = '';
  let grupoVisible = false;
  let filasZona = [];

  filas.forEach(fila => {
    if (fila.classList.contains('table-group')) {
      // Evaluar grupo anterior
      if (zonaActual && !grupoVisible) {
        filasZona.forEach(f => f.style.display = 'none');
        if (zonaGrupoFila) zonaGrupoFila.style.display = 'none';
      }
      // Iniciar nuevo grupo
      zonaActual = fila.textContent.trim().toLowerCase();
      grupoVisible = false;
      filasZona = [];
      zonaGrupoFila = fila;
      fila.style.display = ''; // Mostrar por ahora, se decidirá al final
      return;
    }

    const zonaFila = (fila.getAttribute('data-zona-agrupada') || '').toLowerCase();
    const mostrar = !zonaSeleccionada || zonaFila === zonaSeleccionada;

    fila.style.display = mostrar ? '' : 'none';
    filasZona.push(fila);

    if (mostrar) {
      grupoVisible = true;
      const celdas = fila.querySelectorAll('td');
      if (celdas.length < 20) return;

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

  // Evaluar último grupo
  if (!grupoVisible && zonaGrupoFila) {
    filasZona.forEach(f => f.style.display = 'none');
    zonaGrupoFila.style.display = 'none';
  }

  // Quitar fila promedio previa
  const tbody = document.querySelector('#tablaPrecios tbody');
  const filasExistentes = tbody.querySelectorAll('tr');
  if (filasExistentes.length > 0 && filasExistentes[filasExistentes.length - 1].textContent.includes('Promedios')) {
    tbody.removeChild(filasExistentes[filasExistentes.length - 1]);
  }

  // Agregar nueva fila promedio si hay al menos una fila válida
  if (cuenta > 0) {
    const filaPromedio = document.createElement('tr');
    filaPromedio.innerHTML = `
      <td colspan="4"><strong>Promedios</strong></td>
      <td><strong>$${(suma.vu_magna / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.vu_premium / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.vu_diesel / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.costo_flete / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.pf_magna / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.pf_premium / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.pf_diesel / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.precio_magna / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.precio_premium / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.precio_diesel / cuenta).toFixed(2)}</strong></td>
      <td><strong>${(suma.porcentaje_utilidad_magna / cuenta).toFixed(2)}%</strong></td>
      <td><strong>${(suma.porcentaje_utilidad_premium / cuenta).toFixed(2)}%</strong></td>
      <td><strong>${(suma.porcentaje_utilidad_diesel / cuenta).toFixed(2)}%</strong></td>
      <td><strong>$${(suma.utilidad_litro_magna / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.utilidad_litro_premium / cuenta).toFixed(2)}</strong></td>
      <td><strong>$${(suma.utilidad_litro_diesel / cuenta).toFixed(2)}</strong></td>
    `;
    tbody.appendChild(filaPromedio);
  }
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

const lighting = document.querySelector('.lighting-effect');

document.addEventListener('mousemove', (e) => {
    // Calculamos las coordenadas del mouse
    const x = e.clientX;
    const y = e.clientY;

    // Actualizamos las variables CSS personalizadas
    lighting.style.setProperty('--x', x + 'px');
    lighting.style.setProperty('--y', y + 'px');
});
