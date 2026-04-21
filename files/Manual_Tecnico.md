# Manual Tecnico

## 1. Alcance del documento

Este manual describe la operacion tecnica del proyecto tal como esta implementado en el repositorio. No repite la explicacion general del `README`; se concentra en comportamiento real, dependencias, flujo interno, base de datos, puntos de mantenimiento y observaciones que afectan soporte o evolucion.

## 2. Stack real de ejecucion

Backend:

- PHP `8.2` sobre Apache
- extension `mysqli`
- extensiones `pdo` y `pdo_mysql` instaladas, aunque el proyecto usa `mysqli`

Persistencia:

- MySQL `8`

Frontend:

- PHP mezclado con HTML
- Materialize CSS y JS
- NES.css desde CDN
- JavaScript vanilla para interacciones de Pokedex, batalla y logros

Servicios externos consumidos en runtime:

- `https://pokeapi.co/api/v2/pokemon`
- sprites desde `https://raw.githubusercontent.com/PokeAPI/sprites/master/...`
- Google Fonts
- jQuery desde CDN

## 3. Levantamiento del entorno

Archivos involucrados:

- `docker/Dockerfile`
- `docker/docker-compose.yml`

### 3.1 Contenedor web

El `Dockerfile`:

- parte de `php:8.2-apache`
- instala `mysqli`, `pdo` y `pdo_mysql`
- habilita `mod_rewrite`
- expone el puerto `80`

### 3.2 Contenedor de base de datos

`docker-compose.yml` define:

- `web` en `8080:80`
- `db` en `3307:3306`

Montajes:

- `../Proyecto` en `/var/www/html`
- `../Proyecto/mysql-init` en `/docker-entrypoint-initdb.d`

Detalle importante:

- el contenedor MySQL declara `MYSQL_DATABASE=proyecto`
- el script SQL crea y usa `pokedex_app`
- la conexion PHP apunta a `pokedex_app`

Esto funciona porque `init.sql` crea explicitamente la base real usada por la app.

## 4. Conexion y configuracion interna

Archivo: `Proyecto/Controlador/conexion.php`

Parametros hardcodeados:

- host: `db`
- usuario: `root`
- password: `root`
- base: `pokedex_app`

Caracteristicas:

- usa `new mysqli(...)`
- si falla la conexion hace `die(...)`
- fija `utf8mb4`

No hay variables de entorno ni capa de abstraccion para configuracion.

## 5. Sesion y control de acceso

Variables de sesion realmente usadas por el flujo principal:

- `$_SESSION['trainer_id']`
- `$_SESSION['trainer_name']`

Pantallas protegidas verifican principalmente `trainer_id`.

Rutas que fuerzan autenticacion:

- `Controlador/principal.php`
- `Vista/equipo.php`
- `Vista/verTodosPokemon.php`
- `Vista/combate.php`
- `Vista/retar.php`
- `Vista/batalla.php`
- `Controlador/configuracion.php`
- `Controlador/editarCuenta.php`
- `Controlador/eliminarUsuario.php`

Salida de sesion:

- `Controlador/salir.php`

Observacion importante:

- `actualizarCuenta.php` actualiza `$_SESSION['nombre']` y `$_SESSION['telefono']`, pero no sincroniza `$_SESSION['trainer_name']`
- por eso, despues de editar nombre, el panel puede seguir mostrando el nombre previo hasta que se vuelva a iniciar sesion

## 6. Modelo de datos operativo

Archivo fuente: `Proyecto/mysql-init/init.sql`

### 6.1 Tabla `usuarios`

Campos:

- `id_usuario`
- `nombre`
- `telefono`
- `contrasena`
- `fecha_registro`

Restricciones:

- `telefono` unico

Comportamiento:

- las contrasenas se guardan en texto plano

### 6.2 Tabla `equipo`

Campos:

- `id_equipo`
- `id_usuario`
- `id_pokemon`
- `fecha_agregado`

Restricciones:

- `UNIQUE (id_usuario, id_pokemon)`
- `FOREIGN KEY (id_usuario) ... ON DELETE CASCADE`

### 6.3 Tabla `combates`

Campos:

- `id_combate`
- `id_usuario`
- `victorias`
- `derrotas`
- `fecha_actualizacion`

Relacion:

- `FOREIGN KEY (id_usuario) ... ON DELETE CASCADE`

### 6.4 Triggers

`limitar_pokemones`

- se ejecuta antes de insertar en `equipo`
- bloquea altas cuando el entrenador ya tiene 6 Pokemon

`crear_combate_usuario`

- se ejecuta despues de insertar en `usuarios`
- crea automaticamente el registro inicial en `combates`

