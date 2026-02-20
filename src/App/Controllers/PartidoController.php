<?php

namespace Paw\App\Controllers;

use Paw\App\Models\PartidoCollections;
use Paw\App\Models\TorneoCollections;
use Paw\Core\Controlador;

class PartidoController extends Controlador
{

  public ?string $modelName = PartidoCollections::class;
  
  // Ver lista de partidos en la Liga
  public function partidos()
  {
    $hayLogin = $_SESSION['login'];
    $title = 'Partidos - LigaCF';

    global $request;

    $filters = [
      'categoria' => $request->getRequest('categoria'),
      'fecha'     => $request->getRequest('fecha'),
      'estado'    => $request->getRequest('estado'),
    ];

    $partidos = $this->model->getAllPartidos($filters);

    $torneoModel = new TorneoCollections();
    $torneoModel->setQueryBuilder($this->model->queryBuilder);
    $categorias = $torneoModel->getCategorias();

    // echo "<pre>";
    // print_r($categorias);
    // echo "</pre>";
    echo $this->twig->render('partidos/index.view.twig', [
      'title' => $title,
      'partidos' => $partidos,
      'categorias' => $categorias,
      'filters' => $filters
    ]);
  }

  // Muestra un partido
  public function show()
  {
    global $request;

    $title = 'Partido - LigaCF';

    $partido_id = $request->get('id');
    $partido = $this->model->getPartido($partido_id);
    
    
    //print_r($tabla);

    echo $this->twig->render('partidos/show.view.twig', [
      'title' =>  $title,
      'partido' => $partido
    ]);
  }
  
    // Cargar resultado de un partido
    public function cargarResultado()
    {
        global $request;

        $idPartido      = $request->getRequest("id_partido");
        $golesLocal     = $request->getRequest("goles_local");
        $golesVisitante = $request->getRequest("goles_visitante");

        // Partido sin resultado
        if ($idPartido && $golesLocal !== null && $golesVisitante !== null) {
            $partidoCollection = new PartidoCollections();
            $partidoCollection->setQueryBuilder($this->getQb());
            $partidoCollection->cargarResultado($idPartido, $golesLocal, $golesVisitante);
        }
        
        header("Location: /partidos/partido?id=$idPartido");
        exit;
    }
  
}
