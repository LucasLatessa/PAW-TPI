<?php
namespace Paw\App\Controllers;

use Paw\App\Models\Equipo;
use Paw\App\Models\EquipoCollections;
use Paw\App\Models\EquipoTorneoCollections;
use Paw\App\Models\Partido;
use Paw\App\Models\PartidoCollections;
use Paw\App\Models\Torneo;
use Paw\App\Models\TorneoCollections;
use Paw\Core\Controlador;

class TorneoController extends Controlador
{

    public ?string $modelName = TorneoCollections::class;

    // Ver lista de torneos en la Liga
    public function torneos()
    {

        $hayLogin = $_SESSION['login'];
        $title    = 'Torneos - LigaCF';

        global $request;
        $paginaActual = (int) ($request->get('p') ?? 1);         // si no hay, es la 1
        $porPagina    = (int) ($request->get('per_page') ?? 4); // cantidad de equipos por pagina
        try {
            $torneos      = $this->model->getTorneosPaginados($paginaActual, $porPagina);
            $totalTorneos = $this->model->getTotalTorneos();
            $totalPaginas = ceil($totalTorneos / $porPagina);

            //var_dump($equipos);
            echo $this->twig->render('torneos/index.view.twig', [
                'title'        => $title,
                'torneos'      => $torneos,
                'paginaActual' => $paginaActual,
                'totalPaginas' => $totalPaginas,
                'porPagina'    => $porPagina,
            ]);
        } catch (\InvalidArgumentException $e) {
            header("Location: /torneos");
            exit;
        }


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

        $title = $torneo->getNombre() . ' - LigaCF';

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

    public function formTorneo()
    {
        global $request;

        $id              = $request->get('id');
        $torneo          = null;
        $todosLosEquipos = null;

        // Si hay ID, estoy en un update, sino en un create
        if ($id) {
            $torneo = $this->model->getTorneo($id);
            $title  = 'Editar torneo - LigaCF';
            $action = '/torneo/editar?id=' . $id;
        } else {
            $title  = 'Crear torneo - LigaCF';
            $action = '/torneos/crearTorneo';
            // traemos TODOS los equipos de la liga
            $equipoModel     = new EquipoCollections($this->model->queryBuilder);
            $todosLosEquipos = $equipoModel->getAllEquipos();
        }

        echo $this->twig->render('torneos/form.view.twig', [
            'title'   => $title,
            'equipos' => $todosLosEquipos,
            'torneo'  => $torneo,
            'action'  => $action,
            'isEdit'  => $id ? true : false,
        ]);
    }

    public function updateTorneo()
    {
        global $request;

        $id = $request->get('id');

        // obtener datos del torneo
        $nombreTorneo = $request->getRequest('nombre_torneo');
        $categoria    = $request->getRequest('categoria');
        $temporada    = $request->getRequest('temporada');
        $descripcion  = $request->getRequest('descripcion');
        $fechaInicio  = $request->getRequest('fechaInicio');
        $fechaFin     = $request->getRequest('fechaFin');

        $torneoAActualizar = new Torneo();
        $torneoAActualizar->set([
            'id'           => $id,
            'nombre'       => $nombreTorneo,
            'categoria'    => $categoria,
            'temporada'    => $temporada,
            'descripcion'  => $descripcion,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
        ]);
        $this->model->update($torneoAActualizar, $this->getQb());

        header('Location: /torneos/torneo?id=' . $id);
        exit();
    }

    public function crearTorneo()
    {
        global $request;

        // obtener datos del torneo
        $nombreTorneo = $request->getRequest('nombre_torneo');
        $categoria    = $request->getRequest('categoria');
        $temporada    = $request->getRequest('temporada');
        $descripcion  = $request->getRequest('descripcion');
        $fechaInicio  = $request->getRequest('fechaInicio');
        $fechaFin     = $request->getRequest('fechaFin');

        $torneoACrear = new Torneo();
        $torneoACrear->set([
            'nombre'       => $nombreTorneo,
            'categoria'    => $categoria,
            'temporada'    => $temporada,
            'descripcion'  => $descripcion,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
        ]);
        $nuevoTorneo = $this->model->create($torneoACrear);
        $torneoId    = $nuevoTorneo->getId();

        $equiposIds   = $request->getRequest('equipos_ids') ?? [];
        $crearFixture = $request->getRequest('crear_fixture'); // llega 1 si se marco
        if (! empty($equiposIds) && $torneoId) {
            $this->model->vincularEquiposAlTorneo($torneoId, $equiposIds);
            // generamos los partidos
            if ($crearFixture == "1") {
                $this->model->generarFixtureAutomatico($torneoId, $equiposIds);
            }
        }
        header('Location: /torneos/torneo?id=' . $torneoId);
        exit();
    }
    public function formCargarEquipos()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        global $request;
        $torneoId = $request->get('id');

        // buscamos los datos del torneo
        $torneo = $this->model->getTorneo($torneoId);

        // Obtenemos los equipos que YA pertenecen al torneo
        $equiposEnTorneo = $this->model->getAllEquipos($torneoId);

        // creamos un array  con los IDs de los equipos ya cargados
        $idsCargados = array_map(function ($equipoTorneo) {
            return $equipoTorneo->getId();
        }, $equiposEnTorneo);

        // traemos TODOS los equipos de la liga
        $equipoModel     = new EquipoCollections($this->model->queryBuilder);
        $todosLosEquipos = $equipoModel->getAllEquipos();

        // nos quedamos solo con los que NO estan en idsCargados
        $equiposDisponibles = array_filter($todosLosEquipos, function ($equipo) use ($idsCargados) {
            return ! in_array($equipo->getId(), $idsCargados);
        });

        // error en sesion
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        echo $this->twig->render('torneos/cargarEquipos.view.twig', [
            'title'   => 'Seleccionar Equipos - ' . $torneo->getNombre(),
            'torneo'  => $torneo,
            'equipos' => $equiposDisponibles,
            'error'   => $error,
        ]);
    }

