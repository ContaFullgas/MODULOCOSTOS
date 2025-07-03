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
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="container-fluid py-4" style="background-color: #3a3d41; width: 90%;">
  <header class="p-2 text-bg-white fixed-top" style="background-color: #3B1C32; z-index: 1030; max-height: 80px;">
    <div class="lighting-effect"></div>
    <div class="container-fluid ">
      <div class="row align-items-center">
        <!-- Logo - columna izquierda -->
        <div class="col-4 col-md-2">
          <img class="img-fluid p-1" src="logo/image004.png" alt="logoFullgas" style="max-height: 50px;">
        </div>

        <!-- Título - columna central -->
        <div class="col-4 col-md-8 d-flex align-items-center justify-content-center">
          <div class="verificador fw-bold mb-0 fs-5 fs-md-4 fs-lg-3"
            style="color: white; font-family: 'Oswald', sans-serif;">
            PRECIOS COMBUSTIBLES FULLGAS
          </div>
        </div>

        <!-- Espacio vacío para alinear -->
        <div class="col-4 col-md-2"></div>
      </div>
    </div>   
</header>

<!-- Contenedor principal con padding superior para el header fijo -->
  <main class="container-fluid mt-5 pt-4 pb-5"> <!-- Añadido mt-5 y pt-4 para espacio del header fijo -->

<ul class="nav nav-tabs" id="myTabs" role="tablist">
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
  <h3 style="color: white; font-family: 'Oswald', sans-serif;">Subir Facturas (XML)</h3>

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
    <h3 style="color: white; font-family: 'Oswald', sans-serif;">Consulta Mensual de Promedios</h3>
    <br>
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
                <th class="RazonSocial border border-white rounded-4  text-center align-middle" style="background-color: black; color: white;  padding: 10px; font-family: 'Oswald', sans-serif;" rowspan="2">SIIC</th>
                <th class="RazonSocial border border-white rounded-4  text-center align-middle" style="background-color: #4F1C51; color: white; ; padding: 10px; font-family: 'Oswald', sans-serif;" rowspan="2">ZONA</th>
                <th class="Estacion border border-white rounded-4 text-center align-middle" style="background-color: #A55B4B; color: white; padding: 10px; font-family: 'Oswald', sans-serif;" rowspan="2">ESTACIÓN</th>
                <th class="border border-white rounded-4" colspan="4" style="background-color: #261FB3; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PROMEDIO DE UTILIDAD</th>
                <th class="border border-white rounded-4" colspan="4" style="background-color: #261FB3; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PROMEDIO DE UTILIDAD POR LITRO</th>
            
                </tr>
                <tr>
                <th class="Magna border border-white rounded-4 text-center align-middle" style="background-color: #399918; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">MAGNA</th>
                <th class="Premium border border-white rounded-4 text-center align-middle" style="background-color: #FF0000; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PREMIUM</th>
                <th class="Diesel border border-white rounded-4 text-center align-middle" style="background-color: black; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">DIESEL</th>
                <th class="Estacion border border-white rounded-4 text-center align-middle" style="border-right-width: 8px; background-color: #DCA06D; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PROMEDIO GENERAL POR ESTACION</th>
                <th class="Magna border border-white rounded-4 text-center align-middle" style="background-color: #399918; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">MAGNA</th>
                <th class="Premium border border-white rounded-4 text-center align-middle" style="background-color: #FF0000; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">PREMIUM</th>
                <th class="Diesel border border-white rounded-4 text-center align-middle" style="background-color: black; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">DIESEL</th>
                <th class="Estacion border border-white rounded-4 text-center align-middle" style="border-right-width: 8px; background-color: #DCA06D; color: white; padding: 10px; font-family: 'Oswald', sans-serif;">UTILIDAD PROMEDIO</th>
                
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

</script>

    <!-- Modal -->
    <div class="modal fade" id="modalEstacion" tabindex="-1" aria-labelledby="modalEstacionLabel" aria-hidden="true">
      <div class="modal-dialog border border-white border-4 rounded-4">
        <div class="modal-content text-dark" style="background-color: #000000;">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEstacionLabel" style="color: white;">Seleccionar estación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body" style="background-color: #950101; color: white;"></div>

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
    <!-- Llamada al archivo con las funciones javascript -->
    <script src="./funciones.js"></script>

    <footer class="footer text-bg-white d-flex flex-column align-items-center"
      style="background-color: black; position: fixed; bottom: 0; left: 0; right: 0; padding: 5px 0;">
      <p class="position-absolute start-0 fw-bold p-3"
        style="font-family: sans-serif; font-size: 0.8rem; margin-top: 0; color: white;">DEPARTAMENTO
        CONTABILIDAD-SISTEMAS</p> <img class="footer-logo p-0 fs-1" src="logo/FG_GASOLINERAS-4.png"
        alt="logoFullgas">
    </footer>

</body>
</html>
