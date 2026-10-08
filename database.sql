CREATE DATABASE IF NOT EXISTS frey_studio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE frey_studio;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS detalle_pedido, pedidos, pagos, detalle_venta, ventas, inventario, productos, categorias, proveedores, clientes, empleados, usuarios;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE usuarios (
 id_usuario INT AUTO_INCREMENT PRIMARY KEY,
 nombre_usuario VARCHAR(60) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 rol ENUM('admin','vendedor') NOT NULL DEFAULT 'vendedor',
 activo TINYINT(1) NOT NULL DEFAULT 1,
 creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE empleados (
 id_empleado INT AUTO_INCREMENT PRIMARY KEY,
 nombres VARCHAR(100) NOT NULL, apellidos VARCHAR(100) NOT NULL,
 dni VARCHAR(15) UNIQUE, cargo VARCHAR(60) NOT NULL DEFAULT 'Vendedor',
 id_usuario INT UNIQUE NULL,
 FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
) ENGINE=InnoDB;
CREATE TABLE clientes (
 id_cliente INT AUTO_INCREMENT PRIMARY KEY,
 nombres VARCHAR(100) NOT NULL, apellidos VARCHAR(100) DEFAULT '',
 dni VARCHAR(15) UNIQUE, telefono VARCHAR(25), correo VARCHAR(150),
 creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE proveedores (
 id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(150) NOT NULL, telefono VARCHAR(25), correo VARCHAR(150), direccion VARCHAR(255),
 activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;
CREATE TABLE categorias (
 id_categoria INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(80) NOT NULL UNIQUE, descripcion VARCHAR(255)
) ENGINE=InnoDB;
CREATE TABLE productos (
 id_producto INT AUTO_INCREMENT PRIMARY KEY,
 id_categoria INT NULL, id_proveedor INT NULL,
 nombre VARCHAR(150) NOT NULL, descripcion TEXT,
 precio_compra DECIMAL(10,2) NOT NULL DEFAULT 0,
 precio_venta DECIMAL(10,2) NOT NULL,
 activo TINYINT(1) NOT NULL DEFAULT 1,
 creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE SET NULL,
 FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor) ON DELETE SET NULL,
 CHECK (precio_compra >= 0), CHECK (precio_venta > 0)
) ENGINE=InnoDB;
CREATE TABLE inventario (
 id_inventario INT AUTO_INCREMENT PRIMARY KEY,
 id_producto INT NOT NULL UNIQUE, stock_actual INT NOT NULL DEFAULT 0, stock_minimo INT NOT NULL DEFAULT 3,
 actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE,
 CHECK (stock_actual >= 0), CHECK (stock_minimo >= 0)
) ENGINE=InnoDB;
CREATE TABLE ventas (
 id_venta INT AUTO_INCREMENT PRIMARY KEY, id_cliente INT NULL, id_empleado INT NOT NULL,
 fecha_venta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 subtotal DECIMAL(10,2) NOT NULL, descuento DECIMAL(10,2) NOT NULL DEFAULT 0,
 total DECIMAL(10,2) NOT NULL, estado ENUM('Finalizada','Anulada','Pendiente') NOT NULL DEFAULT 'Finalizada',
 FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE SET NULL,
 FOREIGN KEY (id_empleado) REFERENCES usuarios(id_usuario),
 CHECK (subtotal >= 0), CHECK (descuento >= 0), CHECK (total >= 0)
) ENGINE=InnoDB;
CREATE TABLE detalle_venta (
 id_detalle_venta INT AUTO_INCREMENT PRIMARY KEY, id_venta INT NOT NULL, id_producto INT NOT NULL,
 cantidad INT NOT NULL, precio_unitario DECIMAL(10,2) NOT NULL, subtotal DECIMAL(10,2) NOT NULL,
 FOREIGN KEY (id_venta) REFERENCES ventas(id_venta) ON DELETE CASCADE,
 FOREIGN KEY (id_producto) REFERENCES productos(id_producto), CHECK (cantidad > 0)
) ENGINE=InnoDB;
CREATE TABLE pagos (
 id_pago INT AUTO_INCREMENT PRIMARY KEY, id_venta INT NOT NULL, monto DECIMAL(10,2) NOT NULL,
 metodo_pago ENUM('Efectivo','Yape','Plin','Tarjeta','Transferencia') NOT NULL,
 estado_pago ENUM('Pendiente','Pagado','Reembolsado') NOT NULL DEFAULT 'Pendiente',
 fecha_pago DATETIME NULL,
 FOREIGN KEY (id_venta) REFERENCES ventas(id_venta) ON DELETE CASCADE, CHECK (monto >= 0)
) ENGINE=InnoDB;
CREATE TABLE pedidos (
 id_pedido INT AUTO_INCREMENT PRIMARY KEY, id_cliente INT NOT NULL,
 fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 estado_pedido ENUM('Recibido','En proceso','Listo','Entregado','Cancelado') NOT NULL DEFAULT 'Recibido', observaciones TEXT,
 FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
) ENGINE=InnoDB;
CREATE TABLE detalle_pedido (
 id_detalle_pedido INT AUTO_INCREMENT PRIMARY KEY, id_pedido INT NOT NULL, id_producto INT NOT NULL,
 cantidad INT NOT NULL, precio_unitario DECIMAL(10,2) NOT NULL,
 FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
 FOREIGN KEY (id_producto) REFERENCES productos(id_producto), CHECK (cantidad > 0)
) ENGINE=InnoDB;

-- Cuenta de demostración: admin / Admin123! (cambiar antes de producción).
INSERT INTO usuarios(nombre_usuario,password_hash,rol) VALUES
('admin','$2y$12$FrUJ0.5hZnOfne2uEjrBGeiD6bdgdIBiXPR1j20o118RABY2EFQpK','admin');
-- Reemplaza el hash anterior por uno generado localmente si el login no valida en tu entorno:
-- php -r "echo password_hash('Admin123!', PASSWORD_DEFAULT), PHP_EOL;"
INSERT INTO categorias(nombre,descripcion) VALUES
('Labiales','Labiales líquidos, mate y cremosos'),('Rostro','Bases, correctores y polvos'),('Ojos','Sombras, delineadores y máscaras'),('Pestañas','Pestañas postizas y adhesivos'),('Accesorios','Brochas y accesorios de maquillaje');
INSERT INTO proveedores(nombre,telefono,correo,direccion) VALUES
('Proveedor de demostración','999999999','proveedor@example.com','Dirección de ejemplo');
INSERT INTO productos(id_categoria,id_proveedor,nombre,descripcion,precio_compra,precio_venta) VALUES
(1,1,'Labial mate','Producto de muestra; cambia por tu catálogo real',8.00,15.00),
(2,1,'Polvo compacto','Producto de muestra; cambia por tu catálogo real',12.00,22.00),
(3,1,'Paleta de sombras','Producto de muestra; cambia por tu catálogo real',18.00,32.00),
(4,1,'Pestañas postizas','Producto de muestra; cambia por tu catálogo real',5.00,12.00);
INSERT INTO inventario(id_producto,stock_actual,stock_minimo) VALUES (1,20,5),(2,12,3),(3,8,2),(4,15,4);
