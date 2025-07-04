-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 23-06-2025 a las 20:17:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

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
  `zona_agrupada` varchar(100) DEFAULT NULL,
  `rfc_receptor` varchar(13) DEFAULT NULL,
  `iva` decimal(5,2) DEFAULT 0.16
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estaciones`
--

INSERT INTO `estaciones` (`id`, `nombre`, `zona_agrupada`, `rfc_receptor`, `iva`) VALUES
(1, 'COLOSIO', 'Estatal Campeche', 'SEB151218B36', 0.16),
(2, 'JUÁREZ', 'Estatal Campeche', 'SEB151218B36', 0.16),
(3, 'IMÍ', 'Estatal Campeche', 'SEB151218B36', 0.16),
(4, 'PGJ', 'Estatal Campeche', 'SEB151218B36', 0.16),
(5, 'VILLACABRA', 'Estatal Campeche', 'SEB151218B36', 0.16),
(6, 'CHAN YAXCHE', NULL, 'SEC150112537', 0.16),
(7, 'CHEMAX', 'Estatal Yucatán', 'SEC150112537', 0.16),
(8, 'TULUM CRUCERO', 'Estatal Qroo', 'SEC150112537', 0.16),
(9, 'EBTÚN', 'Estatal Yucatán', 'SEC150112537', 0.16),
(10, 'IDEAL', 'Estatal Qroo', 'SEC150112537', 0.16),
(11, 'ISLA AGUADA', 'Estatal Campeche', 'SEC150112537', 0.16),
(12, 'PEDRO SANTOS', 'Estatal Qroo', 'SEC150112537', 0.16),
(13, 'SACIABIL', 'Estatal Yucatán', 'SEC150112537', 0.16),
(14, 'ZACÍ', 'Estatal Yucatán', 'SEC150112537', 0.16),
(15, 'MORELOS', 'Estatal Yucatán', 'SEC20082165A', 0.16),
(16, 'SEYBAPLAYA', 'Estatal Campeche', 'SEC20082165A', 0.16),
(17, 'CHAMPOTÓN  MALECÓN.', 'Estatal Campeche', 'SEC141031S67', 0.16),
(18, 'HECELCHAKÁN', 'Estatal Campeche', 'SEC141031S67', 0.16),
(19, 'KANASÍN 58', 'Estatal Yucatán', 'CACX7605101P8', 0.16),
(20, 'PISTÉ', 'Estatal Yucatán', 'CACX7605101P8', 0.16),
(21, 'TULUM COBA', 'Estatal Qroo', 'CACX7605101P8', 0.16),
(22, 'TAXISTAS CUN', NULL, 'SEM1512187Y9', 0.16),
(23, 'C. LÓPEZ PORTILLO', 'Estatal Qroo', 'SEM1512187Y9', 0.16),
(24, 'TEJUPILCO', NULL, 'SEM1512187Y9', 0.16),
(25, 'ESCARCEGA LEMUS', 'Estatal Campeche', 'SEM1410318Q5', 0.16),
(26, 'TENABO', 'Estatal Campeche', 'SEM1410318Q5', 0.16),
(27, 'ABROJALITO', 'Estatal Edo Mex', 'SES150112RC3', 0.16),
(28, 'REAL VERONA', 'Estatal Edo Mex', 'SES150112RC3', 0.16),
(29, 'CELAYA 2', NULL, 'CSB971203H46', 0.16),
(30, 'SAN PEDRO', NULL, 'CARJ521227GH5', 0.16),
(31, 'CALKINI CENTRO', 'Estatal Campeche', 'SEM141031V5A', 0.16),
(32, 'ESCARCEGA ALARCÓN', 'Estatal Campeche', 'SEM141031V5A', 0.16),
(33, 'CARRILLO PUERTO', 'Estatal Qroo', 'SEM141031V5A', 0.16),
(34, 'C. LÓPEZ PORTILLO 2', 'Estatal Qroo', 'SEM141031V5A', 0.16),
(35, 'MOTUL', 'Estatal Yucatán', 'SEM141031V5A', 0.16),
(36, 'ROJO GOMEZ', 'Estatal Qroo', 'SEM141031V5A', 0.08),
(37, 'SAN JUAN', 'Estatal Yucatán', 'SEM141031V5A', 0.16),
(38, 'MERCADO', 'Estatal Yucatán', 'SEM141031V5A', 0.16),
(39, 'CHUBURNÁ', 'Estatal Yucatán', 'SEY0704139A8', 0.16),
(40, 'KOHUNLICH', 'Estatal Qroo', 'SEY0704139A8', 0.08),
(41, 'UMÁN', 'Estatal Yucatán', 'SEY0704139A8', 0.16),
(42, 'VALLADOLID', 'Estatal Yucatán', 'SEY0704139A8', 0.16),
(43, 'AGUASCALIENTES', 'Estatal Aguascalientes', 'SEC141031B4A', 0.16),
(44, 'SALAMANCA', NULL, 'SEC141031B4A', 0.16),
(45, '20 DE NOVIEMBRE', 'Estatal Qroo', 'SEC1503037FA', 0.16),
(46, 'CALKINI 2', 'Estatal Campeche', 'SEC1503037FA', 0.16),
(47, 'CAUCEL', 'Estatal Yucatán', 'SEC1503037FA', 0.16),
(48, 'PALOMAR', NULL, 'SEC1503037FA', 0.16),
(49, 'RANCHO VIEJO', 'Estatal Qroo', 'SEC1503037FA', 0.16),
(50, 'RUTA 5', 'Estatal Qroo', 'SEC1503037FA', 0.16),
(51, 'EL CUYO', 'Estatal Yucatán', 'JGE900406818', 0.16),
(52, 'LA BARCA', NULL, 'SEE141031A1A', 0.16),
(53, 'PETO', 'Estatal Yucatán', 'SER150303GN5', 0.16),
(54, 'TZUCACAB', 'Estatal Yucatán', 'SER150303GN5', 0.16),
(55, 'RAMOS ARIZPE', 'Estatal Coahuila', 'SES200507JU6', 0.16),
(56, 'REAL GRANADA', 'Estatal Edo Mex', 'AET1404031U2', 0.16),
(57, 'SIERRA HERMOSA', 'Estatal Edo Mex', 'AET1404031U2', 0.16),
(58, 'TECAMÁC MP', 'Estatal Edo Mex', 'AET1404031U2', 0.16),
(59, 'COLONIA YUCATÁN', 'Estatal Yucatán', 'CGM130531NS2', 0.16),
(60, 'PREMIER', NULL, 'AOD170302LQ7', 0.16),
(61, 'RIO NUEVO', NULL, 'AOD170302LQ7', 0.16),
(62, 'TRAILERO', NULL, 'AOD170302LQ7', 0.16),
(63, 'MAYA', 'Estatal Yucatán', 'SEC150204U97', 0.16),
(64, 'CALIMAYA', 'Estatal Edo Mex', 'SEC150204U97', 0.16),
(65, 'MAXUXAC 2', 'Estatal Qroo', 'SEC150204U97', 0.08),
(66, 'MULSAY', 'Estatal Yucatán', 'SEC150204U97', 0.16),
(67, 'CHAMPOTÓN  RESTAURANTE', 'Estatal Campeche', 'SEI1410319R7', 0.16),
(68, 'CHAMPOTÓN  ECHEVERRIA', 'Estatal Campeche', 'SEI1410319R7', 0.16),
(69, 'HOPELCHÉN', 'Estatal Campeche', 'SEI1410319R7', 0.16),
(70, 'BUENAVISTA', 'Estatal Campeche', 'SEM150204RK4', 0.16),
(71, 'PALMIRA', 'Estatal Campeche', 'SEM150204RK4', 0.16),
(72, 'TULUM ZAMNA', 'Estatal Qroo', 'SEM070413TA9', 0.16),
(73, 'ANDRÉS QROO.', 'Estatal Qroo', 'SEM070413TA9', 0.08),
(74, 'BACALAR', 'Estatal Qroo', 'SEM070413TA9', 0.16),
(75, 'HÉROES', 'Estatal Qroo', 'SEM070413TA9', 0.08),
(76, 'HUAY PIX', 'Estatal Qroo', 'SEM070413TA9', 0.08),
(77, 'MAXUXAC', 'Estatal Qroo', 'SEM070413TA9', 0.08),
(78, 'EXPOFERIA', 'Estatal Yucatán', 'SYU110901LR9', 0.16),
(79, 'KOPOMA ', 'Estatal Yucatán', 'SYU110901LR9', 0.16),
(80, 'MUNA', 'Estatal Yucatán', 'SYU110901LR9', 0.16),
(81, 'TIZIMÍN', 'Estatal Yucatán', 'SYU110901LR9', 0.16),
(82, 'PUEBLA CENTRO', 'Estatal Puebla', 'SEP200807463', 0.16),
(83, 'TUXTLA SANTA MARIA', 'Estatal Chiapas', 'SEP200807463', 0.16),
(84, '14 SUR', 'Estatal Puebla', 'GRM1604267N3', 0.16),
(85, 'AGUA AZUL', 'Estatal Puebla', 'GON130219GD4', 0.16),
(86, 'ALLENDE', 'Estatal Coahuila', 'PET070605PC2', 0.16),
(87, 'AMALUCAN', 'Estatal Puebla', 'GRM1604267N3', 0.16),
(88, 'AMOZOC', 'Estatal Puebla', 'SEF200821N64', 0.16),
(89, 'AMOZOC 2', 'Estatal Puebla', 'SGA150629E68', 0.16),
(90, 'ATEXCAC', 'Estatal Puebla', 'SEF200821N64', 0.16),
(91, 'ATLIXCO', 'Estatal Puebla', 'SEF200821N64', 0.16),
(100, 'ATLIXCO 2', 'Estatal Puebla', 'SEF200821N64', 0.16),
(101, 'BOSQUES', 'Estatal Puebla', 'GRM1604267N3', 0.16),
(102, 'CANCÚN MADERO ', 'Estatal Qroo', 'SEM1512187Y9', 0.16),
(103, 'CAPU', 'Estatal Puebla', 'MOR9602092P5', 0.16),
(104, 'CHOLULA', 'Estatal Puebla', 'SEF200821N64', 0.16),
(105, 'COLÓN', 'Estatal Nuevo León', 'SCO140610PY0', 0.16),
(106, 'EL DORADO', 'Estatal Edo Mex', 'VEGV560707KD2', 0.16),
(107, 'FORJADORES', 'Estatal Puebla', 'SEF200821N64', 0.16),
(108, 'HERMANAS', 'Estatal Coahuila', 'PET070605PC2', 0.16),
(109, 'HERMANOS SERDAN', 'Estatal Puebla', 'GRM1604267N3', 0.16),
(110, 'LOS ANGELES', 'Estatal Puebla', 'GRA141128124', 0.16),
(111, 'METEPEC', 'Estatal Edo Mex', 'SME960327222', 0.16),
(112, 'NUEVA ROSITA 2', 'Estatal Coahuila', 'PET070605PC2', 0.16),
(113, 'PERINORTE', 'Estatal Puebla', 'SEF200821N64', 0.16),
(114, 'SABINAS', 'Estatal Coahuila', 'PNO130206NW7', 0.16),
(115, 'SAN MATEO', 'Estatal Edo Mex', 'SSM0302202G0', 0.16),
(116, 'TLAXCALA', 'Estatal Tlaxcala', 'SEC141031B4A', 0.16),
(117, 'TORRECILLAS', 'Estatal Puebla', 'GRM1604267N3', 0.16),
(118, 'TUXTLA BELISARIO', 'Estatal Chiapas', 'CRC0412029S2', 0.16),
(119, 'VILLA VERDE', 'Estatal Puebla', 'GVV110118HW7', 0.16),
(120, 'XONACA', 'Estatal Puebla', 'MOR9602092P5', 0.16),
(121, 'YAXCHÉ', 'Estatal Yucatán', 'SEC150112537', 0.16),
(122, 'LA MARQUESA', 'Estatal Edo Mex', 'SRT060418CG3', 0.16),
(123, 'BOULEBARD NORTE', 'Estatal Puebla', NULL, 0.00),
(124, 'FELIX U. GOMEZ', 'Estatal Nuevo León', NULL, 0.00),
(125, 'n', 'Estatal Yucatán', NULL, 0.00);

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
  `siic_excel` varchar(20) DEFAULT NULL,
  `siic_inteligas` varchar(20) DEFAULT NULL,
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
  `precio_diesel` decimal(10,2) DEFAULT NULL,
  `porcentaje_utilidad_magna` decimal(6,2) DEFAULT NULL,
  `porcentaje_utilidad_premium` decimal(6,2) DEFAULT NULL,
  `porcentaje_utilidad_diesel` decimal(6,2) DEFAULT NULL,
  `utilidad_litro_magna` decimal(10,2) DEFAULT NULL,
  `utilidad_litro_premium` decimal(10,2) DEFAULT NULL,
  `utilidad_litro_diesel` decimal(10,2) DEFAULT NULL,
  `modificado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `precios_combustible`
