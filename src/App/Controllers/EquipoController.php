<?php
namespace Paw\App\Controllers;

use Paw\App\Models\EquipoCollections;
use Paw\App\Models\Equipo;
use Paw\App\Models\Estadio;
use Paw\App\Models\EquipoTorneoCollections;
use Paw\App\Models\EstadioCollections;
use Paw\App\Models\PartidoCollections;
use Paw\Core\Controlador;

class EquipoController extends Controlador
{

    public ?string $modelName = EquipoCollections::class;

    // Ver lista de equipos en la Liga
    public function equipos()
    {
        global $request;
        $paginaActual = $request->get('p') ?? 1;         // si no hay, es la 1
        $porPagina    = $request->get('per_page') ?? 12; // cantidad de equipos por pagina

        $equipos      = $this->model->getEquiposPaginados($paginaActual, $porPagina);
        $totalEquipos = $this->model->getTotalEquipos();
        $totalPaginas = ceil($totalEquipos / $porPagina);

        echo $this->twig->render('equipos/index.view.twig', [
            'title'        => 'Equipos - LigaCF',
            'equipos'      => $equipos,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas,
            'porPagina'    => $porPagina,
        ]);
    }

    // Muestra un equipo del torneo
    public function show()
    {
        global $request;

        $equipo_id = $request->get('id');
        $equipo    = $this->model->getID($equipo_id);

        // Modelos tabla y partido
        $partidoModel = new PartidoCollections();
        $partidoModel->setQueryBuilder($this->getQb());
        $tablaModel = new EquipoTorneoCollections();
        $tablaModel->setQueryBuilder($this->getQb());
        $torneo_id = $tablaModel->getLastTorneo($equipo_id);

        $ultimosPartidos = [];
        $posicion        = null;
        $tabla           = null;
        $totalEquipos    = 0;
        $proxPartido     = null;
        if ($torneo_id) {

            // Buscamos los partidos de este equipo
            $ultimosPartidos = $partidoModel->getPartidosByEquipo($equipo_id);

            //Posicion actual en primera
            $posicion = $tablaModel->getPosicion($torneo_id, $equipo_id);

            //Tabla de posiciones en ese torneo
            $tabla = $tablaModel->getEstadisticas($torneo_id, $equipo_id);

            //Total de equipos en ese torneo
            $totalEquipos = $tablaModel->getCantidadEquipos($torneo_id);

            //Proximo partido (si existe)
            $proxPartido = $partidoModel->getProximoPartido($torneo_id, $equipo_id);

        }

        echo $this->twig->render('equipos/show.view.twig', [
            'title'           => $equipo->getNombre() . ' - LigaCF',
            'equipo'          => $equipo,
            'ultimosPartidos' => $ultimosPartidos,
            'tabla'           => $tabla,
            'posicion'        => $posicion,
            'totalEquipos'    => $totalEquipos,
            'proxPartido'     => $proxPartido,
        ]);
    }
    public function formCrearEquipo()
    {
        $title = 'Crear equipo - LigaCF';
        echo $this->twig->render('equipos/crearEquipo.view.twig', [
            'title' => $title,
        ]);
    }

    public function crearEquipo()
    {
        global $request;

        $nombreEquipo              = $request->getRequest('equipo');
        $latitud                   = $request->getRequest('estadio_lat');
        $longitud                  = $request->getRequest('estadio_lng');
        $nombreInstitucionalEquipo = $request->getRequest('institucional');
        $fechaCreacion             = $request->getRequest('fecha');
        $nombreEstadio             = $request->getRequest('estadio');
        $descripcion               = $request->getRequest('descripcion');

        $errorMessage = null;

        /* si el metodo es POST pero el nombre esta vacio, es porque PHP descarto el POST por exceso de tamaño*/
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($nombreEquipo) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
            $errorMessage = "La imagen excede el tamaño máximo permitido.";
        }
        // validamos si el campo imagen existe y si tiene un error
        elseif (! isset($_FILES['imagen']) || $_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE) {
            $errorMessage = "Tenés que subir un escudo para el equipo.";
        } else {
            // si hay archivo, intentamos subirlo(aca valida el tamaño de 1MB)
            $nombreArchivo = $this->subirImagen($_FILES, 'escudos');

            if ($nombreArchivo !== false) {
                //creamos estadio
                $estadioCollections = new EstadioCollections();
                $estadioACrear = new Estadio();
                $estadioACrear->set([
                    'nombre'   => $nombreEstadio,
                    'latitud'  => $latitud,
                    'longitud' => $longitud
                ]);
                $estadioCollections->setQueryBuilder($this->getQb());
                $estadio = $estadioCollections->create($estadioACrear, $this->getQb());

                // creamos objeto estadio y luego lo pasamos al create
                $equipoACrear = new Equipo();
                $equipoACrear->set([
                    'nombre'               => $nombreEquipo,
                    'nombre_institucional' => $nombreInstitucionalEquipo,
                    'fecha_creacion'       => $fechaCreacion,
                    'estadio_id'           => $estadio->getId(),
                    'descripcion'          => $descripcion,
                    'escudo'               => 'escudos/' . $nombreArchivo,
                ]);
                $nuevoEquipo = $this->model->create($equipoACrear, $this->getQb());
                header("Location: /equipos/equipo?id=" . $nuevoEquipo->getId());
                exit();
            } else {
                $errorMessage = "El escudo excede el tamaño permitido (máx 1MB) o el formato no es válido.";
            }
        }

        // si llegamos aca es porque hubo un error
        $title = "Cargar Equipo - Liga";
        echo $this->twig->render('equipos/crearEquipo.view.twig', [
            'title'                   => $title,
            'errorMessage'            => $errorMessage,
            'equipo_ingresado'        => $nombreEquipo,
            'institucional_ingresado' => $nombreInstitucionalEquipo,
            'fecha_ingresada'         => $fechaCreacion,
            'estadio_ingresado'       => $nombreEstadio,
            'descripcion_ingresada'   => $descripcion,
        ]);
    }
}
