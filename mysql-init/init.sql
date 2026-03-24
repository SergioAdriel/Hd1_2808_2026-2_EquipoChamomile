-- Crear la tabla residente con índices
CREATE TABLE residente (
  nombre_usuario TEXT NOT NULL,
  letra_edificio CHAR(1) NOT NULL,
  numero_departamento INT NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE, -- Cambiado a VARCHAR y agregado UNIQUE
  telefono VARCHAR(10) NOT NULL, -- Aumentado tamaño para flexibilidad
  password CHAR(10) DEFAULT NULL,
  fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  permisos INT NOT NULL DEFAULT 2,
  PRIMARY KEY (telefono),
  INDEX idx_edificio_departamento (letra_edificio, numero_departamento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos en la tabla residente
INSERT INTO residente (nombre_usuario, letra_edificio, numero_departamento, email, telefono, password, permisos)
VALUES 
('Carlos Gómez', 'A', 102, 'carlos.gomez@correo.com', '5552345678', 'clave1234', 2),
('Ana Torres', 'C', 101, 'ana.torres@correo.com', '5553456789', 'password1', 2),
('Luis Hernández', 'B', 201, 'luis.hernandez@correo.com', '5554567890', 'qwerty123', 2),
('Sofía Martínez', 'A', 201, 'sofia.martinez@correo.com', '5555678901', 'sofia2024', 2),
('Miguel Rivera', 'C', 202, 'miguel.rivera@correo.com', '5556789012', 'mrivera10', 2),
('Laura Ramírez', 'D', 101, 'laura.ramirez@correo.com', '5557890123', 'lrpass123', 2),
('Roberto García', 'B', 102, 'roberto.garcia@correo.com', '5558901234', 'securekey', 2),
('Elena Sánchez', 'D', 201, 'elena.sanchez@correo.com', '5559012345', 'claveelena', 2),
('Andrés Pérez', 'A', 102, 'andres.perez@correo.com', '5559123456', 'andres123', 2),
('Paula Ortega', 'C', 202, 'paula.ortega@correo.com', '5551230987', 'pasegura', 2),
('Ricardo López', 'B', 201, 'ricardo.lopez@correo.com', '5559870123', 'riclopez1', 2),
('Valeria Vargas', 'A', 102, 'valeria.vargas@correo.com', '5553210987', 'valeria10', 2);

-- Mostrar tabla con consultas
SELECT * FROM residente;