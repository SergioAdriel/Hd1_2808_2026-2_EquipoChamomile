# Manual Tecnico del Proyecto Pokedex

## 1. Informacion general

- Nombre del proyecto: `Pokedex`
- Tipo de sistema: aplicacion web PHP con MySQL
- Objetivo: permitir el registro de entrenadores, gestion de equipos Pokemon, consulta de Pokedex y simulacion de combates entre usuarios
- Directorio principal de la aplicacion: `Proyecto/`
- Entorno de ejecucion recomendado: Docker Compose

## 2. Tecnologias utilizadas

- PHP `8.2` con Apache
- MySQL `8`
- Docker y Docker Compose
- HTML, CSS y JavaScript
- Materialize CSS
- NES.css
- PokeAPI para informacion y sprites de Pokemon

## 3. Estructura del proyecto

```text
Hd1_2808_2026-2_EquipoChamomile/
|- docker/
|  |- Dockerfile
|  |- docker-compose.yml
|- Proyecto/
|  |- index.php
|  |- header.php
|  |- footer.php
|  |- musica.php
|  |- Controlador/
|  |- Vista/
|  |- mysql-init/
|- README.md
|- docs/
   |- Manual_Tecnico.md
   |- Manual_Usuario.md
```

## 4. Arquitectura general

El sistema sigue una estructura tipo MVC simplificada:

- `Proyecto/index.php` funciona como punto de entrada.
- `Proyecto/Controlador/` concentra la logica de autenticacion, sesion, persistencia y configuracion.
- `Proyecto/Vista/` contiene las pantallas principales del usuario.
- `Proyecto/header.php` y `Proyecto/footer.php` son componentes compartidos.
- `Proyecto/musica.php` agrega musica contextual por pantalla.
- `Proyecto/mysql-init/init.sql` crea la base de datos, tablas, triggers y datos de prueba.

No existe una separacion estricta entre vista y controlador; varias pantallas mezclan HTML, consultas SQL y llamadas a APIs externas dentro del mismo archivo.

## 5. Despliegue y ejecucion

### 5.1 Docker

Archivo: `docker/Dockerfile`

- Imagen base: `php:8.2-apache`
- Extensiones instaladas:
  - `mysqli`
  - `pdo`
  - `pdo_mysql`
- Modulo activado:
  - `rewrite`

### 5.2 Docker Compose

Archivo: `docker/docker-compose.yml`

Servicios definidos:

- `web`
  - construye la imagen desde `docker/Dockerfile`
  - publica `8080:80`
  - monta `../Proyecto` en `/var/www/html`
- `db`
  - usa `mysql:8`
  - publica `3307:3306`
  - monta `../Proyecto/mysql-init` en `/docker-entrypoint-initdb.d`

### 5.3 Ejecucion esperada

Desde la carpeta `docker/`:

```powershell
docker compose up --build
```

Accesos:

- Aplicacion web: `http://localhost:8080`
- MySQL host local: puerto `3307`

## 6. Configuracion de base de datos

Archivo: `Proyecto/Controlador/conexion.php`

Parametros de conexion:

- host: `db`
- usuario: `root`
- contrasena: `root`
- base de datos: `pokedex_app`

La conexion usa `mysqli` y establece `utf8mb4`.

## 7. Modelo de datos

Archivo: `Proyecto/mysql-init/init.sql`

### 7.1 Tabla `usuarios`

Campos principales:

- `id_usuario` `INT` PK autoincremental
- `nombre` `VARCHAR(100)`
- `telefono` `VARCHAR(15)` unico
- `contrasena` `VARCHAR(100)`
- `fecha_registro` `TIMESTAMP`

### 7.2 Tabla `equipo`

Campos principales:

- `id_equipo` `INT` PK autoincremental
- `id_usuario` `INT` FK
- `id_pokemon` `INT`
- `fecha_agregado` `TIMESTAMP`

Restricciones:

- clave unica compuesta `unique_equipo (id_usuario, id_pokemon)`
- `FOREIGN KEY` con `ON DELETE CASCADE`

### 7.3 Tabla `combates`

Campos principales:

- `id_combate` `INT` PK autoincremental
- `id_usuario` `INT` FK
- `victorias` `INT`
- `derrotas` `INT`
- `fecha_actualizacion` `TIMESTAMP`

### 7.4 Triggers

- `limitar_pokemones`
  - evita insertar mas de 6 Pokemon por usuario en `equipo`
- `crear_combate_usuario`
  - crea automaticamente un registro en `combates` cuando se inserta un nuevo usuario

### 7.5 Datos semilla

Se crean tres entrenadores de prueba:

- `Ash Ketchum`
- `Misty Waterflower`
- `Brock Stone`

## 8. Modulos funcionales

### 8.1 Inicio y navegacion

Archivos:

- `Proyecto/index.php`
- `Proyecto/header.php`
- `Proyecto/footer.php`
- `Proyecto/musica.php`

Funciones:

- muestra landing page
- redirecciona al panel si existe sesion activa
- comparte barra de navegacion y pie de pagina
- carga musica distinta segun la pantalla

### 8.2 Registro

Archivos:

- `Proyecto/Controlador/registroVista.php`
- `Proyecto/Controlador/enviarRegistro.php`

Funciones:

- captura nombre, telefono y contrasena
- valida telefono a 10 digitos desde el formulario
- evita duplicados por telefono o nombre
- inserta el usuario en base de datos
- activa automaticamente el trigger que genera estadisticas de combate

