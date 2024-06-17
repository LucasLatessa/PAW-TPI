# PAW-TPI
Repositorio para trabajo practico integrador correspondientes a Programacion en Ambiente Web (11086)

Se resolvio un sitio para una liga de futbol que permite ver tablas, noticias  y resultados de partidos. Tambien se cuenta con una sección para loguearse que permitira realizar ABM de partidos, torneos y equipos.

Entorno de desarrollo: VSCode,
Sistema Operativo: Windows 11

Figma: https://www.figma.com/file/XKGX9Ru2A00yJzaIg28TDR/LMJR(Chiv)---TPI(PAW)?type=design&node-id=11%3A223&mode=design&t=Hns7pwIfRCSFOq5a-1

Diagramas: https://drive.google.com/file/d/11eeG-HcdYVZ5bG-VZjLCpFYsAhSQwbeX/view?usp=sharing


1. Instalar todas las dependencias

composer install

2. Creación db mysql con las configuraciones establecidas en el env 

3. Hacer el migrate db

phinx migrate -e development

4. Levantar el proyecto 

php -S localhost:8888 -t public