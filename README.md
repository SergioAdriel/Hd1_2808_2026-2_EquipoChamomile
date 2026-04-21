# Documentacion completa del proyecto Pokedex

## 1. Descripcion general

Este proyecto es una aplicacion web hecha en PHP que funciona como una Pokedex tematica. Su objetivo es permitir que varios entrenadores se registren, inicien sesion, armen su equipo Pokemon, consulten informacion de la Pokedex y peleen entre ellos.

La aplicacion combina:

- PHP para el backend y la mezcla de vistas con logica
- MySQL para almacenar usuarios, equipos y resultados de combate
- Apache como servidor web
- Docker para ejecutar el entorno completo
- Materialize CSS y NES.css para el estilo visual
- PokeAPI para obtener datos de Pokemon en tiempo real

Ademas de la parte funcional, el proyecto incorpora una identidad visual retro con musica de fondo, sprites de Pokemon, modales informativos y una pantalla animada para los combates.

## 2. Que hace el sistema

Las funciones principales del proyecto son:

- mostrar una pagina de inicio con acceso a registro e inicio de sesion
- registrar entrenadores nuevos
- autenticar entrenadores existentes
- mantener una sesion activa
- mostrar un panel principal con estadisticas de combate y equipo actual
- permitir que el usuario agregue o elimine Pokemon de su equipo
- limitar el equipo a un maximo de 6 Pokemon
- evitar Pokemon repetidos en el mismo equipo
- consultar una Pokedex con detalles de los primeros 151 Pokemon
- listar rivales disponibles
- validar si ambos jugadores tienen equipo completo antes de combatir
- resolver combates comparando estadisticas base de Pokemon obtenidas desde la PokeAPI
- guardar victorias y derrotas en la base de datos
- permitir editar o eliminar la cuenta del usuario
- reproducir musica de fondo distinta en varias pantallas

## 3. Tecnologias usadas

- `PHP 8.2`
- `Apache`
- `MySQL 8`
- `Docker`
- `Docker Compose`
- `Materialize CSS`
- `NES.css`
- `JavaScript`
- `PokeAPI`

## 4. Flujo general del usuario

1. El usuario entra a `Proyecto/index.php`.
2. Si no tiene sesion, puede ir a registro o login.
3. Si inicia sesion correctamente, entra a `Proyecto/Controlador/principal.php`.
4. En el panel principal puede ver:
   - su nombre
   - sus victorias y derrotas
   - su equipo actual
   - accesos a equipo, combate y configuracion
5. En `Proyecto/Vista/equipo.php` puede agregar o eliminar Pokemon.
6. En `Proyecto/Vista/verTodosPokemon.php` puede consultar informacion detallada de la Pokedex.
7. En `Proyecto/Vista/combate.php` puede ver a los oponentes registrados.
8. En `Proyecto/Vista/retar.php` confirma el combate contra un rival.
9. En `Proyecto/Vista/batalla.php` se ejecuta la animacion, se calcula el resultado y se actualiza la tabla `combates`.

## 5. Estructura completa del proyecto

