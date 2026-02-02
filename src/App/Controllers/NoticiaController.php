<?php

namespace Paw\App\Controllers;

use Paw\App\Models\EquipoTorneoCollections;
use Paw\App\Models\NoticiaCollections;
use Paw\App\Models\TorneoCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class NoticiaController extends Controlador
{

  public ?string $modelName = NoticiaCollections::class;

  // Ver lista de noticias en la Liga
  public function noticias()
  {
    $hayLogin = $_SESSION['login'];
    $title = 'Noticias - LigaCF';
    $noticias = $this->model->getAllNoticias();

    echo $this->twig->render('noticias/index.view.twig', [
      'title' =>  $title,
      'noticias' => $noticias,
    ]);
  }

  // Muestra una noticia
  public function show()
  {
    global $request;

    $noticia_id = $request->get('id');
    $noticia = $this->model->getID($noticia_id);

    $title = $noticia['titulo'] . ' - LigaCF';

    echo $this->twig->render('noticias/show.view.twig', [
      'title' =>  $title,
      'noticia' => $noticia
    ]);
  }
}
