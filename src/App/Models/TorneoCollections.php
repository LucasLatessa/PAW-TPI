<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Torneo;

class TorneoCollections extends Model
{
  public $table = 'torneos';


  public function getAllTorneos()
  {
    $torneos = $this->queryBuilder->selectViejo($this->table);
    $torneosCollection = [];
    foreach ($torneos as $torneo) {
      $nuevoTorneo = new Torneo();
      $nuevoTorneo->set($torneo);
      $torneosCollection[] = $nuevoTorneo;
    }
    return $torneosCollection;
  }

  public function getTorneo($idTorneo)
  {
    $torneo = $this->queryBuilder->selectViejo($this->table, ['id' => $idTorneo]);
    return $torneo ? $torneo[0] : null;
  }

  // La idea es que la Query sea casi automatica por el QueryBuilder
  public function getTablaPosiciones($idTorneo)
  {
    $tabla = $this->queryBuilder->selectJoin(
      'equipo_torneo et',
      [
        ['equipos e', 'e.id = et.equipo_id']
      ],
      ['torneo_id' => $idTorneo],
      'et.puntos DESC, et.diferencia_goles DESC, et.goles_favor DESC',
    );
    return $tabla ? $tabla : null;
  }

  public function getCantidadEquipos($idTorneo)
  {
    $cantEquipos = $this->queryBuilder->count(
      'equipo_torneo', ['torneo_id' => $idTorneo]
    );
    return $cantEquipos ? $cantEquipos : 0;
  }

  public function getUltimosPartidos($idTorneo)
  {
    $partidoCollection = new PartidoCollections();
    $partidoCollection->setQueryBuilder($this->queryBuilder);

    return $partidoCollection->getUltimosPorTorneo($idTorneo, 3);
  }

  public function getFixture(int $idTorneo)
  {
    $fechaCollection = new FechaCollections();
    $fechaCollection->setQueryBuilder($this->queryBuilder);

    return $fechaCollection->getByTorneo($idTorneo);
  }

  public function getFecha(int $idTorneo, int $idFecha)
  {
    $fechaCollection = new FechaCollections();
    $fechaCollection->setQueryBuilder($this->queryBuilder);

    return $fechaCollection->getFecha($idTorneo, $idFecha);
  }

  public function getPartidosFecha($idTorneo,$idFecha)
  {
    $partidoCollection = new PartidoCollections();
    $partidoCollection->setQueryBuilder($this->queryBuilder);

    return $partidoCollection->getPartidosByFecha($idTorneo,$idFecha);
  }
}
