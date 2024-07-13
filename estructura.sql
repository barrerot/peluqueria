-- Estructura de tabla para la tabla `usuarios`
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `telefono` varchar(20),
  `direccion` varchar(255),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `negocios`
CREATE TABLE `negocios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `telefono` varchar(20),
  `email` varchar(255),
  `direccion` varchar(255),
  `tipo_negocio` varchar(255),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `negocios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `clientes`
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `negocio_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255),
  `telefono` varchar(20),
  `direccion` varchar(255),
  `fecha_creacion` datetime,
  `cumpleanos` date,
  PRIMARY KEY (`id`),
  KEY `negocio_id` (`negocio_id`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`negocio_id`) REFERENCES `negocios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `horarios`
CREATE TABLE `horarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `negocio_id` int(11) NOT NULL,
  `dia_semana` int(11) NOT NULL,
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `negocio_id` (`negocio_id`),
  CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`negocio_id`) REFERENCES `negocios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `servicios`
CREATE TABLE `servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `negocio_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `duracion` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `negocio_id` (`negocio_id`),
  CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`negocio_id`) REFERENCES `negocios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `citas`
CREATE TABLE `citas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `negocio_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `negocio_id` (`negocio_id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`negocio_id`) REFERENCES `negocios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- Estructura de tabla para la tabla `mensajes`
CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `negocio_id` int(11) NOT NULL,
  `remitente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_envio` datetime NOT NULL,
  `estado` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `negocio_id` (`negocio_id`),
  CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`negocio_id`) REFERENCES `negocios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
