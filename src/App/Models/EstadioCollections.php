<?php
namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Estadio;

class EstadioCollections extends Model
{
    protected $table = 'estadios';

    public function create($nombreEstadio)
   {
      $newEstadio = new Estadio; 
      $data = [
         'nombre' => $nombreEstadio
         //'latitud' => null,
         //'longitud' => null
      ];

      // Asignar el QueryBuilder y establecer los datos del equipo
      $newEstadio->setQueryBuilder($this->queryBuilder);
      $newEstadio->set($data);

      // Insertar los datos en la base de datos
      $this->queryBuilder->insert($this->table, $data);
      $estadioId = $this->queryBuilder->getPdo()->lastInsertId();

      $data['id'] = $estadioId;
      $newEstadio->set($data);

      // Retornar la instancia del nuevo equipo creado
      return $newEstadio;
   }
}