```text
Hd1_2808_2026-2_EquipoChamomile/
|- docker/
|  |- Dockerfile
|  |- docker-compose.yml
|- Proyecto/
|  |- footer.php
|  |- header.php
|  |- index.php
|  |- musica.php
|  |- Controlador/
|  |  |- actualizarCuenta.php
|  |  |- actualizarTabla.php
|  |  |- agregarPokemon.php
|  |  |- configuracion.php
|  |  |- conexion.php
|  |  |- deleteUsuario.php
|  |  |- editarCuenta.php
|  |  |- eliminacionExitosa.php
|  |  |- eliminarPokemon.php
|  |  |- eliminarUsuario.php
|  |  |- enviarRegistro.php
|  |  |- errorLoguin.php
|  |  |- login.php
|  |  |- loguear.php
|  |  |- politica_privacidad.php
|  |  |- principal.php
|  |  |- registro.php
|  |  |- registroVista.php
|  |  |- salir.php
|  |  |- css/
|  |  |  |- materialize.css
|  |  |  |- materialize.min.css
|  |  |  |- modalsPokedex.css
|  |  |  |- PokeCSS.css
|  |  |  |- style.css
|  |  |- js/
|  |  |  |- init.js
|  |  |  |- materialize.js
|  |  |  |- materialize.min.js
|  |  |- Media/
|  |     |- favicon/
|  |     |  |- favicon.png
|  |     |- img/
|  |     |  |- 8611.jpg
|  |     |  |- lapas.png
|  |     |  |- test.png
|  |     |  |- test2.png
|  |     |  |- test3.png
|  |     |  |- test4.png
|  |     |- musica/
|  |        |- tema_batalla.mp3
|  |        |- tema_combate.mp3
|  |        |- tema_configuracion.mp3
|  |        |- tema_equipo.mp3
|  |        |- tema_login.mp3
|  |        |- tema_pokedex.mp3
|  |        |- tema_principal.mp3
|  |        |- tema_registro.mp3
|  |- mysql-init/
|  |  |- init.sql
|  |- Vista/
|     |- batalla.php
|     |- combate.php
|     |- equipo.php
|     |- LICENSE
|     |- retar.php
|     |- verTodosPokemon.php
|     |- css/
|     |  |- batalla.css
|     |  |- materialize.css
|     |  |- materialize.min.css
|     |  |- modalsPokedex.css
|     |  |- PokeCSS.css
|     |  |- style.css
|     |- js/
|     |  |- batalla.js
|     |  |- init.js
|     |  |- logros.js
|     |  |- materialize.js
|     |  |- materialize.min.js
|     |- Media/
|        |- favicon/
|        |  |- favicon.png
|        |- img/
|           |- lapras.png
|- README.md
```

## 6. Explicacion de cada carpeta y archivo

### 6.1 Carpeta raiz

#### `README.md`

Es el archivo de documentacion del proyecto. Describe la estructura, el flujo, la base de datos y el papel de cada archivo.

## 6.2 Carpeta `docker/`

Esta carpeta contiene los archivos para construir y ejecutar la aplicacion con contenedores.

#### `docker/Dockerfile`

Construye la imagen del contenedor web con `php:8.2-apache`. Instala extensiones necesarias para conectarse a MySQL:

- `mysqli`
- `pdo`
- `pdo_mysql`

Tambien habilita `mod_rewrite` de Apache y expone el puerto `80`.

#### `docker/docker-compose.yml`

Define dos servicios:

- `web`: construye la imagen desde el `Dockerfile`, publica `8080:80` y monta `../Proyecto` como `/var/www/html`
- `db`: usa `mysql:8`, expone `3307:3306` y monta `../Proyecto/mysql-init` como carpeta de inicializacion

Esto permite que al levantar el entorno, la base se cree automaticamente y la app quede disponible en `http://localhost:8080`.

## 6.3 Carpeta `Proyecto/`

Es la carpeta principal de la aplicacion.

#### `Proyecto/index.php`

Es la pagina inicial del sistema.

Hace lo siguiente:

- inicia sesion con `session_start()`
- si ya existe `trainer_id`, redirige al panel principal
- carga `header.php` y `musica.php`
- muestra un banner principal con imagen, titulo y descripcion
- muestra botones para registrarse o iniciar sesion
- presenta una seccion de caracteristicas del sistema
- carga `footer.php`

En la practica, esta pagina funciona como landing page del proyecto.

#### `Proyecto/header.php`

Es el encabezado comun de casi todas las pantallas.

Su funcion es:

- iniciar sesion si aun no existe
- declarar la estructura HTML base
- cargar Materialize CSS
- cargar `style.css`
- cargar NES.css y la fuente retro
- mostrar la barra de navegacion
- mostrar enlaces distintos segun el usuario tenga o no sesion activa

Es un archivo compartido por casi toda la aplicacion.

#### `Proyecto/footer.php`

Es el pie de pagina comun.

Su funcion es:

- mostrar una descripcion de la Pokedex
- mostrar enlaces utiles
- mostrar informacion de contacto
- cargar jQuery
- cargar Materialize JS
- cargar `init.js`
- cerrar la estructura HTML

