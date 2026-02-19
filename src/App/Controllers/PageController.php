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
      public function notFound(){
        http_response_code(404);
        $title = "Pagina no encontrada";
        echo $this->twig->render('not-found.view.twig', [
            'title' => $title,
            ]);
    }

    public function internalError()
    {
        http_response_code(500);
        $title = "Internal error";
        echo $this->twig->render('internal-error.view.twig', [
            'title' => $title,
            ]);
    }
}
