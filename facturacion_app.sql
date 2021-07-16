CREATE DATABASE facturacion_app;

use facturacion_app;

CREATE TABLE `admin` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `correo` varchar(50) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE `aumentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(10) DEFAULT NULL,
  `precio_anterior` decimal(10,2) DEFAULT NULL,
  `precio_actual` decimal(10,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE `detalle` (
  `linea` int(11) NOT NULL AUTO_INCREMENT,
  `nrofactura` int(11) NOT NULL,
  `producto` varchar(10) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`linea`,`nrofactura`),
  KEY `producto` (`producto`),
  KEY `nrofactura` (`nrofactura`),
  CONSTRAINT `detalle_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`),
  CONSTRAINT `detalle_ibfk_3` FOREIGN KEY (`nrofactura`) REFERENCES `factura` (`idfactura`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

CREATE TABLE `estado_producto` (
  `id` int(1) NOT NULL,
  `nombre_estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `factura` (
  `idfactura` int(11) NOT NULL AUTO_INCREMENT,
  `cliente` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`idfactura`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

CREATE TABLE `gastos` (
  `idgasto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idgasto`),
  KEY `tipo` (`tipo`),
  CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `tipo_gasto` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

CREATE TABLE `productos` (
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `estado` int(1) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `estado` (`estado`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`estado`) REFERENCES `estado_producto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `tipo_gasto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gasto` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `admin` (`id`, `correo`, `password`) VALUES
(1, 'correo@correo.com', '$2y$10$URgFFV8sF0DBBYVZHZgD5O.q/8MSOjqhnQY9ySaPv7fV/KvyrVigS');

INSERT INTO `estado_producto` (`id`, `nombre_estado`) VALUES
(1, 'Disponible');
INSERT INTO `estado_producto` (`id`, `nombre_estado`) VALUES
(2, 'No Disponible');


INSERT INTO `tipo_gasto` (`id`, `gasto`) VALUES
(1, 'Personal');
INSERT INTO `tipo_gasto` (`id`, `gasto`) VALUES
(2, 'Mercadería');