#### `Proyecto/musica.php`

Es un modulo reutilizable que agrega musica de fondo a la aplicacion.

Hace lo siguiente:

- detecta la pagina actual
- asigna un archivo `.mp3` segun esa pagina
- genera un reproductor flotante con forma de Pokebola
- permite reproducir, pausar y cambiar volumen
- guarda el volumen en `sessionStorage`

Es una parte distintiva del proyecto porque agrega ambientacion por pantalla.

## 6.4 Carpeta `Proyecto/Controlador/`

Aunque el nombre dice "Controlador", aqui hay tanto controladores reales como algunas vistas y archivos heredados. En esta carpeta esta gran parte de la logica del sistema.

### Archivos funcionales principales

#### `Proyecto/Controlador/conexion.php`

Crea la conexion con MySQL usando:

- host: `db`
- usuario: `root`
- password: `root`
- base de datos: `pokedex_app`

Tambien configura el charset en `utf8mb4`.

Es la base de cualquier operacion de base de datos del proyecto.

#### `Proyecto/Controlador/login.php`

Muestra el formulario de inicio de sesion.

Permite ingresar:

- telefono o nombre
- contrasena

Tambien:

- muestra un mensaje si el usuario viene de un registro exitoso
- redirige al panel principal si ya habia sesion
- ofrece enlace a registro y a la pagina principal

#### `Proyecto/Controlador/loguear.php`

Procesa el formulario de login.

Su logica:

- valida que la peticion sea `POST`
- obtiene `usuario` y `clave`
- busca coincidencia por telefono o nombre
- compara la contrasena escrita con la guardada
- si es correcta, crea variables de sesion
- redirige a `principal.php`
- si falla, envia a `errorLoguin.php`

#### `Proyecto/Controlador/errorLoguin.php`

Muestra la pantalla de error de login.

Dependiendo del parametro `msg`, explica si:

- el usuario no existe
- la contrasena es incorrecta
- ocurrio otro error general

#### `Proyecto/Controlador/registroVista.php`

Muestra el formulario de registro para nuevos entrenadores.

Pide:

- nombre
- telefono
- contrasena

Tambien valida desde HTML que el telefono tenga 10 digitos.

#### `Proyecto/Controlador/enviarRegistro.php`

Procesa el registro de un nuevo usuario.

Su funcion es:

- leer los datos enviados por formulario
- comprobar si ya existe otro usuario con ese telefono o nombre
- insertar el nuevo usuario
- redirigir con mensaje de exito o error

No aplica hash a la contrasena, por lo que la guarda en texto plano.

#### `Proyecto/Controlador/principal.php`

Es el panel principal del usuario autenticado.

Hace lo siguiente:

- verifica que exista sesion
- consulta las victorias y derrotas del usuario en `combates`
- consulta el equipo actual del usuario en `equipo`
- muestra sprites de los Pokemon del equipo
- muestra botones para:
  - editar equipo
  - consultar oponentes
  - abrir configuracion de cuenta

Es el centro del flujo despues del login.

#### `Proyecto/Controlador/configuracion.php`

Es la pantalla de configuracion de cuenta.

Sirve como menu para:

- editar datos
- eliminar cuenta
- volver al panel principal

Tambien muestra mensajes de error o exito relacionados con actualizacion de cuenta.

#### `Proyecto/Controlador/editarCuenta.php`

Muestra el formulario para editar:

- nombre
- telefono
- contrasena

Su funcion es:

- cargar los datos actuales del usuario desde la base
- mostrarlos en el formulario
- validar en frontend que no haya campos vacios
- validar que la contrasena coincida
- validar el formato del telefono

#### `Proyecto/Controlador/actualizarCuenta.php`

Procesa la actualizacion de datos de cuenta.

Hace:

- validacion de campos obligatorios
- validacion de coincidencia de contrasenas
- validacion de telefono numerico de al menos 10 digitos
- validacion para no duplicar nombre o telefono con otro usuario
- actualizacion del registro en `usuarios`

