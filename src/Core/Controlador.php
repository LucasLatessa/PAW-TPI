<?php

namespace Paw\Core;

use Paw\Core\Model;
use Paw\Core\Database\QueryBuilder;
use Paw\Core\Traits\Loggable;

class Controlador
{
    public string $viewsDir; 
    public ?string $modelName = null;
    public array $rutasMenuBurger;
    public array $rutasFooter;
    public array $rutasHeaderDer;
    public array $rutasLogoHeader;
    protected $model;

    use Loggable;

    public $qb;

    public function __construct()
    {
        global $connection, $log;

        $this->viewsDir = __DIR__ . "/../App/views/";

        $this->rutasMenuBurger = [
            [
                "href" => '../compra/menu',
                "name" => "Menu",
            ],
            [
                "href" => '../compra/reserva',
                "name" => "Reserva mesa",
            ],
            [
                "href" => '../cuenta/perfil',
                "name" => "Perfil"
            ]
        ];

        $this->rutasLogoHeader = [
            "href" => '../',
            "name" => "Home",
        ];

        $this->rutasHeaderDer = [
            [
                "href" => '../compra/carrito',
                "name" => "carrito",
            ],
            [
                "href" => '../cuenta/login',
                "name" => "usuario"
            ],
        ];

        $this->rutasFooter = [
            [
                "href" => '../institucional/locales',
                "name" => "Locales"
            ],
            [
                "href" => '../institucional/servCliente',
                "name" => "Servicio al Cliente"
            ],
            [
                "href" => '../institucional/nosotros',
                "name" => "Sobre nosotros"
            ],
            [
                "href" => '../cuenta/consumos',
                "name" => "Consumos"
            ]
        ];

        $qb = new QueryBuilder($connection, $log);

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

    public function getRutasMenuBurger()
    {
        return $this->rutasMenuBurger;
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
