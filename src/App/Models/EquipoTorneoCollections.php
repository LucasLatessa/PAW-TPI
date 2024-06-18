<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Equipo;

class EquipoTorneoCollections extends Model
{
   public $table = 'equipoTorneo';

   public function getAllEquipos($idTorneo)
   {
      // Obtener todos los equipos usando el método selectViejo de QueryBuilder
      $equipos = $this->queryBuilder->selectViejo($this->table, ["id_torneo" => $idTorneo]);

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

   public function getTabla($idTorneo)
   {
      // Obtener todos los equipos usando el método selectViejo de QueryBuilder
      $equipos = $this->queryBuilder->selectTabla($this->table, ["id_torneo" => $idTorneo]);

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

   public function getEstadisticas($idTorneo, $equipoId)
   {
      $stats = $this->queryBuilder->selectViejo($this->table, ['id_torneo' => $idTorneo, 'id_equipo' => $equipoId]);

      // Asegúrate de que todas las claves necesarias estén presentes
      $defaultStats = [
         'partidosJugados' => 0,
         'partidosGanados' => 0,
         'partidosEmpatados' => 0,
         'partidosPerdidos' => 0,
         'golesAFavor' => 0,
         'golesContra' => 0,
         'diferencia' => 0,
         'puntos' => 0,
      ];

      // Si no se encuentra el equipo, retorna las estadísticas por defecto
      if (empty($stats)) {
         return $defaultStats;
      } else {
         return $stats[0];
      }

      // Combina las estadísticas actuales con las predeterminadas para asegurar que todas las claves estén presentes
      //return $stats
   }

   public function updateEstadisticas($equipoId, $fields)
   {
      // Llama a updateEquipoTorneo con los campos válidos
      $params = ['id_equipo' => $equipoId];
      return $this->queryBuilder->updateEquipoTorneo($this->table, $fields, $params);
   }

   //    public function get($nombre){
//       $equipo = $this->queryBuilder->selectNombreEquipo($this->table, $nombre);
//       return $equipo;
//    }


}