### 8.3 Inicio de sesion

Archivos:

- `Proyecto/Controlador/login.php`
- `Proyecto/Controlador/loguear.php`
- `Proyecto/Controlador/errorLoguin.php`

Funciones:

- permite acceso por telefono o nombre
- valida contrasena contra la tabla `usuarios`
- crea variables de sesion:
  - `trainer_id`
  - `trainer_name`

### 8.4 Panel principal

Archivo: `Proyecto/Controlador/principal.php`

Funciones:

- consulta victorias y derrotas del usuario
- muestra el equipo actual usando sprites de PokeAPI
- ofrece accesos a equipo, combate y configuracion

### 8.5 Gestion de equipo

Archivos:

- `Proyecto/Vista/equipo.php`
- `Proyecto/Controlador/agregarPokemon.php`
- `Proyecto/Controlador/eliminarPokemon.php`

Funciones:

- muestra Pokemon actuales del usuario
- permite buscar Pokemon por nombre o por numero
- limita la captura a los primeros 151 Pokemon
- impide duplicados
- impide superar 6 Pokemon
- permite eliminar Pokemon del equipo

Dependencias externas:

- `https://pokeapi.co/api/v2/pokemon?limit=151`
- `https://pokeapi.co/api/v2/pokemon/{id}`
- sprites desde GitHub de PokeAPI

### 8.6 Pokedex

Archivo: `Proyecto/Vista/verTodosPokemon.php`

Funciones:

- carga los primeros 151 Pokemon
- muestra tarjetas con sprite, nombre y numero
- abre un modal con:
  - altura
  - peso
  - experiencia base
  - tipos
  - estadisticas base
  - grito del Pokemon

### 8.7 Combate

Archivos:

- `Proyecto/Vista/combate.php`
- `Proyecto/Vista/retar.php`
- `Proyecto/Vista/batalla.php`

Flujo:

1. se valida que el usuario tenga 6 Pokemon
2. se listan rivales registrados
3. se valida que el rival tambien tenga 6 Pokemon
4. se confirma el combate
5. se simula la batalla
6. se actualiza la tabla `combates`

Regla de combate implementada:

- se enfrentan 6 pares de Pokemon por posicion
- para cada enfrentamiento se consulta PokeAPI
- se suman las estadisticas base de cada Pokemon
- gana el Pokemon con suma mayor
- el entrenador con mas victorias parciales gana el combate final

## 9. Flujo de sesion

- Las pantallas protegidas verifican `$_SESSION['trainer_id']`
- El cierre de sesion se realiza en `Proyecto/Controlador/salir.php`
- La eliminacion de cuenta destruye la sesion y elimina registros relacionados por cascada

## 10. Integraciones externas

### 10.1 PokeAPI

Uso principal:

- obtener nombres de Pokemon
- obtener datos completos de Pokedex
- obtener estadisticas para combates
- obtener gritos de Pokemon

Riesgo operativo:

- si PokeAPI no responde, varias funciones pierden informacion o pueden mostrar datos incompletos

### 10.2 Recursos remotos adicionales

- Google Fonts
- `unpkg.com` para NES.css
- `code.jquery.com` para jQuery
- sprites desde `raw.githubusercontent.com`

## 11. Observaciones tecnicas importantes

Estas observaciones describen el estado actual del proyecto y conviene considerarlas en mantenimiento futuro:

- las contrasenas se guardan en texto plano; en un entorno real deberian cifrarse con `password_hash()` y validarse con `password_verify()`
- la aplicacion depende de internet para varias pantallas clave
- existe mezcla de logica, presentacion y acceso a datos en varios archivos
- el archivo `Proyecto/Controlador/actualizarTabla.php` parece codigo heredado y no corresponde al dominio actual del sistema
- en `Proyecto/Controlador/actualizarCuenta.php` se actualiza `$_SESSION['nombre']`, pero la aplicacion usa `$_SESSION['trainer_name']` para mostrar el nombre en pantalla
- los mensajes con acentos presentan problemas de codificacion en algunos archivos

## 12. Mantenimiento recomendado

- separar mejor vistas, logica y acceso a datos
- centralizar validaciones de sesion y errores
- proteger contrasenas con hashing
- agregar manejo de fallos para llamadas a PokeAPI
- mover configuraciones sensibles a variables de entorno
- agregar pruebas funcionales y validaciones automatizadas

## 13. Archivos clave de referencia

- `Proyecto/index.php`
- `Proyecto/header.php`
- `Proyecto/footer.php`
- `Proyecto/musica.php`
- `Proyecto/Controlador/conexion.php`
- `Proyecto/Controlador/principal.php`
- `Proyecto/Controlador/enviarRegistro.php`
- `Proyecto/Controlador/loguear.php`
- `Proyecto/Controlador/configuracion.php`
- `Proyecto/Controlador/actualizarCuenta.php`
- `Proyecto/Controlador/deleteUsuario.php`
- `Proyecto/Vista/equipo.php`
- `Proyecto/Vista/verTodosPokemon.php`
- `Proyecto/Vista/combate.php`
- `Proyecto/Vista/retar.php`
- `Proyecto/Vista/batalla.php`
- `Proyecto/mysql-init/init.sql`
- `docker/Dockerfile`
- `docker/docker-compose.yml`
