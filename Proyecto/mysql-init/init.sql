-- =========================
-- CREAR BASE DE DATOS
-- =========================
CREATE DATABASE IF NOT EXISTS pokedex_app;
USE pokedex_app;

-- =========================
-- LIMPIAR TABLAS
-- =========================
DROP TABLE IF EXISTS combates;
DROP TABLE IF EXISTS equipo;
DROP TABLE IF EXISTS usuarios;

-- =========================
-- TABLA USUARIOS
-- =========================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(15) NOT NULL UNIQUE,
    contrasena VARCHAR(100) NOT NULL, -- texto plano ahora
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
-- TRIGGER LIMITE 6 POKEMON
-- =========================
DELIMITER $$

CREATE TRIGGER limitar_pokemones
BEFORE INSERT ON equipo
FOR EACH ROW
BEGIN
    DECLARE total INT;

    SELECT COUNT(*) INTO total
    FROM equipo
    WHERE id_usuario = NEW.id_usuario;

    IF total >= 6 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Máximo 6 Pokémon por entrenador';
    END IF;
END$$

DELIMITER ;

-- =========================
-- TRIGGER CREAR COMBATE AUTOMÁTICO
-- =========================
DELIMITER $$

CREATE TRIGGER crear_combate_usuario
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO combates (id_usuario, victorias, derrotas)
    VALUES (NEW.id_usuario, 0, 0);
END$$

DELIMITER ;

-- =========================
-- DATOS DE PRUEBA (SIN HASH 🔥)
-- =========================

INSERT INTO usuarios (nombre, telefono, contrasena)
VALUES 
('Ash Ketchum', '5512345678', 'pikachu123'),
('Misty Waterflower', '5512345679', 'staryu123'),
('Brock Stone', '5512345680', 'onix123');
-- =========================
-- EQUIPOS
-- =========================

