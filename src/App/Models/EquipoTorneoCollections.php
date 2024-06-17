<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Equipo;

class EquipoTorneoCollections extends Model{
   public $table = 'equipoTorneo';

   public function getAllEquipos($idTorneo){
        // Obtener todos los equipos usando el método selectViejo de QueryBuilder
        $equipos = $this->queryBuilder->selectViejo($this->table, ['id_torneo' => $idTorneo]);

        $equipoCollection = new EquipoCollections();
        $equipoCollection->setQueryBuilder($this->queryBuilder);
        
        // Crear una colección de objetos Equipo
        $equiposTorneoCollection = [];
        foreach ($equipos as $equipoData) {
            $nuevoEquipo = new EquipoTorneo; // Suponiendo que tienes una clase Equipo
            $nuevoEquipo->set($equipoData);

            $equipo = $equipoCollection->getXid($nuevoEquipo->getId_equipo());
            $nuevoEquipo->equipo = $equipo;
            $equiposTorneoCollection[] = $nuevoEquipo;
        }
        //var_dump($equiposTorneoCollection);
        return $equiposTorneoCollection;
  }

   public function create($idEquipo, $idTorneo)
   {
      $newEquipoTorneo = new EquipoTorneo; 
      $data = [
         'id_equipo' => $idEquipo,
         'id_torneo' => $idTorneo
      ];

      // Asignar el QueryBuilder y establecer los datos del equipo
      $newEquipoTorneo->setQueryBuilder($this->queryBuilder);
      $newEquipoTorneo->set($data);

      // Insertar los datos en la base de datos
      $this->queryBuilder->insert($this->table, $data);

      // Retornar la instancia del nuevo equipo creado
      return $newEquipoTorneo;
   }
 
//    public function get($nombre){
//       $equipo = $this->queryBuilder->selectNombreEquipo($this->table, $nombre);
//       return $equipo;
//    }


}