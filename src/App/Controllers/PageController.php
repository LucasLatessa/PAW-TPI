<?php

namespace Paw\App\Controllers;

use Paw\App\Models\NoticiaCollections;
use Paw\App\Models\PartidoCollections;
use Paw\App\Models\TorneoCollections;
use Paw\Core\Controlador;

class PageController extends Controlador
{
    protected $noticias;
    protected $torneo;

    // Instanciacion de modelos
    public function __construct()
    {
        parent::__construct();

        $this->torneo = new TorneoCollections();
        $this->torneo->setQueryBuilder($this->getQb());

        $this->noticias = new NoticiaCollections();
        $this->noticias->setQueryBuilder($this->getQb());
    }

  public function index()
  {
    $title = 'Home - LigaCF';

    $id_torneo = 1; //El primero (Primera Division)

    $tabla = $this->torneo->getTablaPosiciones($id_torneo); 
    $noticias = $this->noticias->getUltimasNoticias(3);
    $partidos = $this->torneo->getUltimosPartidos($id_torneo);

    echo $this->twig->render('index.view.twig', [
      'title' =>  $title,
      'tabla' => $tabla,
      "noticias" => $noticias,
      "partidos" => $partidos,
      "torneo_id" => $id_torneo
    ]);
  }

  public function notFound()
  {
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
