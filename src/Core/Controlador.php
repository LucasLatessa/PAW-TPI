<?php

namespace Paw\Core;

use Paw\Core\Model;
use Paw\Core\Database\QueryBuilder;
use Paw\Core\Traits\Loggable;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Controlador
{
    public string $viewsDir; 
    public ?string $modelName = null;
    public array $rutasFooter;
    public array $rutasHeaderDer;
    public array $rutasLogoHeader;
    protected $model;
    protected $twig;
    protected $hayLogin;

    use Loggable;

    public $qb;

    public function __construct()
    {
        global $connection, $log;
        // ARRANCAMOS LA SESION
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Definimos el estado del login y validamos que no sea null
        if (!isset($_SESSION['login'])) {
            $_SESSION['login'] = false; 
        }
        $this->hayLogin = $_SESSION['login'];

        $this->viewsDir = __DIR__ . "/../App/Views/";

        $this->rutasLogoHeader = [
            "href" => '../',
            "name" => "Home",
        ];

        $this->rutasHeaderDer = [
            [
                "href" => '../competencia/tabla',
                "name" => "Tabla",
            ],
            [
                "href" => '../institucional/noticias',
                "name" => "Noticias"
            ],
            [
                "href" => '../competencia/partidos',
                "name" => "Partidos"
            ],
            [
                "href" => '../competencia/listaEquipos',
                "name" => "Equipos"
            ],
            [
                "href" => '../competencia/reglamento',
                "name" => "Reglamento"
            ]
        ];
        if ($this->hayLogin) {
            // Si esta logueado, mostramos perfil
            $this->rutasHeaderDer[] = ["href" => '/perfil', "name" => "Perfil"];
        } else {
            // Si no, mostramos login
            $this->rutasHeaderDer[] = ["href" => '../cuenta/login', "name" => "Login"];
        }

        $this->rutasFooter = [
            [
                "href" => '../institucional/nosotros',
                "name" => "Sobre nosotros"
            ],
            [
                "href" => '../institucional/contacto',
                "name" => "Contacto"
            ]
        ];

        // INICIALIZAMOS TWIG
        $loader = new FilesystemLoader($this->viewsDir);
        $this->twig = new Environment($loader, [
            'debug' => true,
        ]);

        // HACEMOS DISPONIBLES LAS VARIABLES GLOBALES
        $this->twig->addGlobal('hayLogin', $this->hayLogin);
        $this->twig->addGlobal('rutasHeaderDer', $this->rutasHeaderDer);
        $this->twig->addGlobal('rutasFooter', $this->rutasFooter);
        $this->twig->addGlobal('rutasLogoHeader', $this->rutasLogoHeader);
        $qb = new QueryBuilder($connection, $log);
        $this->qb = $qb;

        if (!is_null($this->modelName)) {
            
            $model = new $this->modelName;
            $model->setQueryBuilder($qb);
            $this->setModel($model);
        }
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    public function getQb(){
        return $this->qb;
    }

    public function getRutasFooter()
    {
        return $this->rutasFooter;
    }

    public function getRutasHeaderDer()
    {
        return $this->rutasHeaderDer;
    }

    public function getRutasLogoHeader()
    {
        return $this->rutasLogoHeader;
    }
}
