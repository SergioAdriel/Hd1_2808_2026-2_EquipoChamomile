-- Usar codificación correcta
SET NAMES utf8mb4;

-- Crear base de datos correctamente
CREATE DATABASE IF NOT EXISTS proyecto
CHARACTER SET utf8mb4
COLLATE utf8mb4_spanish_ci;

USE proyecto;

-- Crear TAblas pokedex
CREATE TABLE pokemon (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);
CREATE TABLE tipo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL
);
CREATE TABLE pokemon_tipo (
    id_pokemon INT,
    id_tipo INT,
    PRIMARY KEY (id_pokemon, id_tipo),
    FOREIGN KEY (id_pokemon) REFERENCES pokemon(id),
    FOREIGN KEY (id_tipo) REFERENCES tipo(id)
);
CREATE TABLE pokemon_debilidad (
    id_pokemon INT,
    id_tipo INT,
    PRIMARY KEY (id_pokemon, id_tipo),
    FOREIGN KEY (id_pokemon) REFERENCES pokemon(id),
    FOREIGN KEY (id_tipo) REFERENCES tipo(id)
);

-- Insertar datos correctamente

-- Ver datos
