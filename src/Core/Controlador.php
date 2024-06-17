<?php

namespace Paw\Core;

use Paw\Core\Model;
use Paw\Core\Database\QueryBuilder;
use Paw\Core\Traits\Loggable;

class Controlador
{
    public string $viewsDir; 
    public ?string $modelName = null;
    public array $rutasFooter;
    public array $rutasHeaderDer;
    public array $rutasLogoHeader;
    protected $model;

    use Loggable;

    public $qb;

    public function __construct()
    {
        global $connection, $log;

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
            ],
            [
                "href" => '../cuenta/login',
                "name" => "Login"
            ]
        ];

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
