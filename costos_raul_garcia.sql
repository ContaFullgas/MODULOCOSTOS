-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 02-06-2025 a las 22:07:52
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `costos_raul_garcia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estaciones`
--

CREATE TABLE `estaciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `rfc_receptor` varchar(13) DEFAULT NULL,
  `iva` decimal(5,2) DEFAULT 0.16
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estaciones`
--

INSERT INTO `estaciones` (`id`, `nombre`, `rfc_receptor`, `iva`) VALUES
(1, 'COLOSIO', 'SEB151218B36', 0.16),
(2, 'EL JUAREZ', 'SEB151218B36', 0.16),
(3, 'IMI', 'SEB151218B36', 0.16),
(4, 'PGJ', 'SEB151218B36', 0.16),
(5, 'VILLACABRA', 'SEB151218B36', 0.16),
(6, 'CHAN YAXCHE', 'SEC150112537', 0.16),
(7, 'CHEMAX', 'SEC150112537', 0.16),
(8, 'CRUCERO', 'SEC150112537', 0.16),
(9, 'EBTÚN', 'SEC150112537', 0.16),
(10, 'EL IDEAL', 'SEC150112537', 0.16),
(11, 'ISLA AGUADA', 'SEC150112537', 0.16),
(12, 'PEDRO SANTOS', 'SEC150112537', 0.16),
(13, 'SACIABIL', 'SEC150112537', 0.16),
(14, 'ZACI', 'SEC150112537', 0.16),
(15, 'JOSÉ MARÍA MORELOS', 'SEC20082165A', 0.16),
(16, 'SEYBAPLAYA', 'SEC20082165A', 0.16),
(17, 'CHAMP MALECÓN', 'SEC141031S67', 0.16),
(18, 'HECELCHAKÁN', 'SEC141031S67', 0.16),
(19, 'KANASÍN 58', 'CACX7605101P8', 0.16),
(20, 'PISTÉ', 'CACX7605101P8', 0.16),
(21, 'TULUM COBA/RITO', 'CACX7605101P8', 0.16),
(22, 'TAXISTAS CUN', 'SEM1512187Y9', 0.16),
(23, 'LOPEZ PORTILLO', 'SEM1512187Y9', 0.16),
(24, 'TEJUPILCO', 'SEM1512187Y9', 0.16),
(25, 'ESC. LEMUS', 'SEM1410318Q5', 0.16),
(26, 'TENABO', 'SEM1410318Q5', 0.16),
(27, 'ABROJALITO', 'SES150112RC3', 0.16),
(28, 'REAL VERONA', 'SES150112RC3', 0.16),
(29, 'CELAYA 2', 'CSB971203H46', 0.16),
(30, 'SAN PEDRO', 'CARJ521227GH5', 0.16),
(31, 'CALKINI CENTRO', 'SEM141031V5A', 0.16),
(32, 'ESC ALARCON', 'SEM141031V5A', 0.16),
(33, 'FELIPE CARRILLO', 'SEM141031V5A', 0.16),
(34, 'L PORTILLO 2', 'SEM141031V5A', 0.16),
(35, 'MOTUL', 'SEM141031V5A', 0.16),
(36, 'ROJO GOMEZ', 'SEM141031V5A', 0.08),
(37, 'SAN JUAN', 'SEM141031V5A', 0.16),
(38, 'VALLADOLID MERCADO', 'SEM141031V5A', 0.16),
(39, 'CHUBURNA', 'SEY0704139A8', 0.16),
(40, 'KOHUNLICH', 'SEY0704139A8', 0.08),
(41, 'UMAN', 'SEY0704139A8', 0.16),
(42, 'VALLADOLID', 'SEY0704139A8', 0.16),
(43, 'AGUASCALIENTES 1A', 'SES200507JU6', 0.16),
(44, 'SALAMANCA', 'SES200507JU6', 0.16),
(45, '20 DE NOV', 'SEC1503037FA', 0.16),
(46, 'CALKINI MURAL', 'SEC1503037FA', 0.16),
(47, 'CAUCEL', 'SEC1503037FA', 0.16),
(48, 'PALOMAR', 'SEC1503037FA', 0.16),
(49, 'RANCHO VIEJO', 'SEC1503037FA', 0.16),
(50, 'RUTA 5', 'SEC1503037FA', 0.16),
(51, 'EL CUYO', 'JGE900406818', 0.16),
(52, 'LA BARCA', 'SEE141031A1A', 0.16),
(53, 'PETO', 'SER150303GN5', 0.16),
(54, 'TZUCACAB', 'SER150303GN5', 0.16),
(55, 'RAMOS ARIZPE', 'SEC141031B4A', 0.16),
(56, 'REAL GRANADA', 'AET1404031U2', 0.16),
(57, 'SIERRA HERMOSA', 'AET1404031U2', 0.16),
(58, 'TECAMAC MP', 'AET1404031U2', 0.16),
(59, 'COLONIA YUC', 'CGM130531NS2', 0.16),
(60, 'PREMIER', 'AOD170302LQ7', 0.16),
(61, 'RIO NUEVO', 'AOD170302LQ7', 0.16),
(62, 'TRAILERO', 'AOD170302LQ7', 0.16),
(63, 'GAS MAYA', 'SEC150204U97', 0.16),
(64, 'CALIMAYA', 'SEC150204U97', 0.16),
(65, 'MAXUXAC 2', 'SEC150204U97', 0.08),
(66, 'MULSAY', 'SEC150204U97', 0.16),
(67, 'CHAM. REST.', 'SEI1410319R7', 0.16),
(68, 'CHAMP ECHEVERRIA', 'SEI1410319R7', 0.16),
(69, 'HOPELCHEN', 'SEI1410319R7', 0.16),
(70, 'BUENA VISTA', 'SEM150204RK4', 0.16),
(71, 'PALMIRA', 'SEM150204RK4', 0.16),
(72, 'TULUM ZAMNA', 'SEM070413TA9', 0.16),
(73, 'ANDRES Q.ROO', 'SEM070413TA9', 0.08),
(74, 'BACALAR', 'SEM070413TA9', 0.16),
(75, 'HEROES', 'SEM070413TA9', 0.08),
(76, 'HUAYPIX', 'SEM070413TA9', 0.08),
(77, 'MAXUXAC', 'SEM070413TA9', 0.08),
(78, 'EXPOFERIA', 'SYU110901LR9', 0.16),
(79, 'KOPOMÁ', 'SYU110901LR9', 0.16),
(80, 'MUNA', 'SYU110901LR9', 0.16),
(81, 'TIZIMIN', 'SYU110901LR9', 0.16),
(82, 'PUEBLA CENTRO', 'SEP200807463', 0.16),
(83, 'TUXTLA 1STAM', 'SEP200807463', 0.16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ieps`
--

CREATE TABLE `ieps` (
  `id` int(11) NOT NULL,
  `tipo_combustible` varchar(20) NOT NULL,
  `valor` decimal(6,4) NOT NULL,
  `anio` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ieps`
--

INSERT INTO `ieps` (`id`, `tipo_combustible`, `valor`, `anio`) VALUES
(1, 'Magna', 0.5697, '2025'),
(2, 'Premium', 0.6952, '2025'),
(3, 'Diesel', 0.4728, '2025');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios_combustible`
--

CREATE TABLE `precios_combustible` (
  `id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `siic` varchar(20) DEFAULT NULL,
  `zona` varchar(100) DEFAULT NULL,
  `razon_social` varchar(255) NOT NULL,
  `estacion` varchar(255) NOT NULL,
  `vu_magna` decimal(10,2) DEFAULT NULL,
  `vu_premium` decimal(10,2) DEFAULT NULL,
  `vu_diesel` decimal(10,2) DEFAULT NULL,
  `costo_flete` decimal(10,2) DEFAULT NULL,
  `pf_magna` decimal(10,2) DEFAULT NULL,
  `pf_premium` decimal(10,2) DEFAULT NULL,
  `pf_diesel` decimal(10,2) DEFAULT NULL,
  `precio_magna` decimal(10,2) DEFAULT NULL,
  `precio_premium` decimal(10,2) DEFAULT NULL,
  `precio_diesel` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `precios_combustible`
--

INSERT INTO `precios_combustible` (`id`, `fecha`, `siic`, `zona`, `razon_social`, `estacion`, `vu_magna`, `vu_premium`, `vu_diesel`, `costo_flete`, `pf_magna`, `pf_premium`, `pf_diesel`, `precio_magna`, `precio_premium`, `precio_diesel`) VALUES
(72, '2025-06-02', '117311', 'CAMPECHE', 'RAZÓN SOCIAL DE PRUEBA', 'PALMIRA', 21.82, 22.86, 24.01, 0.25, 21.07, 22.11, 23.26, 23.99, 25.99, 25.99),
(78, '2025-04-23', NULL, NULL, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', 22.35, NULL, NULL, 0.25, 22.60, NULL, NULL, 26.79, NULL, NULL),
(79, '2025-04-28', NULL, NULL, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', 22.01, NULL, NULL, 0.25, 22.26, NULL, NULL, 26.39, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios_uuid`
--

CREATE TABLE `precios_uuid` (
  `id` int(11) NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `precio_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `precios_uuid`
--

INSERT INTO `precios_uuid` (`id`, `uuid`, `precio_id`) VALUES
(102, '12b5b684-b450-42aa-9bec-7494f352d641', 78),
(103, '807b06b6-5f74-4ea9-93cd-9e1abc600417', 79);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estaciones`
--
ALTER TABLE `estaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ieps`
--
ALTER TABLE `ieps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo_combustible` (`tipo_combustible`);

--
-- Indices de la tabla `precios_combustible`
--
ALTER TABLE `precios_combustible`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `precios_uuid`
--
ALTER TABLE `precios_uuid`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `precio_id` (`precio_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estaciones`
--
ALTER TABLE `estaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de la tabla `ieps`
--
ALTER TABLE `ieps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `precios_combustible`
--
ALTER TABLE `precios_combustible`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `precios_uuid`
--
ALTER TABLE `precios_uuid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `precios_uuid`
--
ALTER TABLE `precios_uuid`
  ADD CONSTRAINT `precios_uuid_ibfk_1` FOREIGN KEY (`precio_id`) REFERENCES `precios_combustible` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
