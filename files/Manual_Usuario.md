# Manual de Usuario

## 1. Proposito

Este manual explica como usar la aplicacion web Pokedex desde la perspectiva del entrenador. Se enfoca en las pantallas reales del sistema, los datos que pide cada formulario, las validaciones visibles y las restricciones que el usuario encontrara durante el uso.

## 2. Antes de comenzar

Para usar el sistema necesitas:

- acceder a la carpeta del proyecto desde una terminal
- ingresar a la carpeta con el dockerfile
- ingresa comando de "docker compose up --build" en la terminal
- debe estar dockerizado y el proyecto se levanta en `http://localhost:8080`, donde puedes abrirlo desde cualquier navegador web
- conexion a internet, porque la app consulta informacion de Pokemon en PokeAPI y carga sprites remotos

Si entras a la pagina principal y ya habias iniciado sesion, el sistema te envia directamente al panel principal.

## 3. Pantalla de inicio

Ruta de entrada: `Proyecto/index.php`

Desde aqui puedes:

- abrir `Registrarse`
- abrir `Iniciar sesion`
- escuchar o pausar la musica de fondo desde el boton flotante con forma de Pokebola

En la barra superior:

- si no has iniciado sesion, aparecen accesos a `Registro` e `Iniciar sesion`
- si ya iniciaste sesion, aparece la opcion `Salir`

## 4. Registro de entrenador

Pantalla: `Controlador/registroVista.php`

Datos solicitados:

- nombre de entrenador
- telefono de 10 digitos
- contrasena

Comportamiento observado:

- el campo telefono solo acepta 10 digitos desde el formulario
- el sistema no permite registrar un nombre repetido
- el sistema no permite registrar un telefono repetido
- si el alta se guarda correctamente, la misma pantalla muestra `Registro exitoso`
- el registro no inicia sesion automaticamente; despues debes entrar por login

Mensaje de error visible:

- `El usuario ya esta registrado`

## 5. Inicio de sesion

Pantalla: `Controlador/login.php`

Puedes iniciar sesion con cualquiera de estas dos opciones:

- telefono
- nombre de usuario

Ademas debes capturar la contrasena exacta con la que fue creada la cuenta.

Si el acceso es correcto:

- se crea la sesion del entrenador
- el sistema te redirige al panel principal

Si falla:

- te manda a una pantalla de error
- el mensaje puede indicar que el usuario no existe o que la contrasena es incorrecta

## 6. Panel principal

Pantalla: `Controlador/principal.php`

Esta es la pantalla central del usuario autenticado. Aqui se muestran:

- nombre del entrenador activo
- total acumulado de victorias
- total acumulado de derrotas
- sprites del equipo actual guardado en base de datos

Acciones disponibles:

- `Editar equipo`
- `Consultar oponentes`
- `Configuracion de cuenta`

Si todavia no tienes Pokemon cargados, el panel mostrara `Sin Pokemon`.

## 7. Gestion del equipo

Pantalla: `Vista/equipo.php`

Esta vista sirve para revisar, agregar y eliminar Pokemon del equipo.

### 7.1 Lo que ves en pantalla

- nombre del entrenador
- lista de Pokemon actuales con sprite, nombre y numero
- boton `Eliminar` para cada Pokemon del equipo
- boton `Consultar Pokedex`
- formulario para agregar Pokemon, solo si aun no llegas a 6 integrantes

### 7.2 Reglas reales del equipo

- un entrenador puede tener maximo 6 Pokemon
- no se puede repetir el mismo Pokemon dentro del mismo equipo
- el selector visual esta limitado a Pokemon del `1` al `151`
- cuando el equipo ya tiene 6 integrantes, el formulario de alta deja de mostrarse

### 7.3 Agregar Pokemon

El sistema ofrece dos formas de seleccion:

- buscar por nombre
- buscar por numero

Flujo recomendado:

1. Escribe el nombre o el numero.
2. Selecciona una opcion de la lista de sugerencias.
3. Presiona `Agregar`.

Si todo sale bien aparece:

- `Pokemon agregado correctamente.`

Errores que puede mostrar la vista:

- `Debes seleccionar un Pokemon valido.`
- `Ya tienes ese Pokemon en tu equipo.`
- `No puedes tener mas de 6 Pokemon.`

### 7.4 Eliminar Pokemon

Para quitar un Pokemon:

1. Ubica la tarjeta del Pokemon.
2. Presiona `Eliminar`.
3. Confirma el mensaje del navegador.

Si se borra correctamente aparece:

- `Pokemon eliminado correctamente.`

## 8. Consulta de la Pokedex

Pantalla: `Vista/verTodosPokemon.php`

Esta vista carga los primeros 151 Pokemon y muestra una cuadricula de tarjetas.

