<?php

namespace Paw\App\Controllers;

use Paw\App\Models\PartidoCollections;
use Paw\Core\Controlador;

class PartidoController extends Controlador
{

  public ?string $modelName = PartidoCollections::class;
  
  // Ver lista de partidos en la Liga
  public function partidos()
  {
    $hayLogin = $_SESSION['login'];
    $title = 'Partidos - LigaCF';
    $partidos = $this->model->getAllPartidos();

    echo "<pre>";
    print_r($partidos);
    echo "</pre>";
    //var_dump($equipos);
    echo $this->twig->render('partidos/index.view.twig', [
      'title' => $title,
      'partidos' => $partidos,
    ]);
  }
  
}
