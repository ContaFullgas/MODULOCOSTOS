-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 31-05-2025 a las 17:45:39
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
  `rfc_receptor` varchar(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estaciones`
--

INSERT INTO `estaciones` (`id`, `nombre`, `rfc_receptor`) VALUES
(1, 'COLOSIO', 'SEB151218B36'),
(2, 'EL JUAREZ', 'SEB151218B36'),
(3, 'IMI', 'SEB151218B36'),
(4, 'PGJ', 'SEB151218B36'),
(5, 'VILLACABRA', 'SEB151218B36'),
(6, 'CHAN YAXCHE', 'SEC150112537'),
(7, 'CHEMAX', 'SEC150112537'),
(8, 'CRUCERO', 'SEC150112537'),
(9, 'EBTÚN', 'SEC150112537'),
(10, 'EL IDEAL', 'SEC150112537'),
(11, 'ISLA AGUADA', 'SEC150112537'),
(12, 'PEDRO SANTOS', 'SEC150112537'),
(13, 'SACIABIL', 'SEC150112537'),
(14, 'ZACI', 'SEC150112537'),
(15, 'JOSÉ MARÍA MORELOS', 'SEC20082165A'),
(16, 'SEYBAPLAYA', 'SEC20082165A'),
(17, 'CHAMP MALECÓN', 'SEC141031S67'),
(18, 'HECELCHAKÁN', 'SEC141031S67'),
(19, 'KANASÍN 58', 'CACX7605101P8'),
(20, 'PISTÉ', 'CACX7605101P8'),
(21, 'TULUM COBA/RITO', 'CACX7605101P8'),
(22, 'TAXISTAS CUN', 'SEM1512187Y9'),
(23, 'LOPEZ PORTILLO', 'SEM1512187Y9'),
(24, 'TEJUPILCO', 'SEM1512187Y9'),
(25, 'ESC. LEMUS', 'SEM1410318Q5'),
(26, 'TENABO', 'SEM1410318Q5'),
(27, 'ABROJALITO', 'SES150112RC3'),
(28, 'REAL VERONA', 'SES150112RC3'),
(29, 'CELAYA 2', 'CSB971203H46'),
(30, 'SAN PEDRO', 'CARJ521227GH5'),
(31, 'CALKINI CENTRO', 'SEM141031V5A'),
(32, 'ESC ALARCON', 'SEM141031V5A'),
(33, 'FELIPE CARRILLO', 'SEM141031V5A'),
(34, 'L PORTILLO 2', 'SEM141031V5A'),
(35, 'MOTUL', 'SEM141031V5A'),
(36, 'ROJO GOMEZ', 'SEM141031V5A'),
(37, 'SAN JUAN', 'SEM141031V5A'),
(38, 'VALLADOLID MERCADO', 'SEM141031V5A'),
(39, 'CHUBURNA', 'SEY0704139A8'),
(40, 'KOHUNLICH', 'SEY0704139A8'),
(41, 'UMAN', 'SEY0704139A8'),
(42, 'VALLADOLID', 'SEY0704139A8'),
(43, 'AGUASCALIENTES 1A', 'SES200507JU6'),
(44, 'SALAMANCA', 'SES200507JU6'),
(45, '20 DE NOV', 'SEC1503037FA'),
(46, 'CALKINI MURAL', 'SEC1503037FA'),
(47, 'CAUCEL', 'SEC1503037FA'),
(48, 'PALOMAR', 'SEC1503037FA'),
(49, 'RANCHO VIEJO', 'SEC1503037FA'),
(50, 'RUTA 5', 'SEC1503037FA'),
(51, 'EL CUYO', 'JGE900406818'),
(52, 'LA BARCA', 'SEE141031A1A'),
(53, 'PETO', 'SER150303GN5'),
(54, 'TZUCACAB', 'SER150303GN5'),
(55, 'RAMOS ARIZPE', 'SEC141031B4A'),
(56, 'REAL GRANADA', 'AET1404031U2'),
(57, 'SIERRA HERMOSA', 'AET1404031U2'),
(58, 'TECAMAC MP', 'AET1404031U2'),
(59, 'COLONIA YUC', 'CGM130531NS2'),
(60, 'PREMIER', 'AOD170302LQ7'),
(61, 'RIO NUEVO', 'AOD170302LQ7'),
(62, 'TRAILERO', 'AOD170302LQ7'),
(63, 'GAS MAYA', 'SEC150204U97'),
(64, 'CALIMAYA', 'SEC150204U97'),
(65, 'MAXUXAC 2', 'SEC150204U97'),
(66, 'MULSAY', 'SEC150204U97'),
(67, 'CHAM. REST.', 'SEI1410319R7'),
(68, 'CHAMP ECHEVERRIA', 'SEI1410319R7'),
(69, 'HOPELCHEN', 'SEI1410319R7'),
(70, 'BUENA VISTA', 'SEM150204RK4'),
(71, 'PALMIRA', 'SEM150204RK4'),
(72, 'TULUM ZAMNA', 'SEM070413TA9'),
(73, 'ANDRES Q.ROO', 'SEM070413TA9'),
(74, 'BACALAR', 'SEM070413TA9'),
(75, 'HEROES', 'SEM070413TA9'),
(76, 'HUAYPIX', 'SEM070413TA9'),
(77, 'MAXUXAC', 'SEM070413TA9'),
(78, 'EXPOFERIA', 'SYU110901LR9'),
(79, 'KOPOMÁ', 'SYU110901LR9'),
(80, 'MUNA', 'SYU110901LR9'),
(81, 'TIZIMIN', 'SYU110901LR9'),
(82, 'PUEBLA CENTRO', 'SEP200807463'),
(83, 'TUXTLA 1STAM', 'SEP200807463');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios_combustible`
--

CREATE TABLE `precios_combustible` (
  `id` int(11) NOT NULL,
  `razon_social` varchar(255) NOT NULL,
  `estacion` varchar(255) NOT NULL,
  `fecha` date DEFAULT NULL,
  `diesel` decimal(10,2) DEFAULT NULL,
  `magna` decimal(10,2) DEFAULT NULL,
  `premium` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `precios_combustible`
--

INSERT INTO `precios_combustible` (`id`, `razon_social`, `estacion`, `fecha`, `diesel`, `magna`, `premium`) VALUES
(65, 'ALIANZA EMPRESARIAL DE TECAMAC', 'SIERRA HERMOSA', '2025-05-15', NULL, 21.41, NULL),
(66, 'ALIANZA EMPRESARIAL DE TECAMAC', 'TECAMAC MP', '2025-05-14', NULL, 21.41, NULL),
(67, 'ALIANZA EMPRESARIAL DE TECAMAC', 'SIERRA HERMOSA', '2025-05-14', 22.32, NULL, NULL),
(68, 'ALIANZA EMPRESARIAL DE TECAMAC', 'TECAMAC MP', '2025-05-20', NULL, 21.41, NULL),
(69, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-03-07', NULL, 20.30, NULL),
(70, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-02-10', NULL, 20.40, 21.80),
(71, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-04-23', NULL, 22.35, NULL);

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
(81, '1d4f371b-6d1c-4a67-9bcb-148c9c3ebbf1', 65),
(82, '7b585033-47bf-4b26-9990-269b9e9e8fc1', 66),
(83, '5ecde881-306f-4a54-866c-6284a545d0d7', 67),
(84, 'b5a69a84-3535-4596-b47e-d1f46055739e', 68),
(85, '3E7B22FB-D987-42B3-8832-7327591BC58D', 69),
(86, 'E47A58FE-AFD9-4F0A-8EA2-C6819405702D', 69),
(87, '7F0FFE88-6AA4-4B0C-B926-63601329F922', 70),
(88, '0176A895-81ED-4F46-B35A-2DF907073FFD', 70),
(89, 'DC06B84A-2417-4F9A-800A-FBDB49CAF66A', 70),
(90, '12b5b684-b450-42aa-9bec-7494f352d641', 71);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estaciones`
--
ALTER TABLE `estaciones`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de la tabla `precios_combustible`
--
ALTER TABLE `precios_combustible`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `precios_uuid`
--
ALTER TABLE `precios_uuid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

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