Observacion importante:

- actualiza en sesion `nombre` y `telefono`, pero el proyecto usa `trainer_name` como nombre visible principal, asi que la sesion no queda totalmente sincronizada en esa parte

#### `Proyecto/Controlador/eliminarUsuario.php`

Muestra la pantalla de confirmacion para borrar la cuenta.

Advierte que al eliminar la cuenta tambien se perderan:

- el equipo Pokemon
- las estadisticas de combate

#### `Proyecto/Controlador/deleteUsuario.php`

Ejecuta la eliminacion real de la cuenta.

Hace:

- validar sesion
- borrar al usuario de la tabla `usuarios`
- destruir la sesion
- redirigir a la pagina principal

Gracias a las llaves foraneas con `ON DELETE CASCADE`, tambien se eliminan automaticamente:

- el equipo del usuario
- su historial de combates

#### `Proyecto/Controlador/agregarPokemon.php`

Agrega un Pokemon al equipo del usuario autenticado.

Su logica:

- verifica sesion
- obtiene el id del Pokemon enviado desde el formulario
- valida que no venga vacio
- valida que el usuario no tenga ya 6 Pokemon
- valida que el Pokemon no este repetido
- inserta el registro en la tabla `equipo`

#### `Proyecto/Controlador/eliminarPokemon.php`

Elimina un Pokemon del equipo del usuario.

Hace:

- validar sesion
- validar que llegue un Pokemon por `POST`
- verificar que realmente exista ese Pokemon en el equipo del usuario
- eliminarlo
- redirigir con mensaje de exito o error

#### `Proyecto/Controlador/salir.php`

Destruye la sesion del usuario y lo regresa a `index.php`.

#### `Proyecto/Controlador/politica_privacidad.php`

Muestra una pagina informativa con:

- declaracion de privacidad
- informacion sobre uso y proteccion de datos
- integrantes del equipo de desarrollo
- nombre del equipo del proyecto

### Archivos secundarios o residuales

#### `Proyecto/Controlador/registro.php`

Este archivo no forma parte clara del flujo actual de la Pokedex.

Por lo que se observa:

- pertenece a una version anterior o a otro proyecto
- usa variables de sesion como `username`
- pide datos como edificio, departamento y correo
- no coincide con el modelo actual de `usuarios`

En otras palabras, parece un archivo heredado que hoy no corresponde con la logica principal.

#### `Proyecto/Controlador/actualizarTabla.php`

Tambien parece residual o reciclado de otro sistema.

Razones:

- consulta una tabla llamada `residente`
- muestra columnas como `letra_edificio`, `numero_departamento` y `email`
- esas tablas y campos no forman parte de `init.sql`

No parece ser usado por la aplicacion actual.

#### `Proyecto/Controlador/eliminacionExitosa.php`

Es una vista simple que muestra un mensaje de eliminacion exitosa.

Actualmente no parece estar integrada al flujo real, porque `deleteUsuario.php` redirige al `index.php`.

### Subcarpeta `Proyecto/Controlador/css/`

Contiene archivos CSS disponibles desde el area de control.

#### `Proyecto/Controlador/css/materialize.css`

Version no minificada de Materialize CSS. Es una libreria de terceros para componentes visuales.

#### `Proyecto/Controlador/css/materialize.min.css`

Version minificada de Materialize CSS. Misma funcion que la anterior, pero optimizada para carga.

#### `Proyecto/Controlador/css/modalsPokedex.css`

Hoja de estilos para tarjetas y modales de la Pokedex. Su equivalente tambien existe en `Vista/css/`.

#### `Proyecto/Controlador/css/PokeCSS.css`

Estilos para mostrar tarjetas Pokemon y elementos relacionados con el equipo. Su equivalente tambien existe en `Vista/css/`.

#### `Proyecto/Controlador/css/style.css`

Estilos generales del sitio. Su equivalente tambien existe en `Vista/css/`.

### Subcarpeta `Proyecto/Controlador/js/`

Contiene JavaScript base para la interfaz.

#### `Proyecto/Controlador/js/init.js`

