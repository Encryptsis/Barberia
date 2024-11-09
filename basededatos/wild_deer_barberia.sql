-- 1. Roles
CREATE TABLE roles (
    rol_id INT AUTO_INCREMENT PRIMARY KEY,
    rol_nombre VARCHAR(50) NOT NULL UNIQUE,
    rol_descripcion TEXT,
    rol_nivel INT NOT NULL,
    rol_activo BOOLEAN DEFAULT TRUE,
    INDEX idx_rol_nivel (rol_nivel)
);

-- 2. Usuarios
CREATE TABLE usuarios (
    usr_id INT AUTO_INCREMENT PRIMARY KEY,
    usr_username VARCHAR(50) NOT NULL UNIQUE,
    usr_password VARCHAR(255) NOT NULL,
    usr_nombre_completo VARCHAR(100) NOT NULL,
    usr_correo_electronico VARCHAR(100) NOT NULL UNIQUE,
    usr_telefono VARCHAR(20),
    usr_foto_perfil VARCHAR(255),
    usr_activo BOOLEAN DEFAULT TRUE,
    usr_rol_id INT,
    usr_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usr_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usr_recuperacion_token VARCHAR(255),
    usr_recuperacion_expira DATETIME,
    usr_ultimo_acceso DATETIME,
    FOREIGN KEY (usr_rol_id) REFERENCES roles(rol_id),
    INDEX idx_usr_rol_id (usr_rol_id)
);

-- 3. Servicios
CREATE TABLE servicios (
    srv_id INT AUTO_INCREMENT PRIMARY KEY,
    srv_nombre VARCHAR(100) NOT NULL UNIQUE,
    srv_descripcion TEXT,
    srv_precio DECIMAL(10,2) NOT NULL,
    srv_duracion TIME NOT NULL,
    srv_disponible BOOLEAN DEFAULT TRUE,
    srv_imagen VARCHAR(255),
    INDEX idx_srv_disponible (srv_disponible)
);

-- 4. Estados_Citas
CREATE TABLE estados_citas (
    estado_id INT AUTO_INCREMENT PRIMARY KEY,
    estado_nombre VARCHAR(50) NOT NULL UNIQUE,
    estado_descripcion TEXT
);

-- 5. Citas
CREATE TABLE citas (
    cta_id INT AUTO_INCREMENT PRIMARY KEY,
    cta_cliente_id INT NOT NULL,
    cta_profesional_id INT,
    cta_fecha DATE NOT NULL,
    cta_hora TIME NOT NULL,
    cta_estado_id INT NOT NULL DEFAULT 1,
    cta_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cta_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cta_cliente_id) REFERENCES usuarios(usr_id),
    FOREIGN KEY (cta_profesional_id) REFERENCES usuarios(usr_id),
    FOREIGN KEY (cta_estado_id) REFERENCES estados_citas(estado_id),
    INDEX idx_cta_estado_id (cta_estado_id)
);

-- 6. Citas_Servicios
CREATE TABLE citas_servicios (
    cta_srv_cita_id INT NOT NULL,
    cta_srv_servicio_id INT NOT NULL,
    FOREIGN KEY (cta_srv_cita_id) REFERENCES citas(cta_id) ON DELETE CASCADE,
    FOREIGN KEY (cta_srv_servicio_id) REFERENCES servicios(srv_id) ON DELETE CASCADE,
    PRIMARY KEY (cta_srv_cita_id, cta_srv_servicio_id)
);

-- 7. Usuarios_Servicios
CREATE TABLE usuarios_servicios (
    usr_srv_usuario_id INT NOT NULL,
    usr_srv_servicio_id INT NOT NULL,
    usr_srv_notas TEXT,
    FOREIGN KEY (usr_srv_usuario_id) REFERENCES usuarios(usr_id) ON DELETE CASCADE,
    FOREIGN KEY (usr_srv_servicio_id) REFERENCES servicios(srv_id) ON DELETE CASCADE,
    PRIMARY KEY (usr_srv_usuario_id, usr_srv_servicio_id)
);

