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


  public function show()
{
    global $request;
    $partido_id = $request->get('id');
    $title = 'Partido - LigaCF';
    $partido = $this->model->getPartido($partido_id);
    
    // Asumo que esto devuelve algo tipo "2026-02-25" o un objeto DateTime
    $fechaPartido = $partido->getFecha(); 
    $fechaBuscada = ($fechaPartido instanceof \DateTime) ? $fechaPartido->format('Y-m-d') : date('Y-m-d', strtotime($fechaPartido));

    $estadio = $partido->getEstadio();
    $climaPartido = null;

    if ($estadio->getLatitud() && $estadio->getLongitud()) {
        $weatherModel = new Weather($estadio->getLatitud(), $estadio->getLongitud());
        $pronostico = $weatherModel->getForecastWeather(30);
            echo "<pre>";
            print_r($pronostico);
            echo "</pre>";
            die();

        if (isset($pronostico['list'])) {
            foreach ($pronostico['list'] as $dia) {
                // Convertimos el timestamp del pronostico a fecha y comparamos
                $fechaPronostico = date('Y-m-d', $dia['dt']);
                
                if ($fechaPronostico === $fechaBuscada) {
                    $climaPartido = $dia;
                    break; // Cortamos el bucle cuando lo encontramos
                }
            }
        }
    }



    // Si no lo encontro (fuera de los 30 dias o error) climaPartido sera null
    echo $this->twig->render('partidos/show.view.twig', [
        'title'   => 'Partido - LigaCF',
        'partido' => $partido,
        'clima'   => $climaPartido, // Mandamos solo el dia especifico o null
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
