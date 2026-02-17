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
$router->get('/equipos/crearEquipo', 'EquipoController@formCrearEquipo');
/* Post */
$router->post('/equipos/crearEquipo', 'EquipoController@crearEquipo');

/* ===== NOTICIAS ===== */
$router->get('/noticias', 'NoticiaController@noticias');
$router->get('/noticias/noticia', 'NoticiaController@show');
$router->get('/noticias/crear', 'NoticiaController@formCrearnoticia');
/* Post */
$router->post('/noticias/crear', 'NoticiaController@crearNoticia');

/* ===== TORNEOS ===== */
$router->get('/torneos', 'TorneoController@torneos');
$router->get('/torneos/torneo', 'TorneoController@show');
$router->get('/torneos/torneo/tabla', 'TorneoController@tabla');
$router->get('/torneos/torneo/fixture', 'TorneoController@fixture');
$router->get('/torneos/crearTorneo', 'TorneoController@formCrearTorneo');
$router->get('/torneo/cargarEquipos', "TorneoController@formCargarEquipos");
$router->get('/torneo/cargarPartido', "TorneoController@formCargarPartido");
/* Post */
$router->post('/torneos/crearTorneo', 'TorneoController@crearTorneo');
$router->post('/torneo/cargarEquipos', "TorneoController@cargarEquipos");

$router->post('/torneo/cargarPartido', "TorneoController@cargarPartido");

/* ===== PARTIDOS ===== */
$router->get('/partidos', 'PartidoController@partidos');
$router->get('/partidos/partido', 'PartidoController@show');

$router->post('/partidos/cargarResultado', "PartidoController@cargarResultado");
/* ===== USUARIO ===== */
$router->get('/login', 'UsuarioController@formLogin');
$router->get('/cuenta/registrarse', 'UsuarioController@formSignUp');
$router->get('/cuenta/logout', 'UsuarioController@logout');
$router->get('/cuenta/perfil', 'UsuarioController@perfil');

/* Post */
$router->post('/login', 'UsuarioController@login');
$router->post('/cuenta/registrarse', 'UsuarioController@signUp');
$router->post('/cuenta/perfil', 'UsuarioController@updateperfil');




/* TORNEO(viejo supongo) */



$router->get('/competencia/partidos', 'PageController@partidos');

$router->get('/reglamento', 'PageController@reglamento');

$router->get('/contacto', 'PageController@contacto');

$router->get('/nosotros', 'PageController@nosotros');
