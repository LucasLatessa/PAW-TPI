<?php

namespace Paw\App\Controllers;

use Paw\App\Models\TorneoCollections;
use Paw\Core\Controlador;

class TorneoController extends Controlador
{

  public ?string $modelName = TorneoCollections::class;
  
  // Ver lista de torneos en la Liga
  public function torneos()
  {
    $hayLogin = $_SESSION['login'];
    $title = 'Torneos - LigaCF';
    $torneos = $this->model->getAllTorneos();

    //var_dump($equipos);
    echo $this->twig->render('torneos/index.view.twig', [
      'title' => $title,
      'torneos' => $torneos,
    ]);
  }

  // Muestra un torneo de la liga
  public function show()
  {
    global $request;

    $title = 'Torneo - LigaCF';

    $torneo_id = $request->get('id');
    $torneo = $this->model->getTorneo($torneo_id);
    $tabla = $this->model->getTablaPosiciones($torneo_id);
    $cantEquipos = $this->model->getCantidadEquipos($torneo_id);
    $ultimosPartidos = $this->model->getUltimosPartidos($torneo_id);

    $title = $torneo['nombre'] . ' - LigaCF';

    //print_r($tabla);

    echo $this->twig->render('torneos/torneo.view.twig', [
      'title' =>  $title,
      'torneo' => $torneo,
      'tabla' => $tabla,
      'cantEquipos' => $cantEquipos,
      'ultimosPartidos' => $ultimosPartidos
    ]);
  }

  // Muestro la tabla de un torneo
  public function tabla()
  {
    global $request;

    $title = 'Tabla - LigaCF';

    $torneo_id = $request->get('id');
    $torneo = $this->model->getTorneo($torneo_id);
    $tabla = $this->model->getTablaPosiciones($torneo_id);

    //print_r($tabla);

    echo $this->twig->render('torneos/tabla.view.twig', [
      'title' =>  $title,
      'torneo' => $torneo,
      'tabla' => $tabla
    ]);
  }
}