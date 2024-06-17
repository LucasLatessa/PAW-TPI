<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Torneo;

class TorneoCollections extends Model{
   public $table = 'torneo';


   public function getAllTorneos(){
     $torneos = $this->queryBuilder->selectViejo($this->table);
     $torneosCollection = [];
     foreach($torneos as $torneo)
     {
      $nuevoTorneo = new Torneo();
      $nuevoTorneo->set($torneo);
      $torneosCollection[] = $nuevoTorneo;
     }
     return $torneosCollection;
  }

  public function getTorneo($idTorneo)
  {
   $torneo = $this->queryBuilder->selectViejo($this->table,['id'=> $idTorneo]);
   return $torneo ? $torneo[0] : null;
  }


   public function create($nombreTorneo, $fechaInicio, $fechaFin)
   {
      $newTorneo = new Equipo; 
      $data = [
         'nombre' => $nombreTorneo,
         'fecha_inicio' => $fechaInicio,
         'fecha_fin' => $fechaFin,
         // 'categoria' => $categoria,
         // 'cantidadEquipos' => $cantidadEquipos,
         // 'cantidadFechas' => $cantidadFechas,
         // 'descripcion' => $descripcion
      ];

      // Asignar el QueryBuilder y establecer los datos del equipo
      $newTorneo->setQueryBuilder($this->queryBuilder);
      $newTorneo->set($data);

      // Insertar los datos en la base de datos
      $this->queryBuilder->insert($this->table, $data);

      // Retornar la instancia del nuevo equipo creado
      return $newTorneo;
   }


}