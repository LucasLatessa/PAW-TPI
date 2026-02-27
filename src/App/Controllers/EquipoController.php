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

        try{
            $equipo    = $this->model->getID($equipo_id);
        } catch (\Exception $e) {
            http_response_code(404);
            $title = "Pagina no encontrada";
            echo $this->twig->render('not-found.view.twig', [
              'title' => $title,
            ]);
            return;
        };

        // Modelos tabla y partido
        $partidoModel = new PartidoCollections($this->getQb());
        $tablaModel = new EquipoTorneoCollections($this->getQb());
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
    public function formEquipo()
    {
      global $request;

      $id = $request->get('id');
      $equipo = null;
      $estadio = null;

      // Si hay ID, estoy en un update, sino en un create
      if ($id) {
          $equipo = $this->model->getID($id);
          $estadio = (new EstadioCollections($this->getQb()))->getByID($equipo->getEstadioId());
          $title = 'Editar Equipo - LigaCF';
          $action = '/equipos/editar?id=' . $id;
      } else {
          $title = 'Crear Equipo - LigaCF';
          $action = '/equipos/crearEquipo';
      }

      echo $this->twig->render('equipos/form.view.twig', [
        'title' => $title,
        'equipo' => $equipo,
        'estadio' => $estadio,
        'action'  => $action,
        'isEdit'  => $id ? true : false
      ]);
    }

    public function updateEquipo()
    {
        global $request;

        $id                        = $request->get('id');
        $nombreEquipo              = $request->getRequest('equipo');
        $nombreEstadio             = $request->getRequest('estadio');
        $latitud                   = $request->getRequest('estadio_lat');
        $longitud                  = $request->getRequest('estadio_lng');
        $nombreInstitucionalEquipo = $request->getRequest('institucional');
        $fechaCreacion             = $request->getRequest('fecha');
        $descripcion               = $request->getRequest('descripcion');

        $errorMessage = null;

        // Traigo el escudo actual por defecto
        $nombreImagen = $this->model->getEscudo($id); 

        /* si el metodo es POST pero el nombre esta vacio, es porque PHP descarto el POST por exceso de tamaño*/
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($nombreEquipo) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
            $errorMessage = "La imagen excede el tamaño máximo permitido.";
        }

        // Si el usuario subió un escudo nuevo
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
          $nuevaImagen = $this->subirImagen($_FILES, 'escudos');

          if ($nuevaImagen !== false) {
              $nombreImagen = $nuevaImagen;

              // Borrar imagen vieja
              $imagen = $this->model->getEscudo($id);
              if ($imagen) {
                  $rutaImagen = __DIR__ . '/../../../public/assets/escudos/' . $imagen;

                  if (file_exists($rutaImagen)) {
                      unlink($rutaImagen);
                  }
              }
          } else {
              $errorMessage = "La imagen excede el tamaño permitido (máx 1MB) o el formato no es válido.";
          }

          // Actualizar estadio
          $estadioCollections = new EstadioCollections($this->getQb());
          $estadioACrear = new Estadio();
          $estadioACrear->set([
              'nombre'   => $nombreEstadio,
              'latitud'  => $latitud,
              'longitud' => $longitud
          ]);

          // Creamos objeto con datos actualizados
          $equipoAActualizar = new Equipo();
          $equipoAActualizar->set([
              'id'                   => $id,
              'nombre'               => $nombreEquipo,
              'nombre_institucional' => $nombreInstitucionalEquipo,
              'fecha_creacion'       => $fechaCreacion,
              'estadio_id'           => null, // Lo actualizamos despues porque necesitamos crear el estadio nuevo
              'descripcion'          => $descripcion,
              'escudo'               => $nombreImagen,
          ]);

          // Si no hubo error → actualizamos
          if (!$errorMessage) {

              $estadioCollections->delete($this->model->getEstadio($id), $this->getQb()); // Borro el estadio viejo
              //var_dump($this->model->getEstadio($id));
              
              //Creo el estadio nuevo y se lo asigno al equipo
              $estadio = $estadioCollections->create($estadioACrear);
              $equipoAActualizar->set(['estadio_id' => $estadio->getId()]);

              $this->model->update($equipoAActualizar, $this->getQb());

              header('Location: /equipos/equipo?id=' . $id);
              exit();

          }

          // Error en la carga
          $title = 'Editar equipo - LigaCF';
          //var_dump($estadioACrear);

          echo $this->twig->render('equipos/form.view.twig', [
              'title'        => $title,
              'errorMessage' => $errorMessage,
              'equipo'       => $equipoAActualizar,
              'estadio'      => $estadioACrear,
              'isEdit'       => true,
              'action'       => '/equipos/editar?id=' . $id
          ]);


        }
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
                $estadioCollections = new EstadioCollections($this->getQb());
                $estadioACrear = new Estadio();
                $estadioACrear->set([
                    'nombre'   => $nombreEstadio,
                    'latitud'  => $latitud,
                    'longitud' => $longitud
                ]);
                $estadio = $estadioCollections->create($estadioACrear);

                // creamos objeto estadio y luego lo pasamos al create
                $equipoACrear = new Equipo();
                $equipoACrear->set([
                    'nombre'               => $nombreEquipo,
                    'nombre_institucional' => $nombreInstitucionalEquipo,
                    'fecha_creacion'       => $fechaCreacion,
                    'estadio_id'           => $estadio->getId(),
                    'descripcion'          => $descripcion,
                    'escudo'               => $nombreArchivo,
                ]);
                $nuevoEquipo = $this->model->create($equipoACrear);
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
