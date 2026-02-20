<?php

namespace Paw\App\Models;

use Paw\App\Models\Partido;
use Paw\Core\Model;

class PartidoCollections extends Model
{
  public $table = 'partidos';

  public function getAllPartidos($filters = [])
  {
    $partidos = $this->aplicarFiltros($filters);
    // echo "<pre>";
    // print_r($partidos);
    // echo "</pre>";
    $partidosCollection = [];

    $equipoCollection = new EquipoCollections();
    $equipoCollection->setQueryBuilder($this->queryBuilder);

    foreach ($partidos as $partido) {
      $nuevoPartido = new Partido(); //Hidratar (armar) el partido
      $nuevoPartido->set($partido);

      //Equipo local
      $equipoLocal = $equipoCollection->getID(
        $nuevoPartido->getEquipoLocalId()
      );
      $nuevoPartido->setEquipoLocal($equipoLocal);

      //Equipo visitante
      $equipoVisitante = $equipoCollection->getID(
        $nuevoPartido->getEquipoVisitanteId()
      );
      $nuevoPartido->setEquipoVisitante($equipoVisitante);

      $partidosCollection[] = $nuevoPartido;
    }
    return $partidosCollection;
  }

  private function aplicarFiltros($filters)
  {
    $this->queryBuilder
      ->select($this->table . ' p')
      ->selectColumns('p.*');

    $whereConditions = [];
    $whereParams = [];

    if (!empty($filters['categoria'])) {
      $this->queryBuilder->join('torneos t', 'p.torneo_id = t.id');
      $whereConditions[] = "t.categoria = :categoria";
      $whereParams[':categoria'] = $filters['categoria'];
    }

    if (!empty($filters['fecha'])) {
      $whereConditions[] = "p.fecha_partido = :fecha";
      $whereParams[':fecha'] = $filters['fecha'];
    }

    if (!empty($filters['estado'])) {
      $whereConditions[] = "p.estado = :estado";
      $whereParams[':estado'] = $filters['estado'];
    }

    // Si hay filtros, unimos con AND
    if (!empty($whereConditions)) {
      $conditionString = implode(' AND ', $whereConditions);
      $this->queryBuilder->where($conditionString, $whereParams);
    }

    return $this->queryBuilder->execute();
  }

  public function getPartido($idPartido)
  {
    $partidoData = $this->queryBuilder->selectViejo($this->table, ['id' => $idPartido]);

    if (! $partidoData) {
      return null;
    }

    $data = $partidoData[0];

    $nuevoPartido = new Partido();
    $nuevoPartido->set($data);

    // traemos los equipos
    $equipoCollection = new EquipoCollections();
    $equipoCollection->setQueryBuilder($this->queryBuilder);

    // Local
    $equipoLocal = $equipoCollection->getID($nuevoPartido->getEquipoLocalId());
    $nuevoPartido->setEquipoLocal($equipoLocal);

    // Visitante
    $equipoVisitante = $equipoCollection->getID($nuevoPartido->getEquipoVisitanteId());
    $nuevoPartido->setEquipoVisitante($equipoVisitante);

    // tramos la fecha (fecha nro 1...)
    $fechaData = $this->queryBuilder->selectViejo('fechas', ['id' => $nuevoPartido->getFechaId()]);
    $nuevoPartido->setFecha($fechaData[0]);

    // traemos el torneo
    $torneoColl = new TorneoCollections();
    $torneoColl->setQueryBuilder($this->queryBuilder);
    $torneo = $torneoColl->getTorneo($nuevoPartido->getTorneoId());
    $nuevoPartido->setTorneo($torneo);

    return $nuevoPartido;
  }
  public function getUltimosPorTorneo(int $torneoId, int $limit = 3)
  {
    $partidos = $this->queryBuilder->selectViejo(
      $this->table,
      ['torneo_id' => $torneoId],
      'fecha_partido DESC',
      $limit
    );

    $partidosCollection = [];

    $equipoCollection = new EquipoCollections();
    $equipoCollection->setQueryBuilder($this->queryBuilder);

    foreach ($partidos as $partido) {
      $nuevoPartido = new Partido();
      $nuevoPartido->set($partido);

      // Equipo local
      $equipoLocal = $equipoCollection->getID(
        $nuevoPartido->getEquipoLocalId()
      );
      $nuevoPartido->setEquipoLocal($equipoLocal);

      // Equipo visitante
      $equipoVisitante = $equipoCollection->getID(
        $nuevoPartido->getEquipoVisitanteId()
      );
      $nuevoPartido->setEquipoVisitante($equipoVisitante);

      $partidosCollection[] = $nuevoPartido;
    }

    return $partidosCollection;
  }

  public function getPartidosByFecha(int $torneoId, int $fechaId): array
  {
    $partidos = $this->queryBuilder->selectViejo(
      $this->table,
      ['fecha_id' => $fechaId, "torneo_id" => $torneoId],
      'hora_partido ASC'
    );

    // echo "<pre>";
    // print_r($partidos);
    // echo "</pre>";

    //var_dump($torneoId);
    //var_dump($fechaId);
    $partidosCollection = [];

    $equipoCollection = new EquipoCollections();
    $equipoCollection->setQueryBuilder($this->queryBuilder);

    foreach ($partidos as $partido) {
      $nuevoPartido = new Partido();
      $nuevoPartido->set($partido);

      // Equipo local
      $equipoLocal = $equipoCollection->getID(
        $nuevoPartido->getEquipoLocalId()
      );
      $nuevoPartido->setEquipoLocal($equipoLocal);

      // Equipo visitante
      $equipoVisitante = $equipoCollection->getID(
        $nuevoPartido->getEquipoVisitanteId()
      );
      $nuevoPartido->setEquipoVisitante($equipoVisitante);

      $partidosCollection[] = $nuevoPartido;
    }

    return $partidosCollection;
  }
  public function getPartidosByEquipo($equipoId) //PARTIDOS FINALIZADOS
  {
    return $this->queryBuilder
      ->select($this->table)
      ->join("equipos as local", "partidos.equipo_local_id = local.id")
      ->join("equipos as visitante", "partidos.equipo_visitante_id = visitante.id")
      ->addSelect("local.nombre as nombre_local, local.escudo as escudo_local")
      ->addSelect("visitante.nombre as nombre_visitante, visitante.escudo as escudo_visitante")
      ->where("(equipo_local_id = :id OR equipo_visitante_id = :id) AND estado = :estado")
      ->setParam('id', $equipoId)
      ->setParam('estado', 'finalizado')
      ->order('fecha_partido DESC')
      ->limit(5)
      ->execute();
  }

  //Obtengo el proximo partido de un equipo (Mejorar)
  public function getProximoPartido($idTorneo, $equipoId)
  {
    $result = $this->queryBuilder
      ->select($this->table)
      ->join("equipos as local", "partidos.equipo_local_id = local.id")
      ->join("equipos as visitante", "partidos.equipo_visitante_id = visitante.id")
      ->addSelect("local.nombre as nombre_local, local.escudo as escudo_local")
      ->addSelect("visitante.nombre as nombre_visitante, visitante.escudo as escudo_visitante")
      ->where("(equipo_local_id = :id OR equipo_visitante_id = :id) AND estado = :estado AND torneo_id = :torneoId")
      ->setParam('id', $equipoId)
      ->setParam('estado', 'programado')
      ->setParam('torneoId', $idTorneo)
      ->order('fecha_partido ASC')
      ->limit(1)
      ->execute();

    if (empty($result)) return null;

    $p = $result[0];
    //var_dump($p);

    return [
      'id' => $p['id'],
      'fechaPartido' => $p['fecha_partido'],
      'horaPartido' => $p['hora_partido'],
      'estado' => $p['estado'],
      'golesLocal' => $p['goles_local'],
      'golesVisitante' => $p['goles_visitante'],

      'equipoLocal' => [
        'nombre' => $p['nombre_local'],
        'escudo' => $p['escudo_local'],
        'estadio' => [
          'nombre' => $p['cancha'] ?? ''
        ]
      ],

      'equipoVisitante' => [
        'nombre' => $p['nombre_visitante'],
        'escudo' => $p['escudo_visitante']
      ]
    ];
  }
  public function programarPartido($idTorneo, $fechaTorneo, $local, $visitante, $fecha = null, $hora = null)
  {
    $newPartido = new Partido();

    $data = [
      'torneo_id'           => $idTorneo,
      'fecha_id'            => $fechaTorneo,
      'equipo_local_id'     => $local,
      'equipo_visitante_id' => $visitante,
      'fecha_partido'       => $fecha,
      'hora_partido'        => $hora
    ];

    // insertar en la base de datos
    $this->queryBuilder->insert($this->table, $data);

    $idInsertado = $this->queryBuilder->getPdo()->lastInsertId();

    $newPartido->setQueryBuilder($this->queryBuilder);

    $data['id'] = $idInsertado;
    $newPartido->set($data);

    return $newPartido;
  }
  public function cargarResultado($idPartido, $gl, $gv)
  {

    $partido = $this->queryBuilder->selectViejo('partidos', ["id" => $idPartido], null, $limit = 1);
    $partido = $partido[0];

    // Si el partido esta finalizado, estamos en un UPDATE
    $yaFinalizado = $partido['estado'] === 'finalizado';

    $data = [
      'goles_local'     => $gl,
      'goles_visitante' => $gv,
      'estado'          => 'finalizado',
    ];

    //Actualizar tabla de posiciones
    $equipoTorneoCollection = new EquipoTorneoCollections();
    $equipoTorneoCollection->setQueryBuilder($this->queryBuilder);

    // Si el partido esta finalizado, tengo que restar los puntos y goles del resultado anterior para luego sumar los nuevos resultados
    if ($yaFinalizado) {
      $equipoTorneoCollection->revertirEstadisticas(
        $partido['equipo_local_id'],
        $partido['equipo_visitante_id'],
        $partido['torneo_id'],
        $partido['goles_local'],
        $partido['goles_visitante']
      );
    }

    $this->queryBuilder->update('partidos', $data, ['id' => $idPartido]);

    $equipoTorneoCollection->actualizarEstadisticas(
      $partido['equipo_local_id'],
      $partido['equipo_visitante_id'],
      $partido['torneo_id'],
      $gl,
      $gv
    );
  }
}
