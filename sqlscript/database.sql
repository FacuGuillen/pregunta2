/*intentoCREATE DATABASE labanda;
USE labanda;

DROP TABLE IF EXISTS `canciones`;
CREATE TABLE `canciones` (
  `idCancion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCancion`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `canciones` VALUES (1,'cancion1',10),(2,'cancion2',12),(3,'cancion3',15);

DROP TABLE IF EXISTS `presentaciones`;
CREATE TABLE `presentaciones` (
  `idPresentacion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPresentacion`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `presentaciones` VALUES (1,'Presentacion 1','2020-06-02 22:02:14',10),(2,'Presentacion 2','2020-06-02 22:02:19',10),(3,'Presentacion 3','2020-06-02 22:02:21',10);

create table integrantes
(
    nombre      text null,
    instrumento text null,
    id          int auto_increment
        primary key
);

INSERT INTO integrantes(nombre, instrumento) VALUE ('facu', 'ukelele')*/

CREATE DATABASE IF NOT EXISTS pregunta2;
USE pregunta2;

create table tipo_usuario(
 id_tipo_usuario int auto_increment primary key,
 tipo_usuario varchar (50)
);

create table residencia(
id_residencia int auto_increment primary key,
pais varchar (50),
ciudad varchar (50)
);

DROP TABLE IF EXISTS `ranking`;
create table ranking(
id_ranking int auto_increment primary key,
puntaje_acumulado int,
cantidad_partidas int
);

create table usuarios(
id_usuario int auto_increment primary key,
nombre varchar(100),
apellido varchar(100),
sexo varchar(20),
email varchar(100),
contrasena varchar(250),
nombre_usuario varchar(50),
fecha_nacimiento date,
foto_perfil text,
    --foto_perfil varchar(50),
tipo_usuario int,
tipo_residencia int,
tipo_ranking int,

FOREIGN KEY (tipo_usuario) REFERENCES tipo_usuario(id_tipo_usuario),
FOREIGN KEY (tipo_residencia) REFERENCES residencia(id_residencia),
FOREIGN KEY (tipo_ranking) REFERENCES ranking(id_ranking)
);