Inicializa componentes de Materialize como:

- `sidenav`
- `parallax`

#### `Proyecto/Controlador/js/materialize.js`

Version no minificada de Materialize JS.

#### `Proyecto/Controlador/js/materialize.min.js`

Version minificada de Materialize JS.

### Subcarpeta `Proyecto/Controlador/Media/`

Agrupa recursos multimedia usados por la aplicacion.

#### `Proyecto/Controlador/Media/favicon/favicon.png`

Icono del sitio.

### Subcarpeta `Proyecto/Controlador/Media/img/`

#### `8611.jpg`

Imagen de apoyo dentro del proyecto. No se ve claramente conectada al flujo principal, pero forma parte del repositorio.

#### `lapas.png`

Recurso grafico relacionado con la tematica Pokemon. El nombre sugiere una variante de Lapras.

#### `test.png`

Imagen de apoyo o prueba visual.

#### `test2.png`

Imagen de apoyo o prueba visual.

#### `test3.png`

Imagen usada en `index.php` como fondo del banner principal.

#### `test4.png`

Imagen de apoyo o prueba visual.

### Subcarpeta `Proyecto/Controlador/Media/musica/`

Contiene las pistas de audio para cada seccion de la aplicacion.

#### `tema_batalla.mp3`

Musica de la vista de batalla.

#### `tema_combate.mp3`

Musica para la seccion de combate o reto.

#### `tema_configuracion.mp3`

Musica usada en configuracion y edicion de cuenta.

#### `tema_equipo.mp3`

Musica usada en la gestion del equipo.

#### `tema_login.mp3`

Musica asociada a login o pantallas de error.

#### `tema_pokedex.mp3`

Musica para la Pokedex.

#### `tema_principal.mp3`

Tema base para la pantalla principal y la pagina de inicio.

#### `tema_registro.mp3`

Musica para las pantallas de registro.

## 6.5 Carpeta `Proyecto/mysql-init/`

#### `Proyecto/mysql-init/init.sql`

Es uno de los archivos mas importantes del proyecto porque define la base de datos completa.

Hace lo siguiente:

- crea la base `pokedex_app`
- elimina tablas previas si existian
- crea la tabla `usuarios`
- crea la tabla `equipo`
- crea la tabla `combates`
- crea un trigger para limitar a 6 Pokemon por usuario
- crea un trigger para insertar automaticamente el registro en `combates` cuando nace un usuario
- inserta usuarios de prueba
- inserta equipos iniciales de prueba

Tambien documenta reglas de negocio importantes dentro de comentarios SQL.

## 6.6 Carpeta `Proyecto/Vista/`

Esta carpeta contiene la mayoria de las vistas relacionadas con Pokemon, equipo y combate.

#### `Proyecto/Vista/equipo.php`

Es la pantalla para administrar el equipo del usuario.

Su funcion es:

- verificar sesion
- contar cuántos Pokemon tiene el usuario
- mostrar mensajes de error o exito
- listar el equipo actual
- permitir eliminar cada Pokemon
- permitir agregar un Pokemon por nombre o por numero
- consultar la PokeAPI para mostrar nombre de cada Pokemon guardado
- impedir agregar mas de 6 Pokemon

#### `Proyecto/Vista/verTodosPokemon.php`

Es la Pokedex visual del proyecto.

Hace:

- cargar los primeros 151 Pokemon desde la PokeAPI
- mostrar sus tarjetas con sprite y numero
- abrir un modal al hacer clic
- consultar detalles del Pokemon seleccionado
- mostrar tipos, altura, peso, experiencia y estadisticas
- reproducir el grito del Pokemon usando `pokemon.cries`

#### `Proyecto/Vista/combate.php`

Muestra la lista de rivales registrados.

Hace:

- verificar sesion
- comprobar si el usuario actual tiene 6 Pokemon
- obtener todos los entrenadores y sus estadisticas
- excluir al propio usuario de la lista de rivales
- cargar el equipo de cada rival
- mostrar si el combate es posible
- habilitar el boton `Retar` solo cuando ambos equipos estan completos

#### `Proyecto/Vista/retar.php`

Es la pantalla de confirmacion previa al combate.

Hace:

- validar sesion
- validar el id del rival
- cargar el nombre del rival
- cargar ambos equipos
- verificar que ambos tengan 6 Pokemon
- mostrar una vista comparativa antes de pelear
- enviar el id del rival a `batalla.php`

#### `Proyecto/Vista/batalla.php`

Es la vista mas compleja del proyecto.

Tiene dos fases:

- fase 1: muestra una pantalla animada de carga y combate
- fase 2: calcula el resultado y muestra el resumen final

Durante el calculo:

- obtiene los equipos de ambos usuarios
- consulta la PokeAPI para obtener estadisticas
- suma las `base_stat` de cada Pokemon enfrentado
- compara Pokemon contra Pokemon segun la posicion del equipo
- determina un ganador general
- actualiza victorias y derrotas en `combates`
- muestra detalle de cada pelea
- muestra el equipo ganador
- inyecta variables JS para el sistema de logros

#### `Proyecto/Vista/LICENSE`

Es la licencia MIT de Materialize. No forma parte de la logica del proyecto, pero acompana la distribucion de esa libreria.

### Subcarpeta `Proyecto/Vista/css/`

Aqui estan los estilos usados por las vistas principales.

#### `Proyecto/Vista/css/batalla.css`

Define el estilo de:

- pantalla de carga de la batalla
- barra de poder
- mensajes de combate
- sprites en animacion
- tarjetas de resultado
- detalle de peleas
- popup de logros

#### `Proyecto/Vista/css/materialize.css`

Version no minificada de Materialize CSS.

#### `Proyecto/Vista/css/materialize.min.css`

Version minificada de Materialize CSS.

#### `Proyecto/Vista/css/modalsPokedex.css`

Estilos de la Pokedex y del modal de detalle.

Incluye:

- tarjetas de Pokemon
- animacion de flotacion
- diseño del modal
- colores por tipo de Pokemon
- formato de estadisticas

#### `Proyecto/Vista/css/PokeCSS.css`

Archivo de estilos para tarjetas de Pokemon y bloques visuales del equipo. Se usa para mantener la presentacion de sprites y contenedores.

#### `Proyecto/Vista/css/style.css`

Hoja de estilos generales del sitio, compartida con la cabecera y otras pantallas.

### Subcarpeta `Proyecto/Vista/js/`

Contiene el JavaScript especifico de las vistas.

#### `Proyecto/Vista/js/batalla.js`

Controla la experiencia visual de la pantalla de carga del combate.

Hace:

- mostrar mensajes epicos
- animar ataques aleatorios
- simular explosiones y chispas
- temblar la pantalla
- aumentar una barra de poder
- enviar automaticamente el formulario a `batalla.php?resultado=1` cuando la animacion termina

Es un archivo de ambientacion, no de calculo real del combate.

#### `Proyecto/Vista/js/logros.js`

Implementa logros con `localStorage`.

Actualmente maneja:

- primera victoria
- victoria perfecta
- cinco victorias totales

Tambien muestra un popup animado cuando se desbloquea un logro.

#### `Proyecto/Vista/js/init.js`

Inicializa componentes de Materialize como el menu lateral y el parallax.

#### `Proyecto/Vista/js/materialize.js`

Version no minificada de Materialize JS.

#### `Proyecto/Vista/js/materialize.min.js`

Version minificada de Materialize JS.

### Subcarpeta `Proyecto/Vista/Media/`

Recursos visuales de apoyo para las vistas.

#### `Proyecto/Vista/Media/favicon/favicon.png`

Icono alterno del sitio dentro del area de vistas.

#### `Proyecto/Vista/Media/img/lapras.png`

Imagen relacionada con Lapras. Aporta recursos visuales a la identidad grafica del proyecto.

## 7. Base de datos

El sistema usa la base `pokedex_app`.

### Tabla `usuarios`

Guarda:

- `id_usuario`
- `nombre`
- `telefono`
- `contrasena`
- `fecha_registro`

