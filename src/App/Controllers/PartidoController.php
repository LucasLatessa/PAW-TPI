<?php
namespace Paw\App\Controllers;

use Paw\App\Models\PartidoCollections;
use Paw\App\Models\TorneoCollections;
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
        $paginaActual = (int) ($request->get('p') ?? 1);         // si no hay, es la 1
        $porPagina    = (int) ($request->get('per_page') ?? 4); // cantidad de partidos por pagina

        $filters = [
            'categoria' => $request->getRequest('categoria'),
            'fecha'     => $request->getRequest('fecha'),
            'estado'    => $request->getRequest('estado'),
        ];

        try {
            $partidos      = $this->model->getPartidosPaginados($filters, $paginaActual, $porPagina);
            $totalPartidos = $this->model->getTotalPartidos($filters);
            $totalPaginas  = ceil($totalPartidos / $porPagina);

            $torneoModel = new TorneoCollections($this->model->queryBuilder);
            $categorias = $torneoModel->getCategorias();

            echo $this->twig->render('partidos/index.view.twig', [
                'title'        => 'Partidos - LigaCF',
                'partidos'     => $partidos,
                'categorias'   => $categorias,
                'filters'      => $filters,
                'paginaActual' => $paginaActual,
                'totalPaginas' => $totalPaginas,
                'porPagina'    => $porPagina,
            ]);
        } catch (\InvalidArgumentException $e) {
            header("Location: /partidos");
            exit;
        }

       
    }

    public function show()
    {
        global $request;
        
        $partido_id = $request->get('id');
        $title      = 'Partido - LigaCF';
        $partido    = $this->model->getPartido($partido_id);
        $errorMessage = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        $fechaPartido = $partido->getFechaPartido();
        $horaPartido  = $partido->getHoraPartido();

        $timestamp = strtotime($fechaPartido . " " . $horaPartido);

        $estadio         = $partido->getEstadio();
        $masCercano      = null;
        $menorDiferencia = null;

        if ($estadio->getLatitud() && $estadio->getLongitud()) {
            $weatherModel = new Weather($estadio->getLatitud(), $estadio->getLongitud());
            $pronostico   = $weatherModel->getForecastWeather(30);

            if (isset($pronostico['list'])) {
                foreach ($pronostico['list'] as $dia) {
                    if (! isset($dia['dt'])) {
                        continue;
                    }

                    $diferencia = abs($dia['dt'] - $timestamp);

                    if ($menorDiferencia === null || $diferencia < $menorDiferencia) {
                        $menorDiferencia = $diferencia;
                        $masCercano      = $dia;
                    }
                }
            }
        }
        // Si no lo encontro (fuera de los 30 dias o error) climaPartido sera null
        echo $this->twig->render('partidos/show.view.twig', [
            'title'   => 'Partido - LigaCF',
            'partido' => $partido,
            'clima'   => $masCercano,
            'estadio' => $estadio,
            'errorMessage' => $errorMessage,
        ]);
    }

    public function definirHorario()
    {
        global $request;

        $idPartido = $request->get('id_partido');
        $fecha     = $request->get('fecha_partido');
        $hora      = $request->get('hora_partido');

        // Validacion
        if (! $idPartido || ! $fecha || ! $hora) {
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

        // validamos que no sean nulos, que sean numericos y que no sean negativos
        $datos = ($golesLocal !== null && $golesVisitante !== null);
        $golesValidos   = $datos && is_numeric($golesLocal) && $golesLocal >= 0 && is_numeric($golesVisitante) && $golesVisitante >= 0;

        if ($idPartido && $golesValidos) {
            $partidoCollection = new PartidoCollections($this->getQb());
            $partidoCollection->cargarResultado($idPartido, (int) $golesLocal, (int) $golesVisitante);
            header("Location: /partidos/partido?id=$idPartido");
        } else {
            $error = "Los goles no pueden ser negativos.";
            $_SESSION['error'] = $error;
            header("Location: /partidos/partido?id=$idPartido");
            exit;
        }
        exit;
    }
}
