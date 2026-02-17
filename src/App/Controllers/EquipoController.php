<?php

namespace Paw\App\Controllers;

use Paw\App\Models\Equipo;
use Paw\App\Models\EquipoCollections;
use Paw\App\Models\PartidoCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class EquipoController extends Controlador
{

  public ?string $modelName = EquipoCollections::class;

  // Ver lista de equipos en la Liga
  public function equipos()
  {
    $hayLogin = $_SESSION['login'];
    $title = 'Equipos - LigaCF';
    $equipos = $this->model->getAllEquipos();

    //var_dump($equipos);
    echo $this->twig->render('equipos/index.view.twig', [
      'title' => $title,
      'equipos' => $equipos, // Pasar la lista de equipos a la vista
    ]);
  }

  // Muestra un equipo del torneo
  public function show()
{
    global $request;
    
    $equipo_id = $request->get('id');
    $equipo = $this->model->getID($equipo_id);

    // Buscamos los partidos de este equipo
    $partidoModel = new PartidoCollections();
    $partidoModel->setQueryBuilder($this->getQb());
    
    // Este método lo creamos abajo
    $ultimosPartidos = $partidoModel->getPartidosByEquipo($equipo_id);
    echo $this->twig->render('equipos/show.view.twig', [
      'title'   => $equipo->getNombre() . ' - LigaCF',
      'equipo'  => $equipo,
      'ultimosPartidos' => $ultimosPartidos 
    ]);
}
  public function formCrearEquipo(){
    $title = 'Crear equipo - LigaCF';
    echo $this->twig->render('equipos/crearEquipo.view.twig', [
        'title' =>  $title,
    ]);
  }
  
  public function crearEquipo()
  {
    global $request;

    $nombreEquipo = $request->getRequest('equipo');
    $nombreInstitucionalEquipo = $request->getRequest('institucional');
    $fechaCreacion = $request->getRequest('fecha');
    $nombreEstadio = $request->getRequest('estadio');
    $descripcion = $request->getRequest('descripcion');

    $nombreArchivo = $this->subirImagen($_FILES, 'escudos');
    

    if ($nombreArchivo !== false) {

      $this->model->create($nombreEquipo, $nombreInstitucionalEquipo, $fechaCreacion, $nombreEstadio, $descripcion, $nombreArchivo);

      header('Location: /equipos');
      exit();
    } else {
      $errorMessage = "La imagen excede el tamaño permitido (1MB) o hubo un error en la carga.";
      $title = "Cargar Equipo - Liga";

      echo $this->twig->render('equipo/crearEquipo.view.twig', [
        'title' => $title,
        'errorMessage' => $errorMessage,

        'equipo_ingresado' => $nombreEquipo,
        'institucional_ingresado' => $nombreInstitucionalEquipo,
        'fecha_ingresada' => $fechaCreacion,
        'estadio_ingresado' => $nombreEstadio,
        'descripcion_ingresada' => $descripcion
      ]);
    }
  }
}
