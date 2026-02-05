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

/* ===== EQUIPOS ===== */

$router->get('/equipos', 'EquipoController@equipos');
$router->get('/equipos/equipo', 'EquipoController@show'); 

/* ===== NOTICIAS ===== */

$router->get('/noticias', 'NoticiaController@noticias');
$router->get('/noticias/noticia', 'NoticiaController@show');

/* ===== TORNEOS ===== */

$router->get('/torneos', 'TorneoController@torneos');
$router->get('/torneos/torneo', 'TorneoController@show');
$router->get('/torneos/torneo/tabla', 'TorneoController@tabla');

/* ===== PARTIDOS ===== */

$router->get('/partidos', 'PartidoController@partidos');
$router->get('/partidos/partido', 'PartidoController@show');






$router->get('/noticias', 'NoticiaController@noticias');
$router->get('/crearNoticia', 'NoticiaController@formCrearNoticia');
$router->post('/crearNoticia', 'NoticiaController@crearNoticia');
#$router->get('/partidos', 'PageController@partidos');

#$router->get('/listaEquipos', 'PageController@listaEquipos');

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

$router->get('/cuenta/perfil', 'UsuarioController@perfil');
$router->post('/cuenta/perfil', 'UsuarioController@updateperfil');

/* TORNEO */

$router->get('/torneos/crearTorneo', 'PageController@crearTorneo');
$router->post('/torneos/crearTorneo', 'TorneoController@crearTorneo');
$router->get('/torneos', 'TorneoController@torneos');
$router->get('/torneo', 'TorneoController@torneo');
$router->get('/torneo/cargarEquipo', "TorneoController@formCargarEquipo");
$router->get('/torneo/cargarResultado', "TorneoController@formCargarResultado");
$router->get('/torneo/cargarPartido', "TorneoController@formCargarPartido");

$router->post('/torneo/cargarEquipo', "TorneoController@cargarEquipo");
$router->post('/torneo/cargarResultado', "TorneoController@cargarResultado");
$router->post('/torneo/cargarPartido', "TorneoController@cargarPartido");




$router->get('/competencia/partidos', 'PageController@partidos');