-- Ash (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(1, 25),
(1, 6),
(1, 9),
(1, 1),
(1, 4),
(1, 7);

-- Misty (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(2, 120),
(2, 121),
(2, 54),
(2, 116),
(2, 118),
(2, 119);

-- Brock (3 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(3, 95),
(3, 74),
(3, 111);

-- Usuario 4 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(4, 33),
(4, 87),
(4, 12),
(4, 140),
(4, 56),
(4, 19);

-- Usuario 5 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(5, 72),
(5, 5),
(5, 134),
(5, 44),
(5, 98),
(5, 150);

-- Usuario 6 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(6, 3),
(6, 65),
(6, 23),
(6, 101),
(6, 77),
(6, 142);

-- Usuario 7 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(7, 88),
(7, 2),
(7, 145),
(7, 60),
(7, 39),
(7, 109);

-- Usuario 8 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(8, 18),
(8, 93),
(8, 131),
(8, 47),
(8, 81),
(8, 6);

-- Usuario 9 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(9, 52),
(9, 119),
(9, 27),
(9, 100),
(9, 75),
(9, 11);

-- Usuario 10 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(10, 14),
(10, 121),
(10, 66),
(10, 90),
(10, 3),
(10, 135);

-- Usuario 11 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(11, 42),
(11, 8),
(11, 97),
(11, 126),
(11, 55),
(11, 71);

-- Usuario 12 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(12, 29),
(12, 103),
(12, 17),
(12, 68),
(12, 149),
(12, 84);

-- Usuario 13 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(13, 91),
(13, 63),
(13, 122),
(13, 40),
(13, 6),
(13, 115);

-- Usuario 14 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(14, 7),
(14, 111),
(14, 25),
(14, 89),
(14, 54),
(14, 132);

-- Usuario 15 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(15, 146),
(15, 13),
(15, 76),
(15, 34),
(15, 98),
(15, 57);

-- Usuario 16 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(16, 80),
(16, 22),
(16, 140),
(16, 5),
(16, 102),
(16, 61);

-- Usuario 17 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(17, 39),
(17, 150),
(17, 73),
(17, 11),
(17, 94),
(17, 2);

-- Usuario 18 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(18, 67),
(18, 121),
(18, 44),
(18, 85),
(18, 9),
(18, 134);

-- Usuario 19 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(19, 28),
(19, 76),
(19, 112),
(19, 35),
(19, 140),
(19, 53);

-- Usuario 20 (6 Pokémon)
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(20, 99),
(20, 6),
(20, 120),
(20, 48),
(20, 81),
(20, 15);


-- Usuario 21
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(21, 44),(21, 7),(21, 132),(21, 95),(21, 16),(21, 120);

-- Usuario 22
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(22, 3),(22, 88),(22, 57),(22, 101),(22, 12),(22, 146);

-- Usuario 23
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(23, 73),(23, 25),(23, 90),(23, 134),(23, 41),(23, 6);

-- Usuario 24
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(24, 81),(24, 2),(24, 140),(24, 66),(24, 19),(24, 103);

-- Usuario 25
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(25, 11),(25, 55),(25, 149),(25, 34),(25, 78),(25, 92);

-- Usuario 26
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(26, 121),(26, 5),(26, 63),(26, 87),(26, 142),(26, 27);

-- Usuario 27
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(27, 48),(27, 99),(27, 14),(27, 131),(27, 76),(27, 38);

-- Usuario 28
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(28, 22),(28, 65),(28, 110),(28, 33),(28, 89),(28, 4);

-- Usuario 29
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(29, 54),(29, 136),(29, 8),(29, 72),(29, 97),(29, 118);

-- Usuario 30
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(30, 45),(30, 112),(30, 18),(30, 60),(30, 135),(30, 26);

-- Usuario 31
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(31, 93),(31, 13),(31, 58),(31, 141),(31, 36),(31, 80);

-- Usuario 32
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(32, 21),(32, 104),(32, 67),(32, 9),(32, 150),(32, 52);

-- Usuario 33
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(33, 39),(33, 82),(33, 1),(33, 96),(33, 47),(33, 119);

-- Usuario 34
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(34, 68),(34, 123),(34, 30),(34, 85),(34, 17),(34, 144);

-- Usuario 35
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(35, 10),(35, 59),(35, 137),(35, 42),(35, 77),(35, 111);

-- Usuario 36
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(36, 6),(36, 100),(36, 24),(36, 70),(36, 133),(36, 49);

-- Usuario 37
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(37, 91),(37, 15),(37, 63),(37, 122),(37, 35),(37, 148);

-- Usuario 38
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(38, 84),(38, 2),(38, 56),(38, 109),(38, 18),(38, 130);

-- Usuario 39
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(39, 28),(39, 75),(39, 140),(39, 5),(39, 97),(39, 44);

-- Usuario 40
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(40, 132),(40, 8),(40, 66),(40, 115),(40, 23),(40, 90);

-- Usuario 41
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(41, 12),(41, 134),(41, 39),(41, 81),(41, 2),(41, 107);

-- Usuario 42
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(42, 73),(42, 52),(42, 98),(42, 140),(42, 11),(42, 67);

-- Usuario 43
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(43, 25),(43, 120),(43, 64),(43, 3),(43, 88),(43, 142);

-- Usuario 44
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(44, 47),(44, 101),(44, 19),(44, 55),(44, 131),(44, 6);

-- Usuario 45
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(45, 34),(45, 78),(45, 149),(45, 92),(45, 10),(45, 63);

-- Usuario 46
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(46, 87),(46, 27),(46, 121),(46, 5),(46, 99),(46, 38);

-- Usuario 47
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(47, 14),(47, 76),(47, 48),(47, 131),(47, 89),(47, 22);

-- Usuario 48
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(48, 65),(48, 110),(48, 33),(48, 4),(48, 54),(48, 136);

-- Usuario 49
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(49, 8),(49, 72),(49, 118),(49, 45),(49, 112),(49, 18);

-- Usuario 50
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(50, 60),(50, 135),(50, 26),(50, 93),(50, 13),(50, 58);

-- Usuario 51
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(51, 141),(51, 36),(51, 80),(51, 21),(51, 104),(51, 67);

-- Usuario 52
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(52, 9),(52, 150),(52, 52),(52, 39),(52, 82),(52, 1);

-- Usuario 53
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(53, 96),(53, 47),(53, 119),(53, 68),(53, 123),(53, 30);

-- Usuario 54
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(54, 85),(54, 17),(54, 144),(54, 10),(54, 59),(54, 137);

-- Usuario 55
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(55, 42),(55, 77),(55, 111),(55, 6),(55, 100),(55, 24);

-- Usuario 56
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(56, 70),(56, 133),(56, 49),(56, 91),(56, 15),(56, 63);

-- Usuario 57
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(57, 122),(57, 35),(57, 148),(57, 84),(57, 2),(57, 56);

-- Usuario 58
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(58, 109),(58, 18),(58, 130),(58, 28),(58, 75),(58, 140);

-- Usuario 59
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(59, 5),(59, 97),(59, 44),(59, 132),(59, 8),(59, 66);

-- Usuario 60
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(60, 115),(60, 23),(60, 90),(60, 12),(60, 134),(60, 39);

-- Usuario 61
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(61, 81),(61, 2),(61, 107),(61, 73),(61, 52),(61, 98);

-- Usuario 62
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(62, 140),(62, 11),(62, 67),(62, 25),(62, 120),(62, 64);

-- Usuario 63
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(63, 3),(63, 88),(63, 142),(63, 47),(63, 101),(63, 19);

-- Usuario 64
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(64, 55),(64, 131),(64, 6),(64, 34),(64, 78),(64, 149);

-- Usuario 65
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(65, 92),(65, 10),(65, 63),(65, 87),(65, 27),(65, 121);

-- Usuario 66
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(66, 5),(66, 99),(66, 38),(66, 14),(66, 76),(66, 48);

-- Usuario 67
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(67, 131),(67, 89),(67, 22),(67, 65),(67, 110),(67, 33);

-- Usuario 68
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(68, 4),(68, 54),(68, 136),(68, 8),(68, 72),(68, 118);

-- Usuario 69
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(69, 45),(69, 112),(69, 18),(69, 60),(69, 135),(69, 26);

-- Usuario 70
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(70, 93),(70, 13),(70, 58),(70, 141),(70, 36),(70, 80);

-- Usuario 71
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(71, 21),(71, 104),(71, 67),(71, 9),(71, 150),(71, 52);

-- Usuario 72
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(72, 39),(72, 82),(72, 1),(72, 96),(72, 47),(72, 119);

-- Usuario 73
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(73, 68),(73, 123),(73, 30),(73, 85),(73, 17),(73, 144);

-- Usuario 74
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(74, 10),(74, 59),(74, 137),(74, 42),(74, 77),(74, 111);

-- Usuario 75
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(75, 6),(75, 100),(75, 24),(75, 70),(75, 133),(75, 49);

-- Usuario 76
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(76, 91),(76, 15),(76, 63),(76, 122),(76, 35),(76, 148);

-- Usuario 77
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(77, 84),(77, 2),(77, 56),(77, 109),(77, 18),(77, 130);

-- Usuario 78
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(78, 28),(78, 75),(78, 140),(78, 5),(78, 97),(78, 44);

-- Usuario 79
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(79, 132),(79, 8),(79, 66),(79, 115),(79, 23),(79, 90);

-- Usuario 80
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(80, 12),(80, 134),(80, 39),(80, 81),(80, 2),(80, 107);

-- Usuario 81
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(81, 73),(81, 52),(81, 98),(81, 140),(81, 11),(81, 67);

-- Usuario 82
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(82, 25),(82, 120),(82, 64),(82, 3),(82, 88),(82, 142);

-- Usuario 83
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(83, 47),(83, 101),(83, 19),(83, 55),(83, 131),(83, 6);

-- Usuario 84
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(84, 34),(84, 78),(84, 149),(84, 92),(84, 10),(84, 63);

-- Usuario 85
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(85, 87),(85, 27),(85, 121),(85, 5),(85, 99),(85, 38);

-- Usuario 86
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(86, 14),(86, 76),(86, 48),(86, 131),(86, 89),(86, 22);

-- Usuario 87
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(87, 65),(87, 110),(87, 33),(87, 4),(87, 54),(87, 136);

-- Usuario 88
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(88, 8),(88, 72),(88, 118),(88, 45),(88, 112),(88, 18);

-- Usuario 89
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(89, 60),(89, 135),(89, 26),(89, 93),(89, 13),(89, 58);

-- Usuario 90
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(90, 141),(90, 36),(90, 80),(90, 21),(90, 104),(90, 67);

-- Usuario 91
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(91, 9),(91, 150),(91, 52),(91, 39),(91, 82),(91, 1);

-- Usuario 92
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(92, 96),(92, 47),(92, 119),(92, 68),(92, 123),(92, 30);

-- Usuario 93
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(93, 85),(93, 17),(93, 144),(93, 10),(93, 59),(93, 137);

-- Usuario 94
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(94, 42),(94, 77),(94, 111),(94, 6),(94, 100),(94, 24);

-- Usuario 95
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(95, 70),(95, 133),(95, 49),(95, 91),(95, 15),(95, 63);

-- Usuario 96
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(96, 122),(96, 35),(96, 148),(96, 84),(96, 2),(96, 56);

-- Usuario 97
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(97, 109),(97, 18),(97, 130),(97, 28),(97, 75),(97, 140);

-- Usuario 98
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(98, 5),(98, 97),(98, 44),(98, 132),(98, 8),(98, 66);

-- Usuario 99
INSERT INTO equipo (id_usuario, id_pokemon) VALUES
(99, 115),(99, 23),(99, 90),(99, 12),(99, 134),(99, 39);

-- Usuario 100


-- Se usa la Poke.api para del nuemro de pokemon sacar el JSON de cada pokemon y mostrarlo en la pokedex, pero para el equipo solo se guarda el ID del pokemon, no su información completa.
-- La idea es que cada vez que un usuario gane o pierda un combate, se actualice su registro en la tabla "combates" para reflejar su historial.
-- No puedes entrar a combate si no tiene tu equipo completo de 6 Pokémon.
-- No puedes tener más de 6 Pokémon en tu equipo.
-- Puedes eliminar un Pokémon de tu equipo, pero no puedes agregar otro si ya tienes 6.
-- Puedes eliminar tu cuenta, lo que eliminará automáticamente tu equipo y tu historial de combates gracias a las claves foráneas con ON DELETE CASCADE.
-- No puedes tener el mismo Pokémon más de una vez en tu equipo gracias a la restricción UNIQUE en la tabla "equipo".
