<?php

namespace Paw\App\Models;

use Paw\Core\Database\QueryBuilder;
use Paw\Core\Model;
use Paw\App\Models\Equipo;

class EquipoCollections extends Model
{
  public $table = 'equipos';
  
   public function create($nombreEquipo, $nombreEquipoInstitucional, $fechaCreacion, $estadio, $descripcion, $imagen)
   {
      $newEquipo = new Equipo; 
      $pathImagen = 'escudos/' . $imagen;
      $data = [
         'nombre' => $nombreEquipo,
         'nombre_institucional' => $nombreEquipoInstitucional,
         'fecha_creacion' => $fechaCreacion,
         'estadio_id' => $estadio,
         'descripcion' => $descripcion,
         'escudo' => $pathImagen
      ];

      // Asignar el QueryBuilder y establecer los datos del equipo
      $newEquipo->setQueryBuilder($this->queryBuilder);
      $newEquipo->set($data);

      // Insertar los datos en la base de datos
      $this->queryBuilder->insert($this->table, $data);

      // Retornar la instancia del nuevo equipo creado
      return $newEquipo;
   }

  public function getAllEquipos()
  {
    // Obtener todos los equipos usando el método select de QueryBuilder
    $equipos = $this->queryBuilder->select($this->table)->execute();


    // Crear una coleccion de objetos Equipo
    $equiposCollection = [];
    foreach ($equipos as $equipoData) {
      $nuevoEquipo = new Equipo;
      $nuevoEquipo->set($equipoData);
      $equiposCollection[] = $nuevoEquipo;
    }

    return $equiposCollection;
  }
    public function getID(int $id): ?Equipo
    {
        $record = $this->queryBuilder->select('equipos', ['id' => $id])->execute();
        if (empty($record)) {
            return null;
        }

        $equipo = new Equipo();
        $equipo->set($record[0]);

        // si el equipo tiene un estadio_id
        if ($equipo->getEstadioId()) {
            $dataEstadio = $this->queryBuilder->selectViejo('estadios', ['id' => $equipo->getEstadioId()]);

            if (! empty($dataEstadio)) {
                $estadio = new Estadio();
                $estadio->set($dataEstadio[0]);
                // inyectamos objeto estadio al equipo
                $equipo->setEstadio($estadio);
            }
        }

        return $equipo;
    }
}
