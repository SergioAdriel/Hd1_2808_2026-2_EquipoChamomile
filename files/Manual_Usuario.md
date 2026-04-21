# Manual de Usuario del Proyecto Pokedex

## 1. Objetivo del sistema

La aplicacion Pokedex permite registrar entrenadores, iniciar sesion, crear un equipo Pokemon de hasta 6 integrantes, consultar informacion de la Pokedex y competir contra otros usuarios registrados.

## 2. Requisitos para usar el sistema

- tener el proyecto en ejecucion
- abrir un navegador web
- acceder a `http://localhost:8080`
- contar con conexion a internet para consultar datos de Pokemon y mostrar sprites

## 3. Pantalla de inicio

Al entrar al sistema se muestra la pagina principal con:

- boton `Registrarse`
- boton `Iniciar sesion`
- descripcion general del proyecto
- musica de fondo con control flotante

Si ya existe una sesion activa, el sistema redirige automaticamente al panel principal.

## 4. Registro de entrenador

Ruta: `Registro`

Pasos:

1. dar clic en `Registrarse`
2. capturar nombre de entrenador
3. capturar telefono de 10 digitos
4. capturar contrasena
5. presionar `Registrarse`

Consideraciones:

- no se puede registrar un telefono repetido
- tampoco se permite reutilizar un nombre ya existente
- si el registro es exitoso, el usuario puede iniciar sesion

## 5. Inicio de sesion

Ruta: `Iniciar sesion`

Pasos:

1. dar clic en `Iniciar sesion`
2. escribir telefono o nombre de usuario
3. escribir contrasena
4. presionar `Iniciar sesion`

Si los datos son correctos, el sistema abre el panel principal.

## 6. Panel principal

En el panel principal el usuario puede consultar:

- nombre del entrenador
- victorias y derrotas acumuladas
- equipo Pokemon actual

Acciones disponibles:

- `Editar equipo`
- `Consultar oponentes`
- `Configuracion de cuenta`

## 7. Gestion del equipo Pokemon

Ruta: `Editar equipo`

En esta pantalla se puede:

- ver los Pokemon actuales del equipo
- eliminar Pokemon del equipo
- agregar nuevos Pokemon
- abrir la Pokedex para consultar informacion detallada

### 7.1 Reglas del equipo

- el equipo admite maximo 6 Pokemon
- no se puede repetir el mismo Pokemon en el mismo equipo
- solo se pueden agregar Pokemon validos
- el sistema trabaja con los primeros 151 Pokemon

### 7.2 Agregar Pokemon

Hay dos formas de busqueda:

- por nombre
- por numero

Pasos:

1. entrar a `Editar equipo`
2. elegir el tipo de busqueda
3. seleccionar un Pokemon de la sugerencia
4. presionar `Agregar`

Si la operacion se realiza correctamente, aparece un mensaje de confirmacion.

### 7.3 Eliminar Pokemon

Pasos:

1. entrar a `Editar equipo`
2. ubicar el Pokemon que se desea retirar
3. presionar `Eliminar`
4. confirmar la accion

## 8. Consultar la Pokedex

Ruta: `Consultar Pokedex`

Funciones disponibles:

- ver los primeros 151 Pokemon
- revisar nombre, numero y sprite
- abrir una ventana con informacion detallada

La ventana de detalle muestra:

- imagen
- numero
- altura
- peso
- experiencia base
- tipos
- estadisticas base
- sonido o grito del Pokemon

## 9. Combates

Ruta: `Consultar oponentes`

En esta pantalla se muestran los entrenadores registrados junto con:

- nombre
- victorias y derrotas
- equipo actual

### 9.1 Requisitos para combatir

- el usuario debe tener 6 Pokemon en su equipo
- el rival tambien debe tener 6 Pokemon

Si no se cumple alguna de estas condiciones, el boton `Retar` no estara disponible.

### 9.2 Retar a un rival

Pasos:

1. entrar a `Consultar oponentes`
2. revisar los rivales disponibles
3. presionar `Retar`
4. verificar ambos equipos
5. presionar `Pelear`

### 9.3 Resultado del combate

Despues de la animacion, el sistema muestra:

- marcador de la batalla
- ganador del combate
- detalle de cada enfrentamiento Pokemon contra Pokemon
- historial total actualizado de victorias y derrotas

## 10. Configuracion de cuenta

Ruta: `Configuracion de cuenta`

Opciones disponibles:

- `Editar datos`
- `Eliminar cuenta`
- `Volver al panel`

### 10.1 Editar datos

El usuario puede actualizar:

- nombre
- telefono
- contrasena

Pasos:

1. entrar a `Configuracion de cuenta`
2. presionar `Editar datos`
3. modificar la informacion
4. escribir y confirmar la contrasena
5. presionar `Actualizar datos`

Validaciones:

- todos los campos son obligatorios
- las contrasenas deben coincidir
- el telefono debe contener solo numeros y al menos 10 digitos
- no se permite usar nombre o telefono ya registrados por otro usuario

### 10.2 Eliminar cuenta

Pasos:

1. entrar a `Configuracion de cuenta`
2. presionar `Eliminar cuenta`
3. confirmar la accion

Al eliminar la cuenta tambien se eliminan:

- el equipo Pokemon
- el historial de combates

## 11. Cerrar sesion

Desde la barra superior se puede seleccionar la opcion `Salir`.

Al hacerlo:

- se cierra la sesion actual
- el sistema regresa a la pagina principal

## 12. Mensajes frecuentes del sistema

Algunos mensajes que puede mostrar la aplicacion son:

- `Registro exitoso`
- `El usuario ya esta registrado`
- `Equipo completo (6 Pokemon)`
- `Ya tienes ese Pokemon en tu equipo`
- `Pokemon agregado correctamente`
- `Pokemon eliminado correctamente`
- `Necesitas 6 Pokemon para combatir`
- `Listo para combatir`
- `Datos actualizados correctamente`

## 13. Recomendaciones de uso

- completar el equipo antes de entrar a combate
- verificar con cuidado el Pokemon seleccionado antes de agregarlo
- revisar el historial de combate despues de cada batalla
- mantener conexion a internet para que la Pokedex y las imagenes carguen correctamente

## 14. Soporte basico ante problemas

Si el sistema no funciona como se espera:

- recargar la pagina
- verificar que Docker este levantado
- comprobar que la app responda en `http://localhost:8080`
- confirmar que exista conexion a internet
- volver a iniciar sesion si la sesion expiro
