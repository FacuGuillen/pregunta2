-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         11.7.2-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para pregunta2
CREATE DATABASE IF NOT EXISTS `pregunta2` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `pregunta2`;

-- Volcando estructura para tabla pregunta2.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(15) DEFAULT NULL,
  `color` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla pregunta2.categoria: ~6 rows (aproximadamente)
INSERT INTO `categoria` (`id_categoria`, `categoria`, `color`) VALUES
	(1, 'Historia', 'marron'),
	(2, 'Deportes', 'azul'),
	(3, 'Ciencia', 'verde'),
	(4, 'Cultura', 'naranja'),
	(5, 'Geografía', 'gris'),
	(6, 'Arte', 'rojo');

-- Volcando estructura para tabla pregunta2.partidas
CREATE TABLE IF NOT EXISTS `partidas` (
  `id_partidas` int(11) NOT NULL AUTO_INCREMENT,
  `puntaje` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_partidas`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla pregunta2.partidas: ~7 rows (aproximadamente)
INSERT INTO `partidas` (`id_partidas`, `puntaje`) VALUES
	(1, 0),
	(2, 0),
	(3, 1),
	(4, 2),
	(5, 0),
	(6, 0),
	(7, 2);

--20/6 agregue campo fecha a la partida DEFAULT CURRENT_TIMESTAMP-> comando para poner la fecha actual por defecto
ALTER TABLE `partidas`
    ADD COLUMN `fecha` DATE DEFAULT CURRENT_TIMESTAMP;

-- Volcando estructura para tabla pregunta2.partidas_usuarios
CREATE TABLE IF NOT EXISTS `partidas_usuarios` (
  `id_partidas_usuarios` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_partidas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_partidas_usuarios`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_partidas` (`id_partidas`),
  CONSTRAINT `partidas_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `partidas_usuarios_ibfk_2` FOREIGN KEY (`id_partidas`) REFERENCES `partidas` (`id_partidas`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla pregunta2.partidas_usuarios: ~2 rows (aproximadamente)
INSERT INTO `partidas_usuarios` (`id_partidas_usuarios`, `id_usuario`, `id_partidas`) VALUES
	(1, 3, 1),
	(2, 3, 4);

-- Volcando estructura para tabla pregunta2.pregunta
CREATE TABLE IF NOT EXISTS `pregunta` (
  `id_pregunta` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta` text NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pregunta`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `pregunta_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla pregunta2.pregunta: ~20 rows (aproximadamente)
INSERT INTO `pregunta` (`id_pregunta`, `pregunta`, `id_categoria`) VALUES
	(1, '¿En qué año comenzó la Segunda Guerra Mundial?', 1),
	(2, '¿Quién ganó la Copa Mundial de Fútbol 2018?', 2),
	(3, '¿Cuál es el elemento químico con símbolo O?', 3),
	(4, '¿Quién pintó la Mona Lisa?', 6),
	(5, '¿Cuál es la capital de Chile?', 5),
	(6, '¿Qué cultura construyó las pirámides de Egipto?', 4),
	(7, '¿Cuál es la fórmula química del agua?', 3),
	(8, '¿Quién es conocido como el padre del psicoanálisis?', 4),
	(9, '¿En qué continente se encuentra la selva amazónica?', 5),
	(10, '¿Cuántos jugadores tiene un equipo de fútbol en el campo?', 2),
	(11, '¿Quién escribió "Cien años de soledad"?', 4),
	(12, '¿Qué instrumento tiene teclas blancas y negras?', 6),
	(13, '¿Cuál es la capital de Australia?', 5),
	(14, '¿Quién escribió "Don Quijote de la Mancha"?', 4),
	(15, '¿Cuál es el planeta más cercano al Sol?', 3),
	(16, '¿En qué deporte se utiliza un balón ovalado?', 2),
	(17, '¿Qué año se firmó la Declaración de Independencia de Estados Unidos?', 1),
	(18, '¿Quién compuso la Novena Sinfonía?', 6),
	(19, '¿Cuál es el gas más abundante en la atmósfera terrestre?', 3),
	(20, '¿Quién fue el primer presidente de Estados Unidos?', 1);

-- Volcando estructura para tabla pregunta2.pregunta_usuarios
CREATE TABLE IF NOT EXISTS `pregunta_usuarios` (
  `id_pregunta_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_pregunta` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pregunta_usuario`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_pregunta` (`id_pregunta`),
  CONSTRAINT `pregunta_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `pregunta_usuarios_ibfk_2` FOREIGN KEY (`id_pregunta`) REFERENCES `pregunta` (`id_pregunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla pregunta2.pregunta_usuarios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla pregunta2.ranking
CREATE TABLE IF NOT EXISTS `ranking` (
  `id_ranking` int(11) NOT NULL AUTO_INCREMENT,
  `puntaje_acumulado` int(11) DEFAULT NULL,
  `cantidad_partidas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_ranking`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla pregunta2.ranking: ~2 rows (aproximadamente)
INSERT INTO `ranking` (`id_ranking`, `puntaje_acumulado`, `cantidad_partidas`) VALUES
	(1, 0, 0),
	(2, 10, 2);

-- Volcando estructura para tabla pregunta2.residencia
CREATE TABLE IF NOT EXISTS `residencia` (
  `id_residencia` int(11) NOT NULL AUTO_INCREMENT,
  `pais` varchar(50) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_residencia`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla pregunta2.residencia: ~2 rows (aproximadamente)
INSERT INTO `residencia` (`id_residencia`, `pais`, `ciudad`) VALUES
	(1, 'Argentina', 'Buenos Aires'),
	(2, 'Chile', 'Santiago');

-- Volcando estructura para tabla pregunta2.respuesta
CREATE TABLE IF NOT EXISTS `respuesta` (
  `id_respuesta` int(11) NOT NULL AUTO_INCREMENT,
  `respuesta` varchar(50) DEFAULT NULL,
  `es_correcta` tinyint(1) DEFAULT NULL,
  `id_pregunta` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_respuesta`),
  KEY `id_pregunta` (`id_pregunta`),
  CONSTRAINT `respuesta_ibfk_1` FOREIGN KEY (`id_pregunta`) REFERENCES `pregunta` (`id_pregunta`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla pregunta2.respuesta: ~80 rows (aproximadamente)
INSERT INTO `respuesta` (`id_respuesta`, `respuesta`, `es_correcta`, `id_pregunta`) VALUES
	(1, '1939', 1, 1),
	(2, '1914', 0, 1),
	(3, '1945', 0, 1),
	(4, '1929', 0, 1),
	(5, 'Francia', 1, 2),
	(6, 'Brasil', 0, 2),
	(7, 'Alemania', 0, 2),
	(8, 'Argentina', 0, 2),
	(9, 'Oxígeno', 1, 3),
	(10, 'Oro', 0, 3),
	(11, 'Hidrógeno', 0, 3),
	(12, 'Osmio', 0, 3),
	(13, 'Leonardo da Vinci', 1, 4),
	(14, 'Pablo Picasso', 0, 4),
	(15, 'Vincent Van Gogh', 0, 4),
	(16, 'Michelangelo', 0, 4),
	(17, 'Santiago', 1, 5),
	(18, 'Buenos Aires', 0, 5),
	(19, 'Lima', 0, 5),
	(20, 'La Paz', 0, 5),
	(21, 'Egipcia', 1, 6),
	(22, 'Griega', 0, 6),
	(23, 'Romana', 0, 6),
	(24, 'Maya', 0, 6),
	(25, 'H2O', 1, 7),
	(26, 'CO2', 0, 7),
	(27, 'NaCl', 0, 7),
	(28, 'O2', 0, 7),
	(29, 'Sigmund Freud', 1, 8),
	(30, 'Carl Jung', 0, 8),
	(31, 'Ivan Pavlov', 0, 8),
	(32, 'Alfred Adler', 0, 8),
	(33, 'Sudamérica', 1, 9),
	(34, 'África', 0, 9),
	(35, 'Asia', 0, 9),
	(36, 'Europa', 0, 9),
	(37, '11', 1, 10),
	(38, '9', 0, 10),
	(39, '7', 0, 10),
	(40, '15', 0, 10),
	(41, 'Gabriel García Márquez', 1, 11),
	(42, 'Mario Vargas Llosa', 0, 11),
	(43, 'Pablo Neruda', 0, 11),
	(44, 'Julio Cortázar', 0, 11),
	(45, 'Piano', 1, 12),
	(46, 'Guitarra', 0, 12),
	(47, 'Violín', 0, 12),
	(48, 'Flauta', 0, 12),
	(49, 'Canberra', 1, 13),
	(50, 'Sídney', 0, 13),
	(51, 'Melbourne', 0, 13),
	(52, 'Brisbane', 0, 13),
	(53, 'Miguel de Cervantes', 1, 14),
	(54, 'Gabriel García Márquez', 0, 14),
	(55, 'William Shakespeare', 0, 14),
	(56, 'Jorge Luis Borges', 0, 14),
	(57, 'Mercurio', 1, 15),
	(58, 'Venus', 0, 15),
	(59, 'Marte', 0, 15),
	(60, 'Júpiter', 0, 15),
	(61, 'Rugby', 1, 16),
	(62, 'Fútbol', 0, 16),
	(63, 'Baloncesto', 0, 16),
	(64, 'Voleibol', 0, 16),
	(65, '1776', 1, 17),
	(66, '1492', 0, 17),
	(67, '1810', 0, 17),
	(68, '1914', 0, 17),
	(69, 'Ludwig van Beethoven', 1, 18),
	(70, 'Wolfgang Amadeus Mozart', 0, 18),
	(71, 'Johann Sebastian Bach', 0, 18),
	(72, 'Frédéric Chopin', 0, 18),
	(73, 'Nitrógeno', 1, 19),
	(74, 'Oxígeno', 0, 19),
	(75, 'Dióxido de carbono', 0, 19),
	(76, 'Argón', 0, 19),
	(77, 'George Washington', 1, 20),
	(78, 'Thomas Jefferson', 0, 20),
	(79, 'Abraham Lincoln', 0, 20),
	(80, 'John Adams', 0, 20);

-- Volcando estructura para tabla pregunta2.tipo_usuario
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `id_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_usuario` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla pregunta2.tipo_usuario: ~3 rows (aproximadamente)
INSERT INTO `tipo_usuario` (`id_tipo_usuario`, `tipo_usuario`) VALUES
	(1, 'Jugador'),
	(2, 'Editor'),
	(3, 'Administrador');

-- Volcando estructura para tabla pregunta2.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `sexo` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contrasena` varchar(250) DEFAULT NULL,
  `nombre_usuario` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `foto_perfil` text DEFAULT NULL,
  `tipo_usuario` int(11) DEFAULT NULL,
  `tipo_residencia` int(11) DEFAULT NULL,
  `tipo_ranking` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `tipo_usuario` (`tipo_usuario`),
  KEY `tipo_residencia` (`tipo_residencia`),
  KEY `tipo_ranking` (`tipo_ranking`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`tipo_usuario`) REFERENCES `tipo_usuario` (`id_tipo_usuario`),
  CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`tipo_residencia`) REFERENCES `residencia` (`id_residencia`),
  CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`tipo_ranking`) REFERENCES `ranking` (`id_ranking`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla pregunta2.usuarios: ~3 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `sexo`, `email`, `contrasena`, `nombre_usuario`, `fecha_nacimiento`, `foto_perfil`, `tipo_usuario`, `tipo_residencia`, `tipo_ranking`) VALUES
	(1, 'Juan', 'Pérez', 'Masculino', 'juan@example.com', 'clave123', 'juanito', '2000-05-15', 'foto_juan.jpg', 1, 1, 1),
	(2, 'Ana', 'García', 'Femenino', 'ana@example.com', 'clave456', 'anita', '1998-08-22', 'foto_ana.jpg', 1, 2, 2),
	(3, 'Lautaro', 'Rossi', 'masculinmo', 'lautarorossi99@gmail.com', '$2y$10$egKhe3mdSjYGM3/uJ0mC/eYjDUVIrWXunV6yDWI0WKuCPjwsas/o.', 'poli', '2025-06-16', 'aaa', NULL, NULL, NULL);

--tabla de preguntas,usuario y si respondio bien o respondio mal
CREATE TABLE IF NOT EXISTS  `preguntas_usuarios_respuestas`(
    `id_preguntas_usuarios_respuestas` int(11) NOT NULL AUTO_INCREMENT,
    `id_usuario` int(11) DEFAULT NULL,
    `id_preguntas` int(11) DEFAULT NULL,
    `respuesta_correcta`  BOOLEAN,
    PRIMARY KEY (`id_preguntas_usuarios_respuestas`),
    CONSTRAINT `pregunta_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
    CONSTRAINT `pregunta_usuarios_ibfk_2` FOREIGN KEY (`id_preguntas`) REFERENCES `pregunta` (`id_pregunta`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
-- tabla qr que se relaciona con usuario
ALTER TABLE usuarios ADD UNIQUE (nombre_usuario);

CREATE TABLE qr_usuarios (
                             id_qr_usuario int AUTO_INCREMENT PRIMARY KEY,
                             nombre_usuario varchar(50),
                             qr_path varchar(50),
                             qr_url text,
                             KEY nombre_usuario(nombre_usuario),
                             CONSTRAINT qr_usuarios_1 FOREIGN KEY (nombre_usuario) REFERENCES usuarios(nombre_usuario)
);


/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
