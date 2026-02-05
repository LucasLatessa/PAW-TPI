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
    foreach ($partidos as $partido) {
      $nuevoPartido = new Partido();
      $nuevoPartido->set($partido);
      $partidosCollection[] = $nuevoPartido;
    }
    return $partidosCollection;
  }

}