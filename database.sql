-- Base de datos completa para Yogurt Artesanal San Francisco
CREATE DATABASE yogurt_san_francisco;
USE yogurt_san_francisco;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    direccion TEXT,
    tipo ENUM('cliente', 'admin') DEFAULT 'cliente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_nacimiento DATE,
    activo BOOLEAN DEFAULT TRUE,
    primera_compra BOOLEAN DEFAULT TRUE,
    envases_devueltos INT DEFAULT 0
);

-- Tabla de categorías
CREATE TABLE categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    categoria_id INT,
    stock INT DEFAULT 0,
    imagen VARCHAR(255),
    es_personalizable BOOLEAN DEFAULT FALSE,
    destacado BOOLEAN DEFAULT FALSE,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Tabla de carrito
CREATE TABLE carrito (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT DEFAULT 1,
    personalizaciones JSON,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

-- Tabla de pedidos
CREATE TABLE pedidos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'confirmado', 'preparando', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
    direccion_entrega TEXT NOT NULL,
    telefono_contacto VARCHAR(20),
    notas TEXT,
    descuento_aplicado DECIMAL(10,2) DEFAULT 0,
    codigo_promocion VARCHAR(50),
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega DATETIME,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de detalle de pedidos
CREATE TABLE detalle_pedidos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    personalizaciones JSON,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Tabla de promociones
CREATE TABLE promociones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    tipo ENUM('porcentaje', 'monto_fijo') NOT NULL,
    valor_descuento DECIMAL(10,2) NOT NULL,
    codigo VARCHAR(50) UNIQUE,
    condicion_minima DECIMAL(10,2) DEFAULT 0,
    fecha_inicio DATE,
    fecha_fin DATE,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de contactos
CREATE TABLE contactos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    mensaje TEXT NOT NULL,
    leido BOOLEAN DEFAULT FALSE,
    fecha_contacto TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de favoritos
CREATE TABLE favoritos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    producto_id INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (usuario_id, producto_id)
);

-- Tabla de direcciones de usuarios
CREATE TABLE direcciones_usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    nombre_direccion VARCHAR(100) NOT NULL,
    direccion_completa TEXT NOT NULL,
    ciudad VARCHAR(100),
    codigo_postal VARCHAR(20),
    telefono VARCHAR(20),
    es_principal BOOLEAN DEFAULT FALSE,
    activa BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Insertar usuario administrador por defecto
INSERT INTO usuarios (nombre, email, password, tipo) VALUES 
('Administrador', 'admin@yogurtsanfrancisco.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insertar categorías por defecto
INSERT INTO categorias (nombre, descripcion, imagen) VALUES 
('Yogures Griegos', 'Yogures griegos cremosos y naturales', 'yogur-griego.jpg'),
('Yogures con Frutas', 'Yogures con frutas frescas y naturales', 'yogur-frutas.jpg'),
('Postres de Yogur', 'Deliciosos postres elaborados con yogur', 'postres-yogur.jpg'),
('Tortas Artesanales', 'Tortas caseras con ingredientes naturales', 'tortas.jpg'),
('Productos Personalizados', 'Productos hechos a tu medida', 'personalizados.jpg');

-- Insertar productos por defecto
INSERT INTO productos (nombre, descripcion, precio, categoria_id, stock, es_personalizable, destacado) VALUES 
('Yogur Griego Natural 500ml', 'Yogur griego cremoso 100% natural, sin azúcar añadida', 12000, 1, 50, TRUE, TRUE),
('Yogur con Fresa 400ml', 'Yogur cremoso con trozos de fresa fresca', 10000, 2, 30, TRUE, TRUE),
('Yogur con Mora 400ml', 'Yogur natural con mora fresca y endulzante natural', 10000, 2, 25, TRUE, FALSE),
('Postre de Yogur Griego con Miel', 'Delicioso postre de yogur griego con miel de abeja', 8000, 3, 20, FALSE, TRUE),
('Torta de Zanahoria Mediana', 'Torta artesanal de zanahoria con ingredientes naturales', 35000, 4, 10, TRUE, TRUE),
('Yogur Personalizado', 'Crea tu yogur ideal eligiendo sabor, endulzante y dulzor', 15000, 5, 100, TRUE, FALSE),
('Torta Personalizada', 'Torta hecha a tu medida con tus sabores favoritos', 35000, 5, 20, TRUE, FALSE);

-- Insertar promociones por defecto
INSERT INTO promociones (nombre, descripcion, tipo, valor_descuento, codigo, condicion_minima, fecha_inicio, fecha_fin) VALUES 
('Primera Compra', '15% de descuento en tu primera compra', 'porcentaje', 15.00, 'PRIMERA15', 30000, '2024-01-01', '2024-12-31'),
('Combo Familiar', '20% de descuento en compras superiores a $80.000', 'porcentaje', 20.00, 'FAMILIA20', 80000, '2024-01-01', '2024-12-31'),
('Envase Devuelto', '$2.000 de descuento por envase devuelto', 'monto_fijo', 2000.00, 'ENVASE2000', 0, '2024-01-01', '2024-12-31');
