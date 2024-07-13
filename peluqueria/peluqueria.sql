-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-07-2024 a las 13:13:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Estructura de tabla para la tabla `citas`
CREATE TABLE `citas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `citas`
INSERT INTO `citas` (`id`, `title`, `start`, `end`) VALUES
(12, 'carlos', '2024-07-08 11:00:00', '2024-07-08 12:30:00');

-- Estructura de tabla para la tabla `horarios`
CREATE TABLE `horarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dia_semana` int(11) NOT NULL,
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `horarios`
INSERT INTO `horarios` (`id`, `dia_semana`, `hora_apertura`, `hora_cierre`) VALUES
(1, 1, '09:30:00', '14:30:00'),
(2, 2, '09:30:00', '14:30:00'),
(3, 3, '09:30:00', '14:30:00'),
(4, 4, '09:30:00', '14:30:00'),
(5, 5, '09:30:00', '14:30:00'),
(6, 6, '09:30:00', '14:30:00');

-- Estructura de tabla para la tabla `servicios`
CREATE TABLE `servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `duracion` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `servicios`
INSERT INTO `servicios` (`id`, `nombre`, `duracion`, `precio`) VALUES
(1, 'Corte', 45, 30.00),
(2, 'Corte y Tinte', 90, 50.00),
(3, 'Peinado', 30, 25.00);

-- Estructura de tabla para la tabla `citas_servicios`
CREATE TABLE `citas_servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cita_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cita_id` (`cita_id`),
  KEY `servicio_id` (`servicio_id`),
  CONSTRAINT `citas_servicios_ibfk_1` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `citas_servicios_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcado de datos para la tabla `citas_servicios`
INSERT INTO `citas_servicios` (`cita_id`, `servicio_id`) VALUES
(12, 2);

COMMIT;
