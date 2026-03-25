-- Usar codificación correcta
SET NAMES utf8mb4;

-- Crear base de datos correctamente
CREATE DATABASE IF NOT EXISTS proyecto
CHARACTER SET utf8mb4
COLLATE utf8mb4_spanish_ci;

USE proyecto;

-- Crear TAbla pokedex
CREATE TABLE pokemon (
  id          SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(50)  NOT NULL UNIQUE,
  descripcion VARCHAR(255) NULL
);

-- Crear catalogos
CREATE TABLE tipo (
  id     TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE debilidad (
  id     TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(20) NOT NULL UNIQUE
);


-- Crear tablas de pivote para relaciones N:M

-- Un Pokémon puede tener 1 o 2 tipos
CREATE TABLE pokemon_tipo (
  pokemon_id SMALLINT UNSIGNED NOT NULL,
  tipo_id    TINYINT UNSIGNED  NOT NULL,
  PRIMARY KEY (pokemon_id, tipo_id),
  FOREIGN KEY (pokemon_id) REFERENCES pokemon(id)  ON DELETE CASCADE,
  FOREIGN KEY (tipo_id)    REFERENCES tipo(id)      ON DELETE RESTRICT
);

-- Un Pokémon puede tener N debilidades
CREATE TABLE pokemon_debilidad (
  pokemon_id  SMALLINT UNSIGNED NOT NULL,
  debilidad_id TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (pokemon_id, debilidad_id),
  FOREIGN KEY (pokemon_id)   REFERENCES pokemon(id)   ON DELETE CASCADE,
  FOREIGN KEY (debilidad_id) REFERENCES debilidad(id) ON DELETE RESTRICT
);

-- Insetar datos de catalogos

INSERT INTO tipo (nombre) VALUES
  ('Normal'), ('Fuego'), ('Agua'), ('Planta'), ('Eléctrico'),
  ('Hielo'), ('Lucha'), ('Veneno'), ('Tierra'), ('Volador'),
  ('Psíquico'), ('Bicho'), ('Roca'), ('Fantasma'), ('Dragón'),
  ('Siniestro'), ('Acero'), ('Hada');

INSERT INTO debilidad (nombre) VALUES
  ('Normal'), ('Fuego'), ('Agua'), ('Planta'), ('Eléctrico'),
  ('Hielo'), ('Lucha'), ('Veneno'), ('Tierra'), ('Volador'),
  ('Psíquico'), ('Bicho'), ('Roca'), ('Fantasma'), ('Dragón'),
  ('Siniestro'), ('Acero'), ('Hada');


-- Procedimiento almacenado para insertar Pokémon con sus tipos y debilidades

DELIMITER $$

CREATE PROCEDURE insertar_pokemon(
  IN p_nombre      VARCHAR(40),
  IN p_descripcion VARCHAR(255),
  IN p_tipos       VARCHAR(100),   -- ej: 'Fuego,Volador'
  IN p_debilidades VARCHAR(200)    -- ej: 'Agua,Roca,Eléctrico'
)
BEGIN
  DECLARE v_pokemon_id  SMALLINT UNSIGNED;
  DECLARE v_tipo_id     TINYINT UNSIGNED;
  DECLARE v_deb_id      TINYINT UNSIGNED;
  DECLARE v_token       VARCHAR(20);
  DECLARE v_lista       VARCHAR(200);
  DECLARE v_pos         INT DEFAULT 0;
  DECLARE v_tipo_count  INT DEFAULT 0;
  DECLARE v_msg         VARCHAR(200);

  -- ── BLOQUE DE ROLLBACK ante cualquier error ──────────────────
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    RESIGNAL;  -- relanza el error original hacia el cliente
  END;

  START TRANSACTION;

  -- ────────────────────────────────────────────────────────────
  -- VALIDACIONES DE ENTRADA
  -- ────────────────────────────────────────────────────────────

  -- 1. Nombre obligatorio
  IF p_nombre IS NULL OR TRIM(p_nombre) = '' THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: el nombre del Pokémon no puede estar vacío.';
  END IF;

  -- 2. Nombre no debe exceder 50 caracteres
  IF CHAR_LENGTH(TRIM(p_nombre)) > 50 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: el nombre no puede superar 50 caracteres.';
  END IF;

  -- 3. Nombre duplicado
  IF EXISTS (SELECT 1 FROM pokemon WHERE nombre = TRIM(p_nombre)) THEN
    SET v_msg = CONCAT('Error: ya existe un Pokémon con el nombre "', TRIM(p_nombre), '".');
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = v_msg;
  END IF;

  -- 4. Tipos obligatorios
  IF p_tipos IS NULL OR TRIM(p_tipos) = '' THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: se debe indicar al menos un tipo.';
  END IF;

  -- 5. Máximo 2 tipos (se detectacontando comas)
  SET v_tipo_count = LENGTH(TRIM(p_tipos)) - LENGTH(REPLACE(TRIM(p_tipos), ',', '')) + 1;
  IF v_tipo_count > 2 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: un Pokémon solo puede tener 1 o 2 tipos.';
  END IF;

  -- 6. Debilidades obligatorias
  IF p_debilidades IS NULL OR TRIM(p_debilidades) = '' THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: se debe indicar al menos una debilidad.';
  END IF;

  -- 7. Descripción no debe exceder 255 caracteres
  IF p_descripcion IS NOT NULL AND CHAR_LENGTH(p_descripcion) > 255 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: la descripción no puede superar 255 caracteres.';
  END IF;

  -- ────────────────────────────────────────────────────────────
  -- INSERTAR POKÉMON
  -- ────────────────────────────────────────────────────────────
  INSERT INTO pokemon (nombre, descripcion)
  VALUES (TRIM(p_nombre), TRIM(p_descripcion));

  SET v_pokemon_id = LAST_INSERT_ID();

  -- ────────────────────────────────────────────────────────────
  -- PROCESAR TIPOS (máximo 2)
  -- ────────────────────────────────────────────────────────────
  SET v_lista = CONCAT(TRIM(p_tipos), ',');
  SET v_pos   = 1;

  WHILE v_pos <= 2 AND v_lista <> '' DO
    SET v_token = TRIM(SUBSTRING_INDEX(v_lista, ',', 1));
    SET v_lista = SUBSTRING(v_lista, LENGTH(SUBSTRING_INDEX(v_lista, ',', 1)) + 2);

    IF v_token <> '' THEN
      -- Validar que el tipo exista en el catálogo
      SELECT id INTO v_tipo_id FROM tipo WHERE nombre = v_token LIMIT 1;
      IF v_tipo_id IS NULL THEN
        SET v_msg = CONCAT('Error: el tipo "', v_token, '" no existe en el catálogo.');
        SIGNAL SQLSTATE '45000'
          SET MESSAGE_TEXT = v_msg;
      END IF;

      INSERT IGNORE INTO pokemon_tipo (pokemon_id, tipo_id)
      VALUES (v_pokemon_id, v_tipo_id);
    END IF;

    SET v_tipo_id = NULL;  -- reset para la siguiente iteración
    SET v_pos = v_pos + 1;
  END WHILE;

  -- ────────────────────────────────────────────────────────────
  -- PROCESAR DEBILIDADES (sin límite de cantidad)
  -- ────────────────────────────────────────────────────────────
  SET v_lista = CONCAT(TRIM(p_debilidades), ',');

  WHILE v_lista <> '' DO
    SET v_token = TRIM(SUBSTRING_INDEX(v_lista, ',', 1));
    SET v_lista = SUBSTRING(v_lista, LENGTH(SUBSTRING_INDEX(v_lista, ',', 1)) + 2);

    IF v_token <> '' THEN
      -- Validar que la debilidad exista en el catálogo
      SELECT id INTO v_deb_id FROM debilidad WHERE nombre = v_token LIMIT 1;
      IF v_deb_id IS NULL THEN
        SET v_msg = CONCAT('Error: la debilidad "', v_token, '" no existe en el catálogo.');
        SIGNAL SQLSTATE '45000'
          SET MESSAGE_TEXT = v_msg;
      END IF;

      INSERT IGNORE INTO pokemon_debilidad (pokemon_id, debilidad_id)
      VALUES (v_pokemon_id, v_deb_id);
    END IF;

    SET v_deb_id = NULL;  -- reset para la siguiente iteración
  END WHILE;

  COMMIT;

END$$

DELIMITER ;

-- VISTA  —  muestra todo concatenado y listo para leer

CREATE OR REPLACE VIEW v_pokedex AS
SELECT
  LPAD(p.id, 3, '0')                                          AS numero,
  p.nombre,
  GROUP_CONCAT(DISTINCT t.nombre ORDER BY t.nombre SEPARATOR ' / ') AS tipos,
  GROUP_CONCAT(DISTINCT d.nombre ORDER BY d.nombre SEPARATOR ', ')  AS debilidades,
  p.descripcion
FROM pokemon p
LEFT JOIN pokemon_tipo      pt ON pt.pokemon_id   = p.id
LEFT JOIN tipo              t  ON t.id            = pt.tipo_id
LEFT JOIN pokemon_debilidad pd ON pd.pokemon_id   = p.id
LEFT JOIN debilidad         d  ON d.id            = pd.debilidad_id
GROUP BY p.id, p.nombre, p.descripcion
ORDER BY p.id;

-- EJEMPLOS DE USO

CALL insertar_pokemon('Bulbasaur',  'Una semilla en su lomo absorbe luz solar.',        'Planta,Veneno',  'Fuego,Hielo,Volador,Psíquico');
CALL insertar_pokemon('Charmander', 'La llama de su cola indica su estado de salud.',   'Fuego',          'Agua,Roca,Tierra');
CALL insertar_pokemon('Squirtle',   'Su caparazón reduce la resistencia al nadar.',     'Agua',           'Planta,Eléctrico');
CALL insertar_pokemon('Pikachu',    'Acumula electricidad en las bolsas de sus mejillas.','Eléctrico',    'Tierra');
CALL insertar_pokemon('Gengar',     'Se esconde en sombras y enfría todo a su alrededor.','Fantasma,Veneno','Tierra,Psíquico,Fantasma,Siniestro');
CALL insertar_pokemon('Charizard',  'Vuela buscando rivales fuertes a los que superar.','Fuego,Volador',  'Agua,Roca,Eléctrico');
CALL insertar_pokemon('Mewtwo',     'Creado en laboratorio con el mayor poder psíquico.','Psíquico',      'Bicho,Fantasma,Siniestro');

-- ── Consultar la vista ──────────────────────────────────────
-- SELECT * FROM v_pokedex;

-- ── Filtrar por tipo ────────────────────────────────────────
-- SELECT * FROM v_pokedex WHERE tipos LIKE '%Fuego%';

-- ── Filtrar por debilidad ───────────────────────────────────
-- SELECT * FROM v_pokedex WHERE debilidades LIKE '%Agua%';