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


    // echo "<pre>";
    // print_r($partidos);
    // echo "</pre>";
    echo $this->twig->render('partidos/index.view.twig', [
      'title' => $title,
      'partidos' => $partidos,
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
