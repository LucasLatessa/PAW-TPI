# PAW-TPI
Repositorio para trabajo practico integrador correspondientes a Programacion en Ambiente Web (11086)

Se resolvio un sitio para una liga de futbol que permite ver tablas, noticias  y resultados de partidos. Tambien se cuenta con una sección para loguearse que permitira realizar ABM de partidos, torneos y equipos.

Entorno de desarrollo: VSCode,
Sistema Operativo: Windows 11

Figma: https://www.figma.com/file/XKGX9Ru2A00yJzaIg28TDR/LMJR(Chiv)---TPI(PAW)?type=design&node-id=11%3A223&mode=design&t=Hns7pwIfRCSFOq5a-1

Diagramas: https://drive.google.com/file/d/11eeG-HcdYVZ5bG-VZjLCpFYsAhSQwbeX/view?usp=sharing

## Instrucciones para configurar-instalacion

1. Instalar todas las dependencias

```
composer update
composer install
```

2. Crear un archivo .env, este tendra los configuracion necesaria para conectar a la base de datos

3. Copia el contenido que esta en .env.example en .env, modificiando username y password acorde a las configuraciones de su MySql

4. LLevar a cabo los migrates con el siguiente comando

```
phinx migrate -e development
```

5. Levantar el proyecto

```
php -S localhost:8888 -t public
```

6. En el caso que se quiera levantar en un servidor publico, ejecute el siguiente comando (con el servidor levantado en local)

```
./ngrok http http://localhost:8888/
```