## 7. Semilla de datos y observaciones

El script inserta correctamente solo estos usuarios:

- `Ash Ketchum`
- `Misty Waterflower`
- `Brock Stone`

Despues intenta insertar equipos para IDs `4` a `100`, pero no existen altas previas de esos usuarios. Con la llave foranea actual, esas inserciones no son consistentes con el esquema y provocan fallos si el script se ejecuta completo.

Consecuencia practica:

- la documentacion no debe asumir que existen decenas de rivales sembrados
- el estado confiable inicial son 3 usuarios, de los cuales Brock tiene 3 Pokemon y no puede pelear hasta completar equipo

## 8. Flujo funcional interno

### 8.1 Entrada y layout comun

Archivos:

- `Proyecto/index.php`
- `Proyecto/header.php`
- `Proyecto/footer.php`
- `Proyecto/musica.php`

Detalles tecnicos:

- `index.php` redirige a `Controlador/principal.php` si ya hay sesion
- `header.php` arma la estructura HTML y la barra de navegacion
- `footer.php` cierra el layout y carga scripts comunes
- `musica.php` asigna un `.mp3` segun `basename($_SERVER['PHP_SELF'], '.php')`

Particularidad:

- el boton flotante de musica no solo abre el panel de volumen; tambien alterna play/pause
- el volumen se conserva en `sessionStorage`

### 8.2 Registro

Archivos:

- `Controlador/registroVista.php`
- `Controlador/enviarRegistro.php`

Validaciones reales:

- frontend: telefono con patron de 10 digitos
- backend: evita duplicados por telefono o nombre

Limitacion:

- `enviarRegistro.php` no revalida formato de telefono; si llega un POST manual con formato distinto, podria aceptarse mientras no choque con la BD

### 8.3 Login

Archivos:

- `Controlador/login.php`
- `Controlador/loguear.php`
- `Controlador/errorLoguin.php`

Comportamiento:

- login por `telefono` o `nombre`
- comparacion directa de contrasena sin hashing
- si el usuario no existe o la clave no coincide, redirecciona con `msg`

Codigos usados:

- `usuario`
- `clave`
- `campos`

Nota:

- `errorLoguin.php` no trata `campos` con un mensaje especifico; cae en el mensaje generico

### 8.4 Panel principal

Archivo:

- `Controlador/principal.php`

Consultas ejecutadas:

- victorias y derrotas desde `combates`
- `id_pokemon` del equipo desde `equipo`

Render:

- usa sprites remotos armados por URL con el numero del Pokemon
- no consulta nombres en esta vista

### 8.5 Modulo de equipo

Archivos:

- `Vista/equipo.php`
- `Controlador/agregarPokemon.php`
- `Controlador/eliminarPokemon.php`

Comportamiento real:

- la vista cuenta primero cuantos Pokemon tiene el usuario
- si ya tiene 6, oculta el formulario de alta
- para mostrar nombres del equipo, la vista hace `file_get_contents()` a PokeAPI por cada `id_pokemon`

Alta:

- el frontend permite elegir por nombre o numero
- el `input` oculto `pokemonID` solo se llena cuando el usuario hace clic en una sugerencia
- backend vuelve a validar vacio, limite y duplicado

Borrado:

- requiere `POST`
- comprueba que el Pokemon exista en el equipo del usuario antes de borrar

Limitacion tecnica:

- `agregarPokemon.php` no verifica que el ID pertenezca realmente al rango `1-151`; esa restriccion existe solo en la UI

### 8.6 Pokedex

Archivo:

- `Vista/verTodosPokemon.php`

Mecanica:

- al cargar la pagina, JS llama `https://pokeapi.co/api/v2/pokemon?limit=151`
- por cada resultado construye una tarjeta
- al abrir el modal hace otra peticion al endpoint individual del Pokemon

Extras implementados:

- traduccion de tipos al espanol
- reproduccion del grito del Pokemon
- reduccion temporal del volumen del audio de fondo mientras suena el grito

### 8.7 Listado de oponentes

Archivo:

- `Vista/combate.php`

Logica:

- consulta todos los usuarios unidos con `combates`
- excluye al entrenador autenticado en tiempo de render
- para cada rival lanza una consulta adicional a `equipo`

Condiciones para habilitar `Retar`:

- tu equipo debe tener 6 Pokemon
- el rival debe tener 6 Pokemon

### 8.8 Confirmacion de combate

Archivo:

- `Vista/retar.php`

Controla:

- que `rival` exista
- que el rival no seas tu mismo
- que ambos equipos tengan 6 Pokemon antes de mostrar el boton `Pelear`