--

INSERT INTO `precios_combustible` (`id`, `fecha`, `siic_excel`, `siic_inteligas`, `zona`, `razon_social`, `estacion`, `vu_magna`, `vu_premium`, `vu_diesel`, `costo_flete`, `pf_magna`, `pf_premium`, `pf_diesel`, `precio_magna`, `precio_premium`, `precio_diesel`, `porcentaje_utilidad_magna`, `porcentaje_utilidad_premium`, `porcentaje_utilidad_diesel`, `utilidad_litro_magna`, `utilidad_litro_premium`, `utilidad_litro_diesel`, `modificado`) VALUES
(1, '2025-05-20', '117311', '13191', 'CAMPECHE', 'SE MALECON', 'PALMIRA', 21.82, 22.86, 24.01, 0.25, 21.07, 22.11, 23.26, 23.99, 25.99, 25.99, 13.86, 17.54, 11.74, 2.92, 3.88, 2.73, 0),
(2, '2025-05-20', '117223', '13103', 'CAMPECHE', 'SE MALECON', 'BUENAVISTA', 21.82, 22.86, 24.01, 0.25, 21.07, 22.11, 23.26, 23.99, 25.59, 26.05, 13.86, 15.73, 12.00, 2.92, 3.48, 2.79, 0),
(3, '2025-05-20', '117236', '13116', 'CAMPECHE', 'SE CALKINI', 'ISLA AGUADA', 21.82, 22.86, 24.01, 0.23, 21.05, 22.09, 23.24, 23.89, 25.89, 26.79, 13.50, 17.20, 15.28, 2.84, 3.80, 3.55, 0),
(4, '2025-05-20', '117006', '12886', 'CAMPECHE', 'SE MUYIL', 'ESCARCEGA LEMUS', 21.82, 22.86, 24.01, 0.20, 21.02, 22.06, 23.21, 23.99, 25.99, 26.89, 14.13, 17.81, 15.86, 2.97, 3.93, 3.68, 0),
(5, '2025-05-20', '117006', '60009', 'CAMPECHE', 'SE MAYAPAN', 'ESCARCEGA ALARCÓN', 21.82, 22.86, 24.01, 0.20, 21.02, 22.06, 23.21, 23.99, 25.99, 26.89, 14.13, 17.81, 15.86, 2.97, 3.93, 3.68, 0),
(6, '2025-05-20', '116897', '12777', 'CAMPECHE', 'SE CHAMPOTON', 'CHAMPOTÓN  MALECÓN.', 21.82, 22.86, 24.01, 0.13, 20.95, 21.99, 23.14, 23.99, 25.59, 26.66, 14.50, 16.35, 15.20, 3.04, 3.60, 3.52, 0),
(7, '2025-05-20', '116896', '13117', 'CAMPECHE', 'SE ISLA AGUADA', 'CHAMPOTÓN  RESTAURANTE', 21.82, 22.86, 24.01, 0.13, 20.95, 22.00, 23.14, 23.99, 25.59, 26.66, 14.49, 16.34, 15.19, 3.04, 3.59, 3.52, 0),
(8, '2025-05-20', '', '60029', 'CAMPECHE', 'SE ISLA AGUADA', 'CHAMPOTÓN  ECHEVERRIA', 21.82, 22.86, 24.01, 0.13, 20.95, 22.00, 23.14, 23.99, 25.59, 26.66, 14.49, 16.34, 15.19, 3.04, 3.59, 3.52, 0),
(9, '2025-05-20', '116896', '60028', 'CAMPECHE', 'SE ISLA AGUADA', 'HOPELCHÉN', 21.82, 22.86, 24.01, 0.22, 21.04, 22.08, 23.23, 23.99, 25.69, 26.29, 14.03, 16.34, 13.17, 2.95, 3.61, 3.06, 0),
(10, '2025-05-20', '116896', '60006', 'CAMPECHE', 'SE BENITO JUAREZ', 'VILLACABRA', 21.82, 22.86, 24.01, 0.09, 20.90, 21.95, 23.10, 23.99, 25.80, 26.80, 14.76, 17.56, 16.04, 3.09, 3.85, 3.70, 0),
(11, '2025-05-20', '', '60005', 'CAMPECHE', 'SE BENITO JUAREZ', 'PGJ', 21.82, 22.86, 24.01, 0.09, 20.90, 21.95, 23.10, 23.93, 26.04, 26.53, 14.47, 18.65, 14.87, 3.03, 4.09, 3.43, 0),
(12, '2025-05-20', '', '60004', 'CAMPECHE', 'SE BENITO JUAREZ', 'JUÁREZ', 21.82, 22.86, 24.01, 0.12, 20.94, 21.98, 23.13, 23.99, 25.90, 26.39, 14.56, 17.82, 14.08, 3.05, 3.92, 3.26, 0),
(13, '2025-05-20', '', '60011', 'CAMPECHE', 'SE BENITO JUAREZ', 'IMÍ', 21.82, 22.86, 24.01, 0.09, 20.90, 21.95, 23.10, 23.99, 25.99, 26.29, 14.76, 18.43, 13.83, 3.09, 4.04, 3.19, 0),
(14, '2025-05-20', '', '60012', 'CAMPECHE', 'SE BENITO JUAREZ', 'COLOSIO', 21.82, 22.86, 24.01, 0.12, 20.94, 21.98, 23.13, 23.99, 25.99, 26.69, 14.56, 18.23, 15.38, 3.05, 4.01, 3.56, 0),
(15, '2025-05-20', '107645', '12776', 'CAMPECHE', 'SE MUYIL', 'TENABO', 21.82, 22.86, 24.01, 0.12, 20.94, 21.98, 23.13, 23.85, 25.85, 26.05, 13.92, 17.62, 12.64, 2.91, 3.87, 2.92, 0),
(16, '2025-05-20', '', '60035', 'CAMPECHE', 'SE MAYAPAN', 'CALKINI CENTRO', 21.82, 22.86, 24.01, 0.13, 20.95, 21.99, 23.14, 23.99, 24.99, 25.29, 14.52, 13.64, 9.29, 3.04, 3.00, 2.15, 0),
(17, '2025-05-20', '', '60049', 'CAMPECHE', 'SE CHICHEN', 'CALKINI 2', 21.82, 22.86, 24.01, 0.13, 20.95, 21.99, 23.14, 23.99, 25.69, 26.89, 14.52, 16.82, 16.21, 3.04, 3.70, 3.75, 0),
(18, '2025-05-20', '', '60050', 'CAMPECHE', 'SE CALETA TANKAH', 'SEYBAPLAYA', 21.82, 22.86, 24.01, 0.13, 20.95, 21.99, 23.14, 24.99, 25.89, 26.78, 19.29, 17.73, 15.73, 4.04, 3.90, 3.64, 0),
(19, '2025-05-20', '116886', '12766', 'CAMPECHE', 'SE CHAMPOTON', 'HECELCHAKÁN', 21.82, 22.86, 24.01, 0.14, 20.96, 22.01, 23.15, 23.99, 25.99, 26.89, 14.44, 18.11, 16.14, 3.03, 3.98, 3.74, 0),
(20, '2025-05-20', '107681', '13211', 'MÉRIDA FORÁNEAS', 'SE RUINAS DE EDZNA', 'PETO', 21.82, 22.53, 23.68, 0.20, 21.02, 21.73, 22.88, 24.69, 25.99, 26.89, 17.46, 19.59, 17.52, 3.67, 4.26, 4.01, 0),
(21, '2025-05-20', '115723', '565', 'MÉRIDA FORÁNEAS', 'SE RUINAS DE EDZNA', 'TZUCACAB', 21.82, 22.53, 23.68, 0.20, 21.02, 21.73, 22.88, 24.69, 25.99, 26.89, 17.46, 19.59, 17.52, 3.67, 4.26, 4.01, 0),
(22, '2025-05-20', '', '60047', 'MÉRIDA FORÁNEAS', 'SE CALETA TANKAH', 'MORELOS', 21.82, 22.53, 23.68, 0.30, 21.12, 21.83, 22.98, 24.93, 25.98, 26.78, 18.04, 19.00, 16.53, 3.81, 4.15, 3.80, 0),
(23, '2025-05-20', '115448', '11603', 'MÉRIDA FORÁNEAS', 'SYU', 'MUNA', 21.82, 0.00, 23.68, 0.12, 20.94, 0.00, 22.81, 24.68, 0.00, 26.45, 17.84, 0.00, 15.98, 3.74, 0.00, 3.64, 0),
(24, '2025-05-20', '115689', '11328', 'MÉRIDA FORÁNEAS', 'SYU', 'KOPOMA ', 21.82, 22.53, 23.68, 0.12, 20.94, 21.65, 22.80, 23.99, 26.09, 27.29, 14.59, 20.52, 19.71, 3.05, 4.44, 4.49, 0),
(25, '2025-05-20', '', '11393', 'MÉRIDA FORÁNEAS', 'S.E DE YUCATAN S DE RL DE CV', 'UMÁN', 21.82, 22.53, 23.68, 0.07, 20.89, 21.60, 22.75, 23.13, 23.89, 26.89, 10.74, 10.60, 18.20, 2.24, 2.29, 4.14, 0),
(26, '2025-05-20', '116846', NULL, 'MÉRIDA CENTRO', '', 'n', 21.82, 22.53, 23.68, 0.07, 20.89, 21.60, 22.75, 23.99, 25.69, 26.89, 14.84, 18.92, 18.19, 3.10, 4.09, 4.14, 0),
(27, '2025-05-20', '116247', '12726', 'MÉRIDA CENTRO', 'SEK', 'KANASÍN 58', 21.82, 22.53, 23.68, 0.07, 20.89, 21.60, 22.75, 23.99, 25.69, 26.89, 14.85, 18.93, 18.20, 3.10, 4.09, 4.14, 0),
(28, '2025-05-20', '112235', '12127', 'MÉRIDA CENTRO', 'S.E DE YUCATAN S DE RL DE CV', 'CHUBURNÁ', 21.82, 22.53, 0.00, 0.07, 20.89, 21.60, 0.00, 23.99, 25.69, 0.00, 14.84, 18.92, 0.00, 3.10, 4.09, 0.00, 0),
(29, '2025-05-20', '115513', '8115', 'MÉRIDA CENTRO', 'SE CORREDOR TURISTICO', 'MAYA', 21.82, 22.53, 23.68, 0.07, 20.89, 21.60, 22.75, 23.99, 25.69, 26.89, 14.84, 18.92, 18.19, 3.10, 4.09, 4.14, 0),
(30, '2025-05-20', '', '60007', 'MÉRIDA CENTRO', 'SE CORREDOR TURISTICO', 'MULSAY', 21.82, 22.53, 23.68, 0.07, 20.89, 21.60, 22.75, 23.39, 24.69, 26.89, 11.98, 14.30, 18.20, 2.50, 3.09, 4.14, 0),
(31, '2025-05-20', '', '12563', 'MÉRIDA CENTRO', 'SE CHICHEN', 'CAUCEL', 21.82, 22.53, 0.00, 0.07, 20.89, 21.60, 0.00, 23.99, 25.69, 0.00, 14.85, 18.93, 0.00, 3.10, 4.09, 0.00, 0),
(32, '2025-05-20', '110894', '13346', 'MÉRIDA CENTRO', 'SE MAYAPAN', 'MOTUL', 21.82, 22.53, 23.68, 0.13, 20.95, 21.66, 22.81, 24.69, 26.04, 27.44, 17.86, 20.21, 20.29, 3.74, 4.38, 4.63, 0),
(33, '2025-05-20', '115665', '3875', 'VALLADOLID 2', 'SE CALKINI', 'CHEMAX', 21.82, 22.53, 0.00, 0.30, 21.12, 21.83, 0.00, 24.93, 26.29, 0.00, 18.04, 20.42, 0.00, 3.81, 4.46, 0.00, 0),
(34, '2025-05-20', '112312', '11545', 'VALLADOLID', 'SYU', 'EXPOFERIA', 21.82, 22.53, 23.68, 0.30, 21.12, 21.83, 22.98, 24.68, 26.12, 27.42, 16.86, 19.64, 19.32, 3.56, 4.29, 4.44, 0),
(35, '2025-05-20', '117124', '8192', 'VALLADOLID', 'SE CALKINI', 'EBTÚN', 21.82, 22.53, 23.68, 0.30, 21.12, 21.83, 22.98, 24.68, 26.12, 27.42, 16.86, 19.64, 19.32, 3.56, 4.29, 4.44, 0),
(36, '2025-05-20', '107652', '13004', 'VALLADOLID', 'S.E DE YUCATAN S DE RL DE CV', 'VALLADOLID', 21.82, 22.53, 23.68, 0.30, 21.12, 21.83, 22.98, 24.68, 26.12, 27.42, 16.86, 19.64, 19.32, 3.56, 4.29, 4.44, 0),
(37, '2025-05-20', '', '60044', 'VALLADOLID', '', 'MERCADO', 21.82, 22.53, 23.68, 0.30, 21.12, 21.83, 22.98, 24.68, 26.12, 27.42, 16.86, 19.64, 19.32, 3.56, 4.29, 4.44, 0),
(38, '2025-05-20', '', '60051', 'VALLADOLID', 'SE MAYAPAN', 'SAN JUAN', 21.82, 22.53, 23.68, 0.30, 21.12, 21.83, 22.98, 24.68, 26.12, 27.42, 16.86, 19.64, 19.32, 3.56, 4.29, 4.44, 0),
(39, '2025-05-20', '109426', '4886', 'VALLADOLID', 'SE CALKINI', 'ZACÍ', 21.82, 22.53, 0.00, 0.30, 21.12, 21.83, 0.00, 24.68, 26.12, 0.00, 16.86, 19.64, 0.00, 3.56, 4.29, 0.00, 0),
(40, '2025-05-20', '112415', '5732', 'VALLADOLID', 'SE CALKINI', 'SACIABIL', 21.82, 22.53, 0.00, 0.30, 21.12, 21.83, 0.00, 24.68, 26.12, 0.00, 16.86, 19.64, 0.00, 3.56, 4.29, 0.00, 0),
(41, '2025-05-20', '107654', '8295', 'VALLADOLID 2', 'SE CALKINI', 'YAXCHÉ', 21.82, 22.53, 23.68, 0.30, 21.12, 21.83, 22.98, 24.68, 26.12, 27.42, 16.86, 19.64, 19.32, 3.56, 4.29, 4.44, 0),
(42, '2025-05-20', '117331', '559', 'VALLADOLID', 'SEK', 'PISTÉ', 21.82, 22.53, 23.68, 0.40, 21.22, 21.93, 23.08, 24.49, 25.94, 27.24, 15.41, 18.28, 18.02, 3.27, 4.01, 4.16, 0),
(43, '2025-05-20', '107736', '11807', 'VALLADOLID 2', 'SYU', 'TIZIMÍN', 21.82, 22.53, 23.68, 0.30, 21.12, 21.83, 22.98, 23.99, 25.99, 26.69, 13.59, 19.05, 16.14, 2.87, 4.16, 3.71, 0),
(44, '2025-05-20', '116377', '3941', 'VALLADOLID 2', 'JGE SUCESORES', 'EL CUYO', 22.59, 0.00, 0.00, 0.30, 21.89, 0.00, 0.00, 25.70, 0.00, 0.00, 17.41, 0.00, 0.00, 3.81, 0.00, 0.00, 0),
(45, '2025-05-20', '115559', '12257', 'VALLADOLID 2', 'CGM', 'COLONIA YUCATÁN', 21.82, 22.53, 23.68, 0.30, 21.12, 21.83, 22.98, 25.55, 26.54, 26.98, 20.98, 21.57, 17.40, 4.43, 4.71, 4.00, 0),
(46, '2025-05-20', '114787', '9816', 'CHETUMAL', 'SE CAKINI', 'PEDRO SANTOS', 21.82, 22.53, 23.68, 0.50, 21.32, 22.03, 23.18, 23.99, 26.02, 26.19, 12.53, 18.10, 12.98, 2.67, 3.99, 3.01, 0),
(47, '2025-05-20', '114357', '10667', 'CHETUMAL', 'S.E DEL MAYAB S DE RL DE CV', 'BACALAR', 21.82, 22.53, 23.68, 0.50, 21.32, 22.03, 23.18, 23.99, 25.82, 25.99, 12.53, 17.19, 12.12, 2.67, 3.79, 2.81, 0),
(48, '2025-05-20', '114357', '10237', 'CHETUMAL', 'S.E DEL MAYAB S DE RL DE CV', 'HUAY PIX', 21.82, 22.53, 24.06, 0.50, 21.32, 22.03, 23.56, 23.89, 25.29, 25.25, 12.06, 14.79, 7.17, 2.57, 3.26, 1.69, 0),
(49, '2025-05-20', '115183', '11072', 'CHETUMAL', 'S.E DEL MAYAB S DE RL DE CV', 'HÉROES', 21.81, 22.50, 0.00, 0.47, 20.77, 21.41, 0.00, 24.38, 25.38, 0.00, 23.31, 24.34, 0.00, 5.68, 6.18, 0.00, 0),
(50, '2025-05-20', '115185', '11063', 'CHETUMAL', 'S.E DEL MAYAB S DE RL DE CV', 'MAXUXAC', 21.81, 22.50, 23.65, 0.47, 19.77, 20.41, 21.49, 24.38, 25.38, 26.18, 29.88, 30.75, 27.80, 7.29, 7.80, 7.28, 0),
(51, '2025-05-20', '', '13559', 'CHETUMAL', 'SE CORREDOR TURISTICO', 'MAXUXAC 2', 21.81, 22.50, 23.65, 0.47, 19.77, 20.41, 21.49, 24.29, 24.99, 25.79, 29.40, 28.74, 25.89, 7.14, 7.18, 6.68, 0),
(52, '2025-05-20', '', '11985', 'CHETUMAL', 'SE MAYAPAN', 'ROJO GOMEZ', 21.81, 22.50, 23.65, 0.47, 19.77, 20.41, 21.49, 24.19, 24.93, 25.19, 28.87, 28.43, 22.97, 6.98, 7.09, 5.78, 0),
(53, '2025-05-20', '116574', '11065', 'CHETUMAL', 'S.E DEL MAYAB S DE RL DE CV', 'ANDRÉS QROO.', 21.81, 22.50, 0.00, 0.47, 19.77, 20.41, 0.00, 24.32, 25.32, 0.00, 29.56, 30.44, 0.00, 7.19, 7.71, 0.00, 0),
(54, '2025-05-20', '110722', '12454', 'CHETUMAL', 'S.E DE YUCATAN S DE RL DE CV', 'KOHUNLICH', 21.81, 22.50, 23.65, 0.47, 19.77, 20.41, 21.49, 24.38, 25.38, 26.18, 29.88, 30.75, 27.80, 7.29, 7.80, 7.28, 0),
(55, '2025-05-20', '', '13435', 'CHETUMAL', 'SE MAYAPAN', 'CARRILLO PUERTO', 21.82, 22.53, 23.68, 0.45, 21.27, 21.98, 23.13, 24.93, 26.74, 27.14, 17.21, 21.65, 17.33, 3.66, 4.76, 4.01, 0),
(56, '2025-05-20', '114006', '6774', 'CANCÚN', '', 'TULUM CRUCERO', 21.82, 22.53, 0.00, 0.45, 21.27, 21.98, 0.00, 24.89, 26.63, 0.00, 17.02, 21.15, 0.00, 3.62, 4.65, 0.00, 0),
(57, '2025-05-20', '113936', '9886', 'CANCÚN', 'S.E DEL MAYAB S DE RL DE CV', 'TULUM ZAMNA', 21.82, 22.53, 23.68, 0.45, 21.27, 21.98, 23.13, 24.93, 26.63, 27.76, 17.21, 21.15, 20.01, 3.66, 4.65, 4.63, 0),
(58, '2025-05-20', '', '60030', 'CANCÚN', 'SEK', 'TULUM COBA', 21.82, 22.53, 23.68, 0.45, 21.27, 21.98, 23.13, 24.89, 26.63, 27.38, 17.02, 21.15, 18.37, 3.62, 4.65, 4.25, 0),
(59, '2025-05-20', '', '6602', 'CANCÚN', 'SE CALKINI', 'IDEAL', 21.82, 0.00, 23.68, 0.35, 21.17, 0.00, 23.03, 25.70, 0.00, 26.99, 21.40, 0.00, 17.19, 4.53, 0.00, 3.96, 0),
(60, '2025-05-20', '115927', '13344', 'CANCÚN', 'SE MAR CARIBE', 'CANCÚN MADERO ', 21.82, 22.53, 23.68, 0.50, 21.32, 22.03, 23.18, 24.92, 26.89, 27.78, 16.89, 22.05, 19.84, 3.60, 4.86, 4.60, 0),
(61, '2025-05-20', '', '60002', 'CANCÚN', 'SE MAR CARIBE', 'C. LÓPEZ PORTILLO', 21.82, 22.53, 23.68, 0.50, 21.32, 22.03, 23.18, 24.94, 26.89, 27.84, 16.98, 22.05, 20.10, 3.62, 4.86, 4.66, 0),
(62, '2025-05-20', '', '60016', 'CANCÚN', 'SE MAYABAN', 'C. LÓPEZ PORTILLO 2', 21.82, 22.53, 23.68, 0.50, 21.32, 22.03, 23.18, 24.94, 26.89, 27.79, 16.98, 22.05, 19.88, 3.62, 4.86, 4.61, 0),
(63, '2025-05-20', '', '60014', 'CANCÚN', 'SE CHICHEN', 'RANCHO VIEJO', 21.82, 22.53, 23.68, 0.50, 21.32, 22.03, 23.18, 24.93, 26.93, 27.78, 16.94, 22.23, 19.84, 3.61, 4.90, 4.60, 0),
(64, '2025-05-20', '', '60015', 'CANCÚN', 'SE CHICHEN', 'RUTA 5', 21.82, 22.53, 23.68, 0.50, 21.32, 22.03, 23.18, 24.93, 26.93, 27.78, 16.94, 22.23, 19.84, 3.61, 4.90, 4.60, 0),
(65, '2025-05-20', '', '60013', 'CANCÚN', 'SE CHICHEN', '20 DE NOVIEMBRE', 21.82, 22.53, 0.00, 0.50, 21.32, 22.03, 0.00, 24.93, 26.88, 0.00, 16.94, 22.01, 0.00, 3.61, 4.85, 0.00, 0),
(66, '2025-05-20', '', '60008', 'CENTRO', 'ALIANZA EMPRESARIAL DE TECAMAC', 'REAL GRANADA', 21.00, 21.45, 22.70, 0.25, 21.66, 21.45, 22.70, 25.70, 24.89, 25.05, 15.35, 16.04, 10.35, 3.14, 3.44, 2.35, 0),
(67, '2025-05-20', '', '60022', 'CENTRO', 'SE SAN FRANCISCO', 'REAL VERONA', 21.35, 22.05, 22.00, 0.00, 21.35, 22.05, 22.00, 23.39, 24.83, 24.99, 9.56, 12.61, 13.59, 2.04, 2.78, 2.99, 0),
(68, '2025-05-20', '', '13439', 'CENTRO', 'ALIANZA EMPRESARIAL DE TECAMAC', 'SIERRA HERMOSA', 21.41, 21.45, 22.10, 0.25, 21.66, 21.45, 22.10, 25.70, 25.35, 25.19, 14.87, 18.18, 13.98, 3.04, 3.90, 3.09, 1),
(69, '2025-05-20', '', '60003', 'CENTRO', 'AE TECAMAC', 'TECAMÁC MP', 21.41, 21.49, 22.10, 0.25, 21.66, 21.49, 22.10, 25.70, 25.59, 24.95, 17.31, 19.08, 12.90, 3.54, 4.10, 2.85, 0),
(70, '2025-05-20', '', '60010', 'CENTRO', 'SE SAN FRANCISCO', 'ABROJALITO', 21.35, 21.49, 22.10, 0.00, 21.35, 21.49, 22.10, 23.79, 25.59, 25.29, 11.43, 19.08, 14.43, 2.44, 4.10, 3.19, 0),
(71, '2025-05-20', '', '60048', 'CENTRO', 'VICTOR MANUEL VELAZQUEZ GARDUNO', 'EL DORADO', 21.12, 21.45, 22.70, 0.00, 21.12, 21.45, 22.70, 23.87, 25.27, 25.29, 13.02, 17.81, 11.41, 2.75, 3.82, 2.59, 0),
(72, '2025-05-20', '', '4821', 'CENTRO', 'SERVICIO MEGAL', 'METEPEC', 22.05, 22.30, 0.00, 0.00, 22.05, 22.30, 0.00, 23.79, 24.39, 0.00, 7.89, 9.37, 0.00, 1.74, 2.09, 0.00, 0),
(73, '2025-05-20', ' ', '9620', 'CENTRO', 'SERVICIO RIMER DEL TIANGUISTENCO', 'LA MARQUESA', 21.12, 22.05, 22.10, 0.00, 21.12, 22.05, 22.10, 23.89, 24.99, 25.18, 13.12, 13.33, 13.94, 2.77, 2.94, 3.08, 0),
(74, '2025-05-20', '', '7181', 'CENTRO', 'SUPER SERVICIO M Y M SA DE CV', 'SAN MATEO', 20.45, 21.49, 22.10, 0.00, 20.45, 21.49, 22.10, 22.89, 23.89, 24.79, 11.93, 11.17, 12.17, 2.44, 2.40, 2.69, 0),
(75, '2025-05-20', '', '13530', 'CENTRO', 'SE CORREDOR TURISTICO', 'CALIMAYA', 21.40, 22.30, 23.11, 0.00, 21.40, 22.30, 23.11, 23.99, 24.35, 25.19, 12.10, 9.19, 9.00, 2.59, 2.05, 2.08, 0),
(76, '2025-05-20', '', '60023', 'PUEBLA', 'GRUPO REPOSTA MEXICO ', 'TORRECILLAS', 20.90, 22.05, 22.39, 0.00, 20.90, 22.05, 22.39, 23.07, 23.79, 24.99, 10.38, 7.89, 11.61, 2.17, 1.74, 2.60, 0),
(77, '2025-05-20', '', '60026', 'PUEBLA', 'GRUPO REPOSTA MEXICO ', 'BOSQUES', 21.16, 22.05, 22.39, 0.00, 21.16, 22.05, 22.39, 23.89, 24.39, 24.79, 12.90, 10.61, 10.72, 2.73, 2.34, 2.40, 0),
(78, '2025-05-20', '', '60024', 'PUEBLA', 'GRUPO REPOSTA MEXICO ', '14 SUR', 20.90, 20.90, 22.39, 0.00, 20.90, 20.90, 22.39, 23.09, 23.79, 24.99, 10.48, 13.83, 11.61, 2.19, 2.89, 2.60, 0),
(79, '2025-05-20', '', '60027', 'PUEBLA', 'GRUPO REPOSTA MEXICO ', 'HERMANOS SERDAN', 21.69, 22.00, 22.90, 0.00, 21.69, 22.00, 22.90, 23.43, 24.29, 25.89, 8.02, 10.41, 13.06, 1.74, 2.29, 2.99, 0),
(80, '2025-05-20', '', '60025', 'PUEBLA', 'GRUPO REPOSTA MEXICO', 'AMALUCAN', 21.00, 22.05, 22.50, 0.00, 21.00, 22.05, 22.50, 23.01, 24.19, 24.89, 9.57, 9.71, 10.62, 2.01, 2.14, 2.39, 0),
(81, '2025-05-20', '', '60031', 'PUEBLA', 'GASOLINERA LA ONCE ', 'AGUA AZUL', 20.90, 20.90, 22.30, 0.00, 20.90, 20.90, 22.30, 23.09, 23.79, 25.19, 10.48, 13.83, 12.96, 2.19, 2.89, 2.89, 0),
(82, '2025-05-20', '', '60032', 'PUEBLA', 'GASOLINERA RASAGUI', 'LOS ANGELES', 21.16, 20.90, 22.90, 0.00, 20.50, 20.90, 22.90, 23.19, 24.54, 24.89, 13.12, 17.42, 8.69, 2.69, 3.64, 1.99, 0),
(83, '2025-05-20', '', '60033', 'PUEBLA', 'COMBUSTIBLE DECOM', 'BOULEBARD NORTE', 20.69, 21.04, 22.10, 0.00, 20.50, 21.04, 22.10, 22.90, 23.99, 25.10, 11.71, 14.02, 13.57, 2.40, 2.95, 3.00, 0),
(84, '2025-05-20', '', '60034', 'PUEBLA', 'GASOLINERA VILLA VERDE ', 'VILLA VERDE', 20.90, 20.90, 22.30, 0.00, 20.90, 20.90, 22.30, 23.49, 23.99, 24.99, 12.39, 14.78, 12.06, 2.59, 3.09, 2.69, 0),
(85, '2025-05-20', '', '60040', 'PUEBLA', 'EL FARO', 'CHOLULA', 20.51, 22.05, 22.90, 0.00, 20.51, 22.05, 22.90, 23.09, 23.97, 24.85, 12.58, 8.71, 8.52, 2.58, 1.92, 1.95, 0),
(86, '2025-05-20', '', '60042', 'PUEBLA', 'EL FARO', 'ATLIXCO', 21.16, 20.90, 23.05, 0.00, 21.16, 20.90, 23.05, 23.10, 24.10, 24.94, 9.17, 15.31, 8.20, 1.94, 3.20, 1.89, 0),
(87, '2025-05-20', '', '60037', 'PUEBLA', 'EL FARO', 'AMOZOC', 21.16, 21.98, 22.90, 0.00, 21.16, 21.98, 22.90, 23.29, 24.49, 25.25, 10.07, 11.42, 10.26, 2.13, 2.51, 2.35, 0),
(88, '2025-05-20', '', '13306', 'PUEBLA', 'SORTEGAS SA DE CV', 'AMOZOC 2', 21.00, 21.50, 22.90, 0.00, 21.00, 21.50, 22.90, 23.29, 24.49, 25.25, 10.90, 13.91, 10.26, 2.29, 2.99, 2.35, 0),
(89, '2025-05-20', '', '5236', 'PUEBLA', 'MORPAR SA DE CV', 'XONACA', 21.00, 21.04, 0.00, 0.00, 21.00, 21.04, 0.00, 22.89, 24.39, 0.00, 9.00, 15.92, 0.00, 1.89, 3.35, 0.00, 0),
(90, '2025-05-20', '', '4772', 'PUEBLA', 'MORPAR SA DE CV', 'CAPU', 21.00, 0.00, 22.90, 0.00, 21.00, 0.00, 22.90, 22.95, 0.00, 24.69, 9.29, 0.00, 7.82, 1.95, 0.00, 1.79, 0),
(91, '2025-05-20', '', '60038', 'PUEBLA', 'EL FARO', 'ATEXCAC', 20.90, 22.00, 22.90, 0.00, 20.90, 22.00, 22.90, 22.99, 23.99, 24.89, 10.00, 9.05, 8.69, 2.09, 1.99, 1.99, 0),
(92, '2025-05-20', '', '60041', 'PUEBLA', 'EL FARO', 'ATLIXCO 2', 21.16, 20.90, 23.05, 0.00, 21.16, 20.90, 23.05, 23.10, 24.10, 24.94, 9.17, 15.31, 8.20, 1.94, 3.20, 1.89, 0),
(93, '2025-05-20', '', '60036', 'PUEBLA', 'EL FARO ', 'FORJADORES', 21.16, 22.05, 22.90, 0.00, 21.16, 22.05, 22.90, 22.99, 24.69, 24.99, 8.65, 11.97, 9.13, 1.83, 2.64, 2.09, 0),
(94, '2025-05-20', '', '60039', 'PUEBLA', 'EL FARO', 'PERINORTE', 21.16, 22.05, 22.90, 0.00, 21.16, 22.05, 22.90, 23.29, 23.99, 24.90, 10.07, 8.80, 8.73, 2.13, 1.94, 2.00, 0),
(95, '2025-05-20', '', '60021', 'PUEBLA', 'ES PUERTO AVENTURA', 'PUEBLA CENTRO', 20.90, 20.90, 22.30, 0.00, 20.90, 20.90, 22.30, 22.89, 23.99, 24.65, 9.52, 14.78, 10.54, 1.99, 3.09, 2.35, 0),
(96, '2025-05-20', '', '838', 'CENTRO', 'SE CUIDAD DEL CARMEN', 'TLAXCALA', 21.16, 0.00, 0.00, 0.00, 21.10, 0.00, 0.00, 23.50, 0.00, 0.00, 11.37, 0.00, 0.00, 2.40, 0.00, 0.00, 0),
(97, '2025-05-20', '', '8755', 'CHIS y AGS', '', 'TUXTLA BELISARIO', 22.03, 22.99, 22.53, 0.09, 22.12, 23.08, 22.62, 23.99, 25.45, 26.10, 8.45, 10.26, 15.38, 1.87, 2.37, 3.48, 0),
(98, '2025-05-20', '', '3493', 'CHIS y AGS', 'ES PUERTO AVENTURA', 'TUXTLA SANTA MARIA', 22.03, 22.99, 22.53, 0.09, 22.18, 23.08, 22.62, 23.55, 24.75, 25.99, 6.18, 7.23, 14.89, 1.37, 1.67, 3.37, 0),
(99, '2025-05-20', '', NULL, 'CHIS y AGS', '', 'AGUASCALIENTES', 21.52, 22.74, 0.00, 0.09, 21.61, 22.83, 0.00, 23.99, 25.19, 26.09, 11.04, 10.36, 0.00, 2.38, 2.36, 0.00, 0),
(100, '2025-05-20', '', '9865', 'COAHUILA', 'PETROTANQUES', 'NUEVA ROSITA 2', 20.86, 21.45, 22.47, 0.00, 20.86, 21.45, 22.47, 23.99, 26.00, 26.70, 15.00, 21.21, 18.83, 3.13, 4.55, 4.23, 0),
(101, '2025-05-20', '', '9848', 'COAHUILA', 'PREMIER NOGALAR SA DE CV', 'SABINAS', 20.08, 21.61, 0.00, 0.00, 20.08, 21.61, 0.00, 23.99, 26.99, 0.00, 19.47, 24.90, 0.00, 3.91, 5.38, 0.00, 0),
(102, '2025-05-20', '', '2210', 'COAHUILA', 'PETROTANQUES', 'HERMANAS', 20.86, 21.35, 0.00, 0.00, 20.86, 21.35, 0.00, 23.99, 26.99, 0.00, 15.00, 26.42, 0.00, 3.13, 5.64, 0.00, 0),
(103, '2025-05-20', '', '11415', 'COAHUILA', 'PETROTANQUES', 'ALLENDE', 20.86, 22.08, 22.26, 0.00, 20.86, 22.08, 22.26, 25.99, 26.97, 27.49, 24.59, 22.15, 23.50, 5.13, 4.89, 5.23, 0),
(104, '2025-05-20', '', '60043', 'COAHUILA', 'SE SIERRA DEL CARMEN', 'RAMOS ARIZPE', 21.68, 22.89, 24.17, 0.00, 21.68, 22.89, 24.17, 23.89, 25.89, 26.39, 10.19, 13.13, 9.18, 2.21, 3.00, 2.22, 0),
(105, '2025-05-20', '', '1984', 'MONTERREY', 'SERVIGAZ COLON S.A DE C.V', 'COLÓN', 20.31, 20.90, 21.71, 0.00, 20.31, 20.90, 21.71, 22.49, 26.28, 24.49, 10.73, 25.74, 12.81, 2.18, 5.38, 2.78, 0),
(106, '2025-05-20', '', '9773', 'MONTERREY', '', 'FELIX U. GOMEZ', 20.31, 21.53, 21.71, 0.00, 20.31, 21.53, 21.71, 22.49, 25.49, 24.45, 10.73, 18.39, 12.62, 2.18, 3.96, 2.74, 0);

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
(135, 'b5a69a84-3535-4596-b47e-d1f46055739e', 68);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT de la tabla `ieps`
--
ALTER TABLE `ieps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `precios_combustible`
--
ALTER TABLE `precios_combustible`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4983;

--
-- AUTO_INCREMENT de la tabla `precios_uuid`
--
ALTER TABLE `precios_uuid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `precios_uuid`
--
ALTER TABLE `precios_uuid`
  ADD CONSTRAINT `precios_uuid_ibfk_1` FOREIGN KEY (`precio_id`) REFERENCES `precios_combustible` (`id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `depurar_registros_antiguos` ON SCHEDULE EVERY 1 DAY STARTS '2025-06-19 11:23:39' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    -- Eliminar primero de precios_uuid los relacionados con precios_combustible > 3 meses
    DELETE pu
    FROM precios_uuid pu
    JOIN precios_combustible pc ON pu.precio_id = pc.id
    WHERE pc.fecha < CURDATE() - INTERVAL 3 MONTH;

    -- Luego eliminar de precios_combustible
    DELETE FROM precios_combustible
    WHERE fecha < CURDATE() - INTERVAL 3 MONTH;
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