-- 8. Tipos_Accion y Logs
CREATE TABLE tipos_accion (
    tipo_accion_id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_accion_descripcion VARCHAR(100),
    tipo_accion_nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    log_usuario_id INT,
    log_accion VARCHAR(255) NOT NULL,
    log_tipo_accion_id INT NOT NULL,
    log_descripcion TEXT,
    log_fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (log_usuario_id) REFERENCES usuarios(usr_id),
    FOREIGN KEY (log_tipo_accion_id) REFERENCES tipos_accion(tipo_accion_id),
    INDEX idx_log_fecha (log_fecha)
);

-- 9. Metodos_Pago y Pagos
CREATE TABLE metodos_pago (
    pago_id INT AUTO_INCREMENT PRIMARY KEY,
    pago_nombre VARCHAR(50) NOT NULL UNIQUE,
    pago_descripcion TEXT,
    pago_activo BOOLEAN DEFAULT TRUE,
    INDEX idx_pago_activo (pago_activo)
);



-- 10. Estados_Pagos
CREATE TABLE estados_pagos (
    estado_pago_id INT AUTO_INCREMENT PRIMARY KEY,
    estado_pago_nombre VARCHAR(50) NOT NULL UNIQUE,
    estado_pago_descripcion TEXT
);


CREATE TABLE pagos (
    pago_transaccion_id INT AUTO_INCREMENT PRIMARY KEY,
    pago_cita_id INT NOT NULL,
    pago_usuario_id INT NOT NULL,
    pago_metodo_id INT NOT NULL,
    pago_monto DECIMAL(10,2) NOT NULL,
    pago_fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    pago_estado_pago_id INT NOT NULL DEFAULT 1,
    FOREIGN KEY (pago_cita_id) REFERENCES citas(cta_id) ON DELETE CASCADE,
    FOREIGN KEY (pago_usuario_id) REFERENCES usuarios(usr_id),
    FOREIGN KEY (pago_metodo_id) REFERENCES metodos_pago(pago_id),
    FOREIGN KEY (pago_estado_pago_id) REFERENCES estados_pagos(estado_pago_id),
    INDEX idx_estado_pago_id (pago_estado_pago_id)
);

-- INSERCIONES --

-- Inserción de roles en la tabla `roles`
INSERT INTO roles (rol_nombre, rol_descripcion, rol_nivel, rol_activo) VALUES
('Administrador', 'Tiene acceso completo a todas las funcionalidades y configuraciones del sistema', 1, TRUE),
('Barbero', 'Encargado de realizar servicios de peluquería y cortes de cabello a los clientes', 2, TRUE),
('Facialista', 'Responsable de realizar tratamientos faciales y cuidados de la piel a los clientes', 2, TRUE),
('Recepcionista', 'Gestiona las citas y la atención al cliente en la recepción', 3, TRUE),
('Cliente', 'Usuario del sistema que puede solicitar servicios y agendar citas', 4, TRUE);

-- Inserción de servicios en la tabla `servicios`
INSERT INTO servicios (srv_nombre, srv_descripcion, srv_precio, srv_duracion, srv_disponible, srv_imagen) VALUES
('Haircut', 'Get the haircut you want with our expert stylist. Whether it''s a classic style or something unique, just bring a picture, and we''ll create the look you desire.', 45.00, '00:40:00', TRUE, NULL),
('Full Cut', 'Experience our original full haircut package: A premium grooming service that includes a precise haircut, detailed beard shaping, and eyebrow trimming.', 60.00, '01:00:00', TRUE, NULL),
('Kids', 'We welcome kids for haircuts! For their comfort and safety, we recommend parent and adult supervision for those who are a bit more active.', 35.00, '00:30:00', TRUE, NULL),
('Beard Grooming', 'We offer precise line-ups, shaping, trimming, and shaving. Enjoy a hot towel treatment and relaxing oil for a refreshing experience.', 30.00, '00:30:00', TRUE, NULL),
('Wild Cut', 'Come and live the Wild Deer experience, a service in personal care and well-being, leaving you feeling renewed, confident, and ready for any adventure.', 115.00, '01:30:00', TRUE, NULL),
('Facial', 'We apply masks rich in natural ingredients to deeply nourish and hydrate the skin. This mask, inspired by the purity of nature, returns luminosity and elasticity to your face.', 35.00, '00:30:00', TRUE, NULL),
('Line Up', 'Defining the lines of the forehead, sideburns, and nape, creating a symmetrical and polished finish.', 40.00, '00:30:00', TRUE, NULL),
('Hydrogen Oxygen', 'Is a non-invasive skin care procedure that uses a special device to deliver a mixture of hydrogen gas and oxygen to the skin for deeply cleansing pores and reducing imperfections.', 140.00, '01:00:00', TRUE, NULL);