### Tabla `equipo`

Guarda:

- `id_equipo`
- `id_usuario`
- `id_pokemon`
- `fecha_agregado`

Restricciones importantes:

- no permite repetir el mismo Pokemon para el mismo usuario
- relaciona cada Pokemon con un usuario valido

### Tabla `combates`

Guarda:

- `id_combate`
- `id_usuario`
- `victorias`
- `derrotas`
- `fecha_actualizacion`

### Triggers

#### `limitar_pokemones`

Evita insertar un Pokemon si el usuario ya tiene 6 en su equipo.

#### `crear_combate_usuario`

Crea automaticamente un registro en `combates` cuando se inserta un nuevo usuario.

## 8. Integracion con la PokeAPI

El proyecto depende mucho de la PokeAPI.

Se usa para:

- obtener nombres de Pokemon a partir del id
- cargar lista de 151 Pokemon
- obtener detalles individuales
- obtener tipos, peso, altura y experiencia
- obtener estadisticas base para calcular combates
- obtener los gritos del Pokemon

Tambien usa sprites desde:

- `raw.githubusercontent.com/PokeAPI/sprites/...`

Esto significa que varias partes del sistema requieren internet para funcionar correctamente.

## 9. Reglas de negocio observadas en el codigo

- un usuario debe iniciar sesion para usar panel, equipo, combate o configuracion
- cada usuario puede tener como maximo 6 Pokemon
- no se puede repetir un Pokemon dentro del mismo equipo
- para poder combatir, el usuario debe tener 6 Pokemon
- el rival tambien debe tener 6 Pokemon
- las victorias y derrotas se guardan por usuario
- al eliminar un usuario se elimina tambien su equipo y su historial
- la batalla no usa tipos, movimientos ni niveles; usa la suma de estadisticas base
- el combate compara Pokemon por posicion del equipo, no por seleccion libre

## 10. Archivos duplicados o de terceros

En el proyecto hay varios archivos duplicados entre `Controlador` y `Vista`, especialmente:

- `materialize.css`
- `materialize.min.css`
- `materialize.js`
- `materialize.min.js`
- `init.js`
- algunas hojas CSS visuales

Esto no rompe necesariamente la app, pero indica que hay recursos copiados en mas de una carpeta.

Tambien hay archivos que parecen venir de otros contextos:

- `Controlador/registro.php`
- `Controlador/actualizarTabla.php`
- `Controlador/eliminacionExitosa.php`

Es importante distinguirlos para no confundirlos con el flujo real de la Pokedex.

## 11. Como ejecutar el proyecto

### Opcion recomendada con Docker

Desde la carpeta `docker/` ejecutar:

```bash
docker compose up --build
```

Luego abrir:

```text
http://localhost:8080
```

### Servicios

- web en `localhost:8080`
- mysql en `localhost:3307`

## 12. Datos de prueba cargados por SQL

La base inicial inserta tres usuarios:

- Ash Ketchum
- Misty Waterflower
- Brock Stone

Y tambien crea sus equipos de ejemplo.

Esto sirve para:

- probar login
- probar visualizacion de rivales
- probar el flujo de combate

## 13. Observaciones tecnicas importantes

- las contrasenas se guardan en texto plano
- la app depende de internet para PokeAPI, sprites y algunos recursos externos
- el proyecto mezcla logica, presentacion y control en archivos PHP
- hay rutas relativas que dependen de la estructura actual del repositorio
- algunos archivos parecen heredados y no del todo integrados al sistema actual

## 14. Resumen final

Este proyecto implementa una Pokedex web con estilo retro donde los usuarios pueden registrarse, iniciar sesion, formar un equipo Pokemon, consultar informacion detallada de la Pokedex y combatir con otros entrenadores. La estructura principal se reparte entre una carpeta `Controlador`, una carpeta `Vista`, los recursos multimedia y un script SQL de inicializacion. Aunque hay algunos archivos residuales o duplicados, el flujo principal del sistema esta claro y funciona alrededor de autenticacion, administracion de equipo, PokeAPI y combates.