Cada tarjeta incluye:

- sprite
- nombre
- numero de Pokedex

Al hacer clic en una tarjeta se abre una ventana modal con:

- imagen principal
- numero
- altura en metros
- peso en kilogramos
- experiencia base
- tipos traducidos al espanol
- estadisticas base
- boton para reproducir el grito del Pokemon

Si la consulta a la API falla, la pagina muestra un mensaje de error de carga.

## 9. Consulta de oponentes

Pantalla: `Vista/combate.php`

Esta pantalla lista a los entrenadores registrados, excepto al usuario actual.

Por cada rival se muestran:

- nombre
- victorias y derrotas
- sprites de su equipo actual
- estado para pelear

Estados posibles:

- `Completa tu equipo`
- `Rival incompleto`
- boton `Retar`

Mensaje superior de la pantalla:

- `Necesitas 6 Pokemon para combatir` si tu equipo tiene menos de 6
- `Listo para combatir` si tu equipo ya esta completo

## 10. Confirmacion y batalla

Pantallas:

- `Vista/retar.php`
- `Vista/batalla.php`

### 10.1 Confirmar combate

Antes de pelear, el sistema compara visualmente:

- tu equipo
- el equipo del rival

El boton `Pelear` solo aparece si ambos equipos ya tienen 6 Pokemon.

### 10.2 Animacion previa

Al comenzar la batalla:

- aparece una animacion con barra de carga
- se muestran los dos entrenadores
- se reproducen mensajes y efectos visuales
- al terminar la carga, el sistema envia el resultado automaticamente

### 10.3 Como decide el ganador

El usuario no elige ataques ni movimientos.

La app hace esto de forma automatica:

1. Toma los 6 Pokemon de cada equipo segun el orden almacenado.
2. Enfrenta Pokemon contra Pokemon por posicion.
3. Consulta PokeAPI para leer las estadisticas base de cada uno.
4. Suma las estadisticas base de cada Pokemon.
5. Gana cada ronda el Pokemon con la suma mas alta.
6. Gana la batalla el entrenador que consiga mas rondas.

En caso de empate de estadisticas dentro de una ronda, el codigo actual cuenta esa ronda a favor del rival.

### 10.4 Resultado mostrado

Despues de la animacion se presenta:

- marcador de rondas ganadas por cada entrenador
- historial total actualizado de victorias y derrotas
- equipo ganador
- detalle visual de las 6 peleas

Ademas, el navegador guarda logros en almacenamiento local, por ejemplo:

- primera victoria
- victoria perfecta
- cinco victorias totales

## 11. Configuracion de cuenta

Pantallas:

- `Controlador/configuracion.php`
- `Controlador/editarCuenta.php`
- `Controlador/eliminarUsuario.php`

### 11.1 Menu de configuracion

Desde aqui puedes:

- editar datos
- eliminar cuenta
- volver al panel

### 11.2 Editar datos

Datos editables:

- nombre
- telefono
- contrasena
- confirmacion de contrasena

Validaciones aplicadas:

- todos los campos son obligatorios
- las dos contrasenas deben coincidir
- el telefono debe contener solo numeros y al menos 10 digitos
- no se puede usar nombre o telefono ya registrados por otro usuario

Mensajes posibles:

- `Todos los campos son obligatorios`
- `Las contrasenas no coinciden`
- `El telefono debe tener al menos 10 digitos y solo numeros`
- `El nombre o telefono ya esta registrado por otro usuario`
- `Datos actualizados correctamente`

### 11.3 Eliminar cuenta

La pantalla de confirmacion advierte que se eliminaran:

- la cuenta del entrenador
- su equipo Pokemon
- sus estadisticas de combate

Si confirmas, la app cierra la sesion y vuelve a la pagina principal.

## 12. Cerrar sesion

Accion disponible en la barra superior:

- `Salir`

Al usarla:

- la sesion actual se destruye
- vuelves a `index.php`

## 13. Limitaciones de uso importantes

Durante las pruebas del sistema conviene considerar esto:

- muchas pantallas dependen de internet para mostrar nombres, sprites, detalles y estadisticas
- la Pokedex y el calculo de batalla usan datos externos en tiempo real
- el sistema no maneja movimientos, tipos, niveles ni objetos; solo usa suma de estadisticas base
- la aplicacion trabaja visualmente con los primeros 151 Pokemon en las pantallas de consulta y alta

## 14. Solucion rapida de problemas

Si algo no carga correctamente:

- verifica que Docker siga levantado
- confirma que la app abra en `http://localhost:8080`
- revisa tu conexion a internet
- vuelve a iniciar sesion si te redirige al login
- recarga la pagina si la Pokedex o los sprites no aparecen