    public function cargarEquipos()
    {
        global $request;
        $torneoId = $request->get('torneo_id');

        $equiposIds = $_POST['equipos_seleccionados'] ?? [];

        if (empty($equiposIds)) {
            // Iniciamos sesion si no esta iniciada
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Guardamos el mensaje
            $_SESSION['flash_error'] = "Tenés que seleccionar al menos un equipo.";

            header("Location: /torneo/cargarEquipos?id={$torneoId}");
            exit();
        }

        $exito = $this->model->vincularEquiposAlTorneo($torneoId, $equiposIds);

        if ($exito) {
            header("Location: /torneos/torneo?id={$torneoId}");
        } else {
            header("Location: /torneos/torneo?id={$torneoId}");
        }
    }

    public function formCargarPartido()
    {
        global $request;
        $idTorneo = $request->get('id');
        $title    = 'Cargar Partido - LigaCF';

        // Si TENEMOS ID, buscamos los equipos de ese torneo
        if ($idTorneo) {
            $torneo = $this->model->getTorneo($idTorneo);

            $modelEquipoTorneo = new EquipoTorneoCollections($this->getQb());
            $equiposTorneo     = $modelEquipoTorneo->getAllEquipos($idTorneo);

            $listaTorneos = null;
        } else {
            // Si NO TENEMOS ID, buscamos todos los torneos para que elija
            $torneo        = null;
            $equiposTorneo = null;
            $listaTorneos  = $this->model->getAllTorneos();
        }

        echo $this->twig->render('torneos/cargarPartido.view.twig', [
            'title'         => $title,
            'torneo'        => $torneo,
            'equiposTorneo' => $equiposTorneo,
            'listaTorneos'  => $listaTorneos, // Si esto no es null, muestra un select en la vista
        ]);
    }

    public function cargarPartido()
    {
        global $request;

        $idTorneo     = $request->getRequest("id-torneo");
        $fechaTorneo  = $request->getRequest("fecha-torneo");
        $idLocal      = $request->getRequest("id-equipo-local");
        $idVisitante  = $request->getRequest("id-equipo-visitante");
        $fecha        = $request->getRequest("fecha");
        $hora         = $request->getRequest("hora");
        $errorMessage = null;

        if ($idLocal === $idVisitante) {
            $errorMessage = "Un equipo no puede jugar contra sí mismo. Elegí un rival distinto.";
        } elseif ($fechaTorneo > 20) {
            $errorMessage = "El número de fecha no puede ser mayor a 20.";
        } elseif ($fechaTorneo < 1) {
            $errorMessage = "El número de fecha debe ser al menos 1.";
        }
        if ($errorMessage) {
            $torneoCollections = new TorneoCollections($this->getQb());
            $listaTorneos      = $torneoCollections->getAllTorneos();

            $equiposTorneo = [];
            if ($idTorneo) {
                $etCollections = new EquipoTorneoCollections($this->getQb());
                $equiposTorneo = $etCollections->getAllEquipos($idTorneo);
            }

            echo $this->twig->render('torneos/cargarPartido.view.twig', [
                'title'               => 'Crear Partido - LigaCF',
                'errorMessage'        => $errorMessage,
                'listaTorneos'        => $listaTorneos,
                'equiposTorneo'       => $equiposTorneo,
                'idSeleccionado'      => $idTorneo,
                'fecha_nro_ingresada' => $fechaTorneo,
                'fecha_dia_ingresada' => $fecha,
                'hora_ingresada'      => $hora,
            ]);
            return;
        }

        $equipoCollections = new EquipoCollections($this->model->queryBuilder);
        $equipo            = $equipoCollections->getID($idLocal);
        $estadio_id        = $equipo->getEstadioId();

        //die(var_dump($idTorneo, $fechaTorneo, $idLocal, $idVisitante, $fecha, $hora));
        //Creacion del partido
        $modelPartidoCollections = new PartidoCollections($this->getQb());
        $newPartido              = new Partido();
        $newPartido->set([
            'torneo_id'           => $idTorneo,
            'fecha_id'            => $fechaTorneo,
            'equipo_local_id'     => $idLocal,
            'equipo_visitante_id' => $idVisitante,
            'fecha_partido'       => $fecha,
            'hora_partido'        => $hora,
            'cancha'              => $estadio_id,
        ]);
        //var_dump($newPartido);
        //die();

        $partido   = $modelPartidoCollections->programarPartido($newPartido);
        $idPartido = $partido->getId();

        header('Location: /partidos/partido?id=' . $idPartido);
        exit();
    }

    // Fixture de ese torneo
    public function fixture()
    {
        global $request;
        $torneo_id = $request->get('id');
        $fecha_id  = $request->get('fecha');

        $torneo = $this->model->getTorneo($torneo_id);

        $fechas = $this->model->getFechasDeTorneo($torneo_id);
        if (empty($fechas)) {
            die("Este torneo no tiene partidos cargados.");
        }
        if (! isset($fecha_id)) {
            $fecha_id = $fechas[0]->getId();
        }

        $fechaActual = $this->model->getFecha($fecha_id);

        $partidos = $this->model->getPartidosFecha($torneo_id, $fecha_id);

        echo $this->twig->render('torneos/fixture.view.twig', [
            'title'       => 'Fixture - ' . $torneo->getNombre(),
            'torneo'      => $torneo,
            'fechas'      => $fechas,
            'fechaActual' => $fechaActual,
            'partidos'    => $partidos,
        ]);
    }
}