### 8.9 Resolucion de batalla

Archivo:

- `Vista/batalla.php`

La vista tiene dos modos:

- animacion previa si no existe `$_GET['resultado']`
- calculo final si si existe

Algoritmo:

1. Recupera ambos equipos.
2. Para cada posicion `0..5`, consulta PokeAPI del Pokemon propio y del rival.
3. Suma `base_stat` de cada lista de stats.
4. Si `stats1 > stats2`, gana el jugador actual.
5. En cualquier otro caso, gana el rival.

Efecto del punto 5:

- no existe empate real de ronda; un empate favorece al rival

Persistencia:

- actualiza `combates` sumando victoria al ganador y derrota al perdedor
- si faltara un registro de `combates`, intenta insertarlo en caliente

Presentacion:

- guarda `resultadoFinal`, `ganadasJugador` y `ganadasRival` para JS
- carga `logros.js`, que guarda progresos en `localStorage`

## 9. Configuracion y cuenta

Archivos:

- `Controlador/configuracion.php`
- `Controlador/editarCuenta.php`
- `Controlador/actualizarCuenta.php`
- `Controlador/eliminarUsuario.php`
- `Controlador/deleteUsuario.php`

Edicion:

- valida vacios, igualdad de password, telefono numerico y duplicados
- actualiza `usuarios`

Eliminacion:

- `deleteUsuario.php` ignora el `id_usuario` enviado por formulario y usa el `trainer_id` de sesion
- eso evita borrar otra cuenta manipulando el campo oculto
- al borrar el usuario, MySQL elimina tambien `equipo` y `combates` por cascada

Error de redireccion observado:

- si falla `deleteUsuario.php`, redirige a `../Vista/principal.php?error=eliminar`
- esa ruta no existe; el panel real esta en `Controlador/principal.php`

## 10. Dependencias y puntos fragiles

### 10.1 Dependencia de red

El sistema depende de internet para:

- autocompletado de Pokemon
- carga de nombres de equipo
- Pokedex completa
- calculo de estadisticas de combate
- sprites
- audio de gritos
- fuentes y librerias CDN

Si PokeAPI o GitHub fallan:

- pueden verse nombres vacios o textos de fallback
- la Pokedex puede no cargar
- la batalla puede romperse durante el calculo porque no hay manejo robusto de error alrededor de `file_get_contents()` en `batalla.php`

### 10.2 Codificacion

Varios archivos muestran texto mojibake como `PokÃ©mon` o `ContraseÃ±a`.

Esto sugiere una mezcla de codificaciones de archivo que conviene normalizar a UTF-8.

### 10.3 Acoplamiento

La aplicacion mezcla:

- HTML
- consultas SQL
- validacion
- logica de negocio
- llamadas HTTP externas

dentro de los mismos archivos PHP.

Esto vuelve mas costoso:

- probar
- reutilizar codigo
- capturar errores
- migrar a una arquitectura mas limpia

## 11. Archivos residuales o inconsistentes

Existen archivos que no forman parte del flujo principal actual o muestran señales de codigo heredado:

- `Controlador/registro.php`
- `Controlador/actualizarTabla.php`
- `Controlador/eliminacionExitosa.php`

No deben tomarse como referencia principal para mantenimiento funcional de la Pokedex.

## 12. Recomendaciones tecnicas prioritarias

Orden sugerido de mejora:

1. Corregir `init.sql` para que la semilla sea consistente con las llaves foraneas.
2. Hashear contrasenas con `password_hash()` y validar con `password_verify()`.
3. Sincronizar `trainer_name` despues de editar cuenta.
4. Encapsular llamadas a PokeAPI con manejo de errores y timeouts.
5. Centralizar layout, sesion y acceso a base de datos para reducir duplicacion.
6. Normalizar archivos a UTF-8.
7. Corregir la redireccion erronea de `deleteUsuario.php`.
8. Separar logica de negocio de vistas para facilitar pruebas.

## 13. Referencia rapida para soporte

Rutas mas importantes:

- entrada publica: `Proyecto/index.php`
- login: `Proyecto/Controlador/login.php`
- panel: `Proyecto/Controlador/principal.php`
- equipo: `Proyecto/Vista/equipo.php`
- Pokedex: `Proyecto/Vista/verTodosPokemon.php`
- oponentes: `Proyecto/Vista/combate.php`
- batalla: `Proyecto/Vista/batalla.php`
- configuracion: `Proyecto/Controlador/configuracion.php`
- esquema y seed: `Proyecto/mysql-init/init.sql`
- contenedores: `docker/docker-compose.yml`
