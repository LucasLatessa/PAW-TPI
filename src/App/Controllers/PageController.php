<?php

namespace Paw\App\Controllers;

use Paw\App\Models\EquipoTorneoCollections;
use Paw\App\Models\NoticiaCollections;
use Paw\App\Models\TorneoCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class PageController extends Controlador
{

  public function index()
  {
    $title = 'Home - LigaCF';
    echo $this->twig->render('index.view.twig', [
      'title' =>  $title
    ]);
  }
}
