<?php

namespace Paw\App\Models;

use Paw\Core\Database\QueryBuilder;
use Paw\Core\Model;
use Paw\App\Models\Equipo;

class EquipoCollections extends Model
{
  public $table = 'equipos';

  public function getAllEquipos()
  {
    // Obtener todos los equipos usando el método selectViejo de QueryBuilder
    $equipos = $this->queryBuilder->selectViejo($this->table);


    // Crear una coleccion de objetos Equipo
    $equiposCollection = [];
    foreach ($equipos as $equipoData) {
      $nuevoEquipo = new Equipo;
      $nuevoEquipo->set($equipoData);
      $equiposCollection[] = $nuevoEquipo;
    }

    return $equiposCollection;
  }
  public function getID($idEquipo): ?Equipo
  {
    $result = $this->queryBuilder->selectViejo(
      $this->table,
      ['id' => $idEquipo]
    );

    if (empty($result)) {
      return null;
    }

    $equipo = new Equipo();
    $equipo->set($result[0]);

    return $equipo;
  }
}
