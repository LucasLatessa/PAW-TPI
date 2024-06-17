<?php

namespace Paw\App\Controllers;

use Paw\App\Models\Equipo;
use Paw\App\Models\EquipoCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class EquipoController extends Controlador{

    public ?string $modelName = EquipoCollections::class;

    public string $viewsDir; #Direccion a la vista indicada
    private $twig;
    
    #CONSTRUCTOR
    public function __construct()
    {
        parent::__construct();
        $loader = new FilesystemLoader(__DIR__ . '/../../App/Views');
        $this->twig = new Environment($loader);
    }

    public function datosEquipo(){
        global $request;
        $title = 'Equipos - LigaCF';
        $equipo_nombre = $request->get('nombre');
        $equipo = $this->model->get($equipo_nombre);
        echo $this->twig->render('competencia/equipos.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
            'equipo' => $equipo[0],
        ]);

    }



}