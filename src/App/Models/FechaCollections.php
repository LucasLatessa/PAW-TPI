<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Torneo;

class FechaCollections extends Model
{
  protected $table = 'fechas';

  //Busca las fechas dado el ID del torneo
  public function getByTorneo(int $torneoId): array
  {
    $fechas = $this->queryBuilder->selectViejo(
      $this->table,
      ['torneo_id' => $torneoId],
      'numero ASC'
    );

    $fechasCollection = [];

    foreach ($fechas as $fecha) {
      $nuevaFecha = new Fecha();
      $nuevaFecha->set($fecha);
      $fechasCollection[] = $nuevaFecha;
    }

    return $fechasCollection;
  }

  public function getFecha($idTorneo, $idFecha)
  {
    $fecha = $this->queryBuilder->selectViejo($this->table, ['torneo_id' => $idTorneo, 'id' => $idFecha]);
    return $fecha ? $fecha[0] : null;
  }
}
