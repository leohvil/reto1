-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-12-2014 a las 00:56:15
-- Versión del servidor: 5.5.36
-- Versión de PHP: 5.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `retodb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `poliza_ref`
--

CREATE TABLE IF NOT EXISTS `poliza_ref` (
  `poliza` varchar(8) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `importe` varchar(20) NOT NULL,
  `referencia` varchar(20) NOT NULL,
  PRIMARY KEY (`poliza`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `poliza_ref`
--

INSERT INTO `poliza_ref` (`poliza`, `nombre`, `importe`, `referencia`) VALUES
('ZZ9731', 'CHAVEZ BARRETO MARTH', '150.00', 'PROV00000ZZ990700001'),
('ZZ9745', 'ROCHA COVARRUBIAS JE', '642.56', 'PROV00000ZZ989100007'),
('ZZ9753', 'PRECIADO PALAFOX ALE', '57.23', 'PROV00000ZZ987600008'),
('ZZ9768', 'LARIOS TORREJON OFEL', '219.64', 'PROV00000ZZ987100009'),
('ZZ9825', 'RAMIREZ MEDRANO MARI', '64.26', 'PROV00000ZZ984400006'),
('ZZ9844', 'GUERRA RAMIREZ ARACE', '100.00', 'PROV00000ZZ982500005'),
('ZZ9871', 'MARTINEZ MUNOZ CLAUD', '121.03', 'PROV00000ZZ976800007'),
('ZZ9876', 'SANCHEZ FLORES GUILL', '100.00', 'PROV00000ZZ975300009'),
('ZZ9891', 'MARTIN LANDINOS GERA', '366.73', 'PROV00000ZZ974500005'),
('ZZ9907', 'MARIN MUNOZ MARIA', '358.05', 'PROV00000ZZ973100005');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;