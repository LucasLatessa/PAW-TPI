<?php

require __DIR__ . '/../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;

use Paw\Core\Database\ConnectionBuilder;
use Paw\Core\Request;
use Paw\Core\Router;
use Paw\Core\Config;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Configuro Twig
$loader = new FilesystemLoader(__DIR__ . '/App/Views'); // Ruta a vistas Twig
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/App/cache',
]);

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

$config = new Config;


$log = new Logger('mvc-app-paw-power'); #Instancio logger y le pongo un nombre
$handler = new StreamHandler($config->get("LOG_PATH"));
$handler->setLevel($config->get("LOG_LEVEL"));
$log->pushHandler($handler); #Nivel Debug en este caso, mas bajo

#Conexion con la base de datos
$connectionBuilder = new ConnectionBuilder;
$connectionBuilder->setLoggeable($log);
$connection = $connectionBuilder->make($config);

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler); #Manejador de errores
$whoops->register(); #Ahora el maneja los errores de PHP

$request = new Request;

$router = new Router;
$router->setLoggeable($log); #Agrego el log

# ----------------
#      RUTEO
# ----------------

$router->get('/', 'PageController@index'); #Clase y metodo que procesa la peticion

$router->get('/tabla', 'PageController@tabla');

$router->get('/noticias', 'PageController@noticias');

#$router->get('/partidos', 'PageController@partidos');

#$router->get('/listaEquipos', 'PageController@listaEquipos');


$router->get('/listaEquipos', 'LigaController@listaEquipos');

$router->get('/listaEquipos/equipo', 'EquipoController@datosEquipo'); 


$router->get('/reglamento', 'PageController@reglamento');

$router->get('/login', 'PageController@ingresar');


$router->get('/contacto', 'PageController@contacto');

$router->get('/nosotros', 'PageController@nosotros');


$router->get('/liga/cargarEquipo', 'PageController@cargarEquipo');


$router->post('/liga/cargarEquipo', 'LigaController@cargarEquipo');

$router->get('/cuenta/registrarse', 'PageController@registrarse');
$router->get('/cuenta/logout', 'UsuarioController@logout');
$router->post('/cuenta/registrarse', 'UsuarioController@registrarse');
$router->post('/login', 'UsuarioController@login');

$router->get('/cuenta/perfil', 'PageController@perfil');
$router->post('/cuenta/perfil', 'UsuarioController@updateperfil');


$router->get('/liga/crearTorneo', 'PageController@crearTorneo');


$router->get('/torneos', 'LigaController@torneos');
$router->get('/competencia/partidos', 'PageController@partidos');

$router->post('/liga/crearTorneo', 'LigaController@crearTorneo');