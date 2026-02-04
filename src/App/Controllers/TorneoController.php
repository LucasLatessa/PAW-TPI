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

    $title = $torneo['nombre'] . ' - LigaCF';

    echo $this->twig->render('torneos/torneo.view.twig', [
      'title' =>  $title,
      'torneo' => $torneo
    ]);
  }
}