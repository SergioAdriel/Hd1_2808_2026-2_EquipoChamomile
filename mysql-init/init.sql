-- Crear base de datos
CREATE DATABASE IF NOT EXISTS pokedex_app;
USE pokedex_app;

-- =========================
-- TABLA USUARIOS
-- =========================
DROP TABLE IF EXISTS combates;
DROP TABLE IF EXISTS equipo;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(15) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================
-- TABLA EQUIPO
-- =========================
CREATE TABLE equipo (
    id_equipo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_pokemon INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_equipo (id_usuario, id_pokemon),
    
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- TRIGGER PARA LIMITAR A 6 POKÉMONES
-- =========================
DELIMITER $$
CREATE TRIGGER limitar_pokemones BEFORE INSERT ON equipo
FOR EACH ROW
BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total FROM equipo WHERE id_usuario = NEW.id_usuario;
    IF total >= 6 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Un entrenador no puede tener más de 6 Pokémon.';
    END IF;
END$$
DELIMITER ;

-- =========================
-- TABLA COMBATES
-- =========================
CREATE TABLE combates (
    id_combate INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    victorias INT DEFAULT 0,
    derrotas INT DEFAULT 0,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- DATOS DE PRUEBA
-- =========================

-- Entrenadores
INSERT INTO usuarios (nombre, telefono, contrasena)
VALUES 
('Ash Ketchum', '5512345678', 'pikachu123'),
('Misty Waterflower', '5512345679', 'staryu123'),
('Brock Stone', '5512345680', 'onix123');

-- Equipo Pokémon
-- Ash (equipo completo 6)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(1, 25), -- Pikachu
(1, 6),  -- Charizard
(1, 9),  -- Blastoise
(1, 1),  -- Bulbasaur
(1, 4),  -- Charmander
(1, 7);  -- Squirtle

-- Misty (equipo completo 6)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(2, 120), -- Staryu
(2, 121), -- Starmie
(2, 54),  -- Psyduck
(2, 116), -- Horsea
(2, 118), -- Goldeen
(2, 119); -- Seaking

-- Brock (solo 3 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(3, 95),  -- Onix
(3, 74),  -- Geodude
(3, 111); -- Rhyhorn

-- Combates iniciales
INSERT INTO combates (id_usuario, victorias, derrotas) VALUES
(1, 0, 0),
(2, 0, 0),
(3, 0, 0);


-- La idea es que cada vez que un usuario gane o pierda un combate, se actualice su registro en la tabla "combates" para reflejar su historial.
-- No puedes entrar a combate si no tiene tu equipo completo de 6 Pokémon.
-- No puedes tener más de 6 Pokémon en tu equipo.
-- Puedes eliminar un Pokémon de tu equipo, pero no puedes agregar otro si ya tienes 6.
-- Puedes eliminar tu cuenta, lo que eliminará automáticamente tu equipo y tu historial de combates gracias a las claves foráneas con ON DELETE CASCADE.
-- No puedes tener el mismo Pokémon más de una vez en tu equipo gracias a la restricción UNIQUE en la tabla "equipo".