<?php

namespace Paw\App\Models;

use Paw\App\Models\Partido;
use Paw\Core\Model;

class PartidoCollections extends Model
{
  public $table = 'partidos';

  public function getAllPartidos()
  {
    $partidos = $this->queryBuilder->selectViejo($this->table);
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

  public function getPartido($idPartido)
  {
    $partido = $this->queryBuilder->selectViejo($this->table, ['id' => $idPartido]);
    return $partido ? $partido[0] : null;
  }
}