-- Inserción de datos en la tabla `tipos_accion`
INSERT INTO tipos_accion (tipo_accion_nombre, tipo_accion_descripcion) VALUES
('Creación de Usuario', 'Registro de un nuevo usuario en el sistema'),
('Actualización de Usuario', 'Modificación de la información de un usuario existente'),
('Eliminación de Usuario', 'Eliminación de un usuario del sistema'),
('Creación de Cita', 'Programación de una nueva cita para un cliente'),
('Actualización de Cita', 'Modificación de los detalles de una cita existente'),
('Cancelación de Cita', 'Cancelación de una cita previamente programada'),
('Creación de Pago', 'Registro de un nuevo pago en el sistema'),
('Actualización de Pago', 'Modificación de la información de un pago existente'),
('Eliminación de Pago', 'Eliminación de un registro de pago del sistema'),
('Inicio de Sesión', 'Registro de un usuario iniciando sesión en el sistema'),
('Cierre de Sesión', 'Registro de un usuario cerrando sesión en el sistema');


-- Inserción de métodos de pago en la tabla `metodos_pago`
INSERT INTO metodos_pago (pago_nombre, pago_descripcion, pago_activo) VALUES
('Efectivo', 'Pago en efectivo realizado directamente en el establecimiento', TRUE),
('Tarjeta de Crédito', 'Pago mediante tarjeta de crédito (Visa, MasterCard, etc.)', TRUE),
('Tarjeta de Débito', 'Pago mediante tarjeta de débito', TRUE),
('Transferencia Bancaria', 'Pago mediante transferencia bancaria desde cualquier entidad financiera', TRUE),
('PayPal', 'Pago en línea a través de la plataforma PayPal', TRUE),
('Tarjeta de Regalo', 'Pago con tarjeta de regalo del establecimiento', FALSE);

-- Inserción de estados de pago en la tabla `estados_pagos`
INSERT INTO estados_pagos (estado_pago_nombre, estado_pago_descripcion) VALUES
('Completado', 'El pago ha sido realizado exitosamente y ha sido registrado en el sistema'),
('Pendiente', 'El pago aún no ha sido realizado o está en proceso de verificación'),
('Fallido', 'El intento de pago no fue exitoso debido a un error o rechazo'),
('Reembolsado', 'El monto del pago ha sido devuelto al cliente'),
('En Revisión', 'El pago está en revisión por posibles inconsistencias o problemas');

-- Inserción de usuarios en la tabla `usuarios`
INSERT INTO usuarios (usr_username, usr_password, usr_nombre_completo, usr_correo_electronico, usr_telefono, usr_foto_perfil, usr_activo, usr_rol_id, usr_recuperacion_token, usr_recuperacion_expira, usr_ultimo_acceso) VALUES
('admin_user', 'password123', 'Juan Pérez', 'juan.perez@example.com', '5551234567', NULL, TRUE, 1, NULL, NULL, '2024-11-09 10:00:00'),
('barber_joe', 'password456', 'José López', 'jose.lopez@example.com', '5559876543', NULL, TRUE, 2, NULL, NULL, '2024-11-09 11:00:00'),
('facialist_anna', 'password789', 'Ana García', 'ana.garcia@example.com', '5554567890', NULL, TRUE, 3, NULL, NULL, '2024-11-09 12:00:00'),
('reception_lucy', 'password321', 'Lucía Ramírez', 'lucia.ramirez@example.com', '5556543210', NULL, TRUE, 4, NULL, NULL, '2024-11-09 13:00:00'),
('client_john', 'password654', 'John Doe', 'john.doe@example.com', '5551122334', NULL, TRUE, 5, NULL, NULL, '2024-11-09 14:00:00');
