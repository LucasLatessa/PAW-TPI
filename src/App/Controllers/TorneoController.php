<?php
namespace Paw\App\Controllers;

use Paw\App\Models\TorneoCollections;
use Paw\App\Models\EquipoTorneoCollections;
use Paw\App\Models\PartidoCollections;
use Paw\Core\Controlador;

class TorneoController extends Controlador
{

    public ?string $modelName = TorneoCollections::class;

    // Ver lista de torneos en la Liga
    public function torneos()
    {
        $hayLogin = $_SESSION['login'];
        $title    = 'Torneos - LigaCF';
        $torneos  = $this->model->getAllTorneos();
        $hayLogin = $_SESSION['login'];

        //var_dump($equipos);
        echo $this->twig->render('torneos/index.view.twig', [
            'title'   => $title,
            'torneos' => $torneos,
        ]);
    }

    // Muestra un torneo de la liga
    public function show()
    {
        global $request;

        $title = 'Torneo - LigaCF';

        $torneo_id       = $request->get('id');
        $torneo          = $this->model->getTorneo($torneo_id);
        $tabla           = $this->model->getTablaPosiciones($torneo_id);
        $cantEquipos     = $this->model->getCantidadEquipos($torneo_id);
        $ultimosPartidos = $this->model->getUltimosPartidos($torneo_id);

        $title = $torneo['nombre'] . ' - LigaCF';

        //print_r($tabla);

        echo $this->twig->render('torneos/torneo.view.twig', [
            'title'           => $title,
            'torneo'          => $torneo,
            'tabla'           => $tabla,
            'cantEquipos'     => $cantEquipos,
            'ultimosPartidos' => $ultimosPartidos,
        ]);
    }

    // Muestro la tabla de un torneo
    public function tabla()
    {
        global $request;

        $title = 'Tabla - LigaCF';

        $torneo_id = $request->get('id');
        $torneo    = $this->model->getTorneo($torneo_id);
        $tabla     = $this->model->getTablaPosiciones($torneo_id);

        //print_r($tabla);

        echo $this->twig->render('torneos/tabla.view.twig', [
            'title'  => $title,
            'torneo' => $torneo,
            'tabla'  => $tabla,
        ]);
    }

    public function formCrearTorneo()
    {
        $title = 'Crear torneo - LigaCF';
        echo $this->twig->render('torneos/crearTorneo.view.twig', [
            'title' => $title,
        ]);
    }

    public function crearTorneo()
    {
        global $request;
        //$modelTorneo = TorneoCollections::class; #ver si esto esta bien de usar otro modelo para TorneoColelctiones
        // Obtener los datos del formulario
        $nombreTorneo = $request->getRequest('nombre_torneo');
        $categoria    = $request->getRequest('categoria');
        $temporada    = $request->getRequest('temporada');
        $descripcion  = $request->getRequest('descripcion');
        $fechaInicio  = $request->getRequest('fechaInicio');
        $fechaFin     = $request->getRequest('fechaFin');
        // $cantidadEquipos = $request->getRequest('cantidad_equipos');
        // $cantidadFechas = $request->getRequest('cantidad_fechas');

        $torneo = $this->model->create($nombreTorneo, $categoria, $temporada, $descripcion, $fechaInicio, $fechaFin);

        header('Location: /torneos');
        exit();
    }

    public function formCargarPartido()
    {
        global $request;

        $idTorneo = $request->get('id');
       
        $title    = 'Cargar Partido - LigaCF';
        $torneo   = $this->model->getTorneo($idTorneo);
        //$listaTorneos = $this->model->getAllTorneos();

        $modelEquipoTorneo = new EquipoTorneoCollections();
        $modelEquipoTorneo->setQueryBuilder($this->getQb());
        $equiposTorneo = $modelEquipoTorneo->getAllEquipos($idTorneo);
        echo $this->twig->render('torneos/cargarPartido.view.twig', [
            'title'         => $title,
            'torneo'        => $torneo,
            'equiposTorneo' => $equiposTorneo,
            #'listaEquipos' => $listaEquipos // Pasar la lista de equipos a la vista
        ]);
    }

    public function cargarPartido()
    {
        global $request;

        $idTorneo    = $request->getRequest("id-torneo");
        $fechaTorneo = $request->getRequest("fecha-torneo");
        $idLocal     = $request->getRequest("id-equipo-local");
        $idVisitante = $request->getRequest("id-equipo-visitante");
        $fecha       = $request->getRequest("fecha");
        $hora        = $request->getRequest("hora");
        
        //die(var_dump($idTorneo, $fechaTorneo, $idLocal, $idVisitante, $fecha, $hora));
        //Creacion del partido
        $modelPartidoCollections = new PartidoCollections();
        $modelPartidoCollections->setQueryBuilder($this->getQb());

        $partido = $modelPartidoCollections->programarPartido($idTorneo, $fechaTorneo, $idLocal, $idVisitante, $fecha, $hora);

        header('Location: /torneos/torneo?id=' . $idTorneo);
        exit();
    }
    public function formCargarResultado()
    {
        global $request;

        $idTorneo = $request->get('id');
        $title    = 'Cargar Resultado - LigaCF';
        $torneo   = $this->model->getTorneo($idTorneo);
        //$listaTorneos = $this->model->getAllTorneos();

        $modelPartido = new PartidoCollections();
        $modelPartido->setQueryBuilder($this->getQb());
        $partidosACargar = $modelPartido->getPartidosACargar($idTorneo);

        echo $this->twig->render('liga/cargarResultados.view.twig', [
            'title'           => $title,
            'torneo'          => $torneo,
            'partidosACargar' => $partidosACargar,
            #'listaEquipos' => $listaEquipos // Pasar la lista de equipos a la vista
        ]);
    }

    public function cargarResultado()
    {
        global $request;

        $idTorneo       = $request->getRequest("torneo_id");
        $idPartido      = $request->getRequest("id_partido");
        $golesLocal     = $request->getRequest("golesLocal");
        $golesVisitante = $request->getRequest("golesVisitante");
        if ($idPartido && $golesLocal !== null && $golesVisitante !== null) {
            $partidoCollection = new PartidoCollections();
            $partidoCollection->setQueryBuilder($this->getQb());
            $partidoCollection->cargarResultado($idTorneo, $idPartido, $golesLocal, $golesVisitante);
        }
        header("Location: /torneo/cargarResultado?id=$idTorneo");
        exit;
    }

    // Fixture de ese torneo
    public function fixture()
    {
        global $request;

        $title = 'Fixture - LigaCF';

        $torneo_id = $request->get('id');
        $fecha_id  = $request->get('fecha');
        $torneo    = $this->model->getTorneo($torneo_id);
        $fechas    = $this->model->getFixture($torneo_id);

        if (! isset($fecha_id)) {
            $fecha_id = 1; //Que tome la primera
        }

        //var_dump($fecha_id);

        $fechaActual = $this->model->getFecha($torneo_id, $fecha_id);
        $partidos    = $this->model->getPartidosFecha($torneo_id, $fecha_id);

        //print_r($fechaActual);

        echo $this->twig->render('torneos/fixture.view.twig', [
            'title'       => $title,
            'torneo'      => $torneo,
            'fechas'      => $fechas,
            'fechaActual' => $fechaActual,
            'partidos'    => $partidos,
        ]);
    }
}
