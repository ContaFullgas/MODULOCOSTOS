-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-05-2025 a las 23:47:57
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
(21, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-04-28', NULL, 19.05, NULL),
(22, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-04-23', NULL, 19.27, NULL),
(23, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-04-28', NULL, NULL, 20.55),
(24, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-04-28', NULL, 19.05, NULL),
(25, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-04-28', NULL, NULL, 20.55),
(26, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-03-07', NULL, 17.58, NULL),
(27, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-03-07', NULL, 17.58, NULL),
(28, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-02-10', NULL, 17.66, NULL),
(29, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-02-10', NULL, NULL, 18.89),
(30, 'SERVICIOS ECOLOGICOS DE CIUDAD DEL CARMEN', 'RAMOS ARIZPE', '2025-02-10', NULL, NULL, 18.89);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `precios_combustible`
--
ALTER TABLE `precios_combustible`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `precios_combustible`
--
ALTER TABLE `precios_combustible`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
