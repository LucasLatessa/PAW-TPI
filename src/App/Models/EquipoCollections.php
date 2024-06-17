<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Equipo;

class EquipoCollections extends Model{
   public $table = 'equipo';


   public function getAllEquipos(){
        // Obtener todos los equipos usando el método selectViejo de QueryBuilder
        $equipos = $this->queryBuilder->selectViejo($this->table);
        
        // Crear una colección de objetos Equipo
        $equiposCollection = [];
        foreach ($equipos as $equipoData) {
            $nuevoEquipo = new Equipo; // Suponiendo que tienes una clase Equipo
            $nuevoEquipo->set($equipoData);
            $equiposCollection[] = $nuevoEquipo;
        }
        
        return $equiposCollection;
  }

   public function create($nombreEquipo, $fechaCreacion, $nombreEstadio, $descripcion, $imagen)
   {
      $newEquipo = new Equipo; 
      $data = [
         'nombre' => $nombreEquipo,
         'fecha_creacion' => $fechaCreacion,
         'estadio' => $nombreEstadio,
         'descripcion' => $descripcion,
         'escudo' => $imagen
      ];

      // Asignar el QueryBuilder y establecer los datos del equipo
      $newEquipo->setQueryBuilder($this->queryBuilder);
      $newEquipo->set($data);

      // Insertar los datos en la base de datos
      $this->queryBuilder->insert($this->table, $data);

      // Retornar la instancia del nuevo equipo creado
      return $newEquipo;
   }

}