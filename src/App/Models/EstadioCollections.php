<?php
namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Estadio;

class EstadioCollections extends Model
{
    protected $table = 'estadios';

    public function create(Estadio $estadio, $qb)
   {
    $data = [
        'nombre' => $estadio ->getNombre(),
        'latitud' => $estadio->getLatitud(),
        'longitud' => $estadio->getLongitud(),
    ];

    $qb->insert($this->table, $data);
    $id = $qb->getPdo()->lastInsertId();
    $estadio->set(['id' => $id]); 

    return $estadio;
   }

   public function getByID(int $id): ?Estadio
    { 
      $record = $this->queryBuilder->select('estadios', ['id' => $id])->execute();

      if (empty($record)) {
          return null;
      }

      $estadio = new Estadio();
      $estadio->set($record[0]);

      return $estadio;
    }
}
