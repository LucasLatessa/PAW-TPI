<?php

namespace Paw\App\Controllers;

use Paw\App\Models\EquipoCollections;
use Paw\App\Models\EquipoTorneoCollections;
use Paw\App\Models\TorneoCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Paw\Core\Config;

class TorneoController extends Controlador{ 
    public ?string $modelName = TorneoCollections::class;
    public string $viewsDir; #Direccion a la vista indicada
    private $twig;

    public function __construct()
    {
        parent::__construct();
        $loader = new FilesystemLoader(__DIR__ . '/../../App/Views');
        $this->twig = new Environment($loader);
    }

    public function torneos() {
        $title = 'Torneos - LigaCF';
        $listaTorneos = $this->model->getAllTorneos(); 
    
        echo $this->twig->render('competencia/torneos.view.twig', [
            'title' => $title,
            'listaTorneos' => $listaTorneos,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter,
            #'listaEquipos' => $listaEquipos // Pasar la lista de equipos a la vista
        ]);
    }

    public function torneo()
    {
        global $request;

        $idTorneo = $request->get('id');
        $torneo = $this->model->getTorneo($idTorneo);
        $title = 'Torneos - LigaCF';

        /*Lista de equipos en torneo para ver la tabla*/

        $modelEquipoTorneo = new EquipoTorneoCollections();
        $modelEquipoTorneo->setQueryBuilder($this->getQb());
        $equiposTorneo = $modelEquipoTorneo->getAllEquipos($idTorneo);


        echo $this->twig->render('competencia/torneo.view.twig', [
            'title' => $title,
            'torneo' => $torneo,
            'equipos' => $equiposTorneo,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter,
            #'listaEquipos' => $listaEquipos // Pasar la lista de equipos a la vista
        ]);
    }

    public function crearTorneo()
    {
        global $request;
        //$modelTorneo = TorneoCollections::class; #ver si esto esta bien de usar otro modelo para TorneoColelctiones 
        // Obtener los datos del formulario
        $nombreTorneo = $request->getRequest('nombre_torneo');
        $fechaInicio = $request->getRequest('fechaInicio');
        $fechaFin = $request->getRequest('fechaFin');
        // $cantidadEquipos = $request->getRequest('cantidad_equipos');
        // $cantidadFechas = $request->getRequest('cantidad_fechas');
        // $descripcion = $request->getRequest('descripcion');

        $torneo = $this->model->create($nombreTorneo,$fechaInicio,$fechaFin);

        header('Location: /torneos');
        exit();
    }

    public function formCargarEquipo()
    {
        $title = 'Cargar Equipo - LigaCF';
        $listaTorneos = $this->model->getAllTorneos();
        $modelEquipos = new EquipoCollections();
        $modelEquipos->setQueryBuilder($this->getQb());
        $listaEquipos = $modelEquipos->getAllEquipos();

        echo $this->twig->render('liga/cargarEquipoTorneo.view.twig', [
            'title' => $title,
            'listaTorneos' => $listaTorneos,
            'listaEquipos' => $listaEquipos,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter,
            #'listaEquipos' => $listaEquipos // Pasar la lista de equipos a la vista
        ]);
    }

    public function cargarEquipo()
    {
        global $request;

        $torneo = $request->getRequest("id-torneo");
        $equipo = $request->getRequest("id-equipo");

        $modelEquipoTorneo = new EquipoTorneoCollections();
        $modelEquipoTorneo->setQueryBuilder($this->getQb());
        $equipoTorneo = $modelEquipoTorneo->create($torneo,$equipo);
    
        // var_dump($torneo);
        // var_dump($equipo);

        header('Location: /torneo?id=' . $torneo);
        exit();
    }


}