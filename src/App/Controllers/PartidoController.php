<?php

namespace Paw\App\Controllers;

use Paw\App\Models\PartidoCollections;
use Paw\App\Models\TorneoCollections;
use Paw\App\Models\Estadio;
use Paw\Core\Controlador;
use Paw\Core\Utils\Weather;
class PartidoController extends Controlador
{

  public ?string $modelName = PartidoCollections::class;
  
  // Ver lista de partidos en la Liga
 public function partidos()
{
    global $request;
    $hayLogin = $_SESSION['login'] ?? false;
    
    // Paginacion
    $paginaActual = $request->getRequest('p') ?: 1;
    $porPagina    = $request->get('per_page') ?? 4;// cantidad de partidos por pagina

    $filters = [
      'categoria' => $request->getRequest('categoria'),
      'fecha'     => $request->getRequest('fecha'),
      'estado'    => $request->getRequest('estado'),
    ];

    $partidos = $this->model->getPartidosPaginados($filters, $paginaActual, $porPagina);
    $totalPartidos = $this->model->getTotalPartidos($filters);
    $totalPaginas = ceil($totalPartidos / $porPagina);

    $torneoModel = new TorneoCollections();
    $torneoModel->setQueryBuilder($this->model->queryBuilder);
    $categorias = $torneoModel->getCategorias();

    echo $this->twig->render('partidos/index.view.twig', [
      'title' => 'Partidos - LigaCF',
      'partidos' => $partidos,
      'categorias' => $categorias,
      'filters' => $filters,
      'paginaActual' => $paginaActual,
      'totalPaginas' => $totalPaginas,
      'porPagina'    => $porPagina
    ]);
}

  // Muestra un partido
  public function show()
  {
    global $request;

    $title = 'Partido - LigaCF';

    $partido_id = $request->get('id');
    $partido = $this->model->getPartido($partido_id);
    $estadio = $partido->getEstadio();
    $estadioLatitud =  $estadio->getLatitud();
    $estadioLongitud =  $estadio->getLongitud();

    if ( $estadioLatitud && $estadioLongitud){
      $weatherModel = new Weather($estadioLatitud, $estadioLongitud);
      $clima = $weatherModel->getCurrentWeather();
    } else{
      $clima = 'No se encontro el clima';
    }
    
    echo $this->twig->render('partidos/show.view.twig', [
      'title' =>  $title,
      'partido' => $partido,
      'clima' => $clima,
      'estadio' => $estadio
    ]);
  }
  public function definirHorario()
{
    global $request;

    $idPartido = $request->get('id_partido');
    $fecha = $request->get('fecha_partido');
    $hora = $request->get('hora_partido');

    // Validacion
    if (!$idPartido || !$fecha || !$hora) {
        header("Location: /partidos/partido?id={$idPartido}&error=faltan_datos");
        return;
    }

    $resultado = $this->model->updateHorario($idPartido, $fecha, $hora);

    if ($resultado) {
        header("Location: /partidos/partido?id={$idPartido}");
    } else {
        header("Location: /partidos/partido?id={$idPartido}&error=db");
    }
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
