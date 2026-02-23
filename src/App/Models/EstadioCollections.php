<?php
namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Estadio;

class EstadioCollections extends Model
{
    protected $table = 'estadios';

    public function create($nombreEstadio, $latitud, $longitud)
   {
      $newEstadio = new Estadio; 
      $data = [
         'nombre' => $nombreEstadio,
         'latitud' => $latitud,
         'longitud' => $longitud
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

   public function getID(int $id): ?Estadio
    {
        $record = $this->queryBuilder->selectViejo('estadios', ['id' => $id]);
        if (empty($record)) {
            return null;
        }

        $estadio = new Estadio();
        $estadio->set($record[0]);

        return $estadio;
    }
}
