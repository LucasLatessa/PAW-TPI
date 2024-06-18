<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class PartidoCollections extends Model{
   public $table = 'partido';

//    public function getAllEquipos($idTorneo){
//         // Obtener todos los equipos usando el método selectViejo de QueryBuilder
//         $equipos = $this->queryBuilder->selectViejo($this->table, ['id_torneo' => $idTorneo]);

//         $equipoCollection = new EquipoCollections();
//         $equipoCollection->setQueryBuilder($this->queryBuilder);
        
//         // Crear una colección de objetos Equipo
//         $equiposTorneoCollection = [];
//         foreach ($equipos as $equipoData) {
//             $nuevoEquipo = new EquipoTorneo; // Suponiendo que tienes una clase Equipo
//             $nuevoEquipo->set($equipoData);

//             $equipo = $equipoCollection->getXid($nuevoEquipo->getId_equipo());
//             $nuevoEquipo->equipo = $equipo;
//             $equiposTorneoCollection[] = $nuevoEquipo;
//         }
//         //var_dump($equiposTorneoCollection);
//         return $equiposTorneoCollection;
//   }

   public function create($local, $visitante, $golesLocal, $golesVisitante, $fecha, $hora)
   {
      $newPartido = new Partido(); 

      $data = [
         'id_equipo_local' => $local,
         'id_equipo_visitante' => $visitante,
         'golesLocal' => $golesLocal,
         'golesVisitante' => $golesVisitante,
         'fecha' => $fecha,
         'horario' => $hora
      ];

      // Asignar el QueryBuilder y establecer los datos del equipo
      $newPartido->setQueryBuilder($this->queryBuilder);
      $newPartido->set($data);

      // Insertar los datos en la base de datos
      $this->queryBuilder->insert($this->table, $data);

      //Actualizar estadisticas en EquipoTorneo
      $equipoTorneo = new EquipoTorneoCollections();
      $equipoTorneo->setQueryBuilder($this->queryBuilder);

      //Estadisticas local
      $fieldsLocal = $this->calcularEstadisticas($local,$golesLocal,$golesVisitante);
      $equipoTorneo->updateEstadisticas($local,$fieldsLocal);

      //Estadisticas visitante
      $fieldsVisitante = $this->calcularEstadisticas($visitante,$golesVisitante,$golesLocal);
      $equipoTorneo->updateEstadisticas($visitante,$fieldsVisitante);

      //Instacia nuevo partido creado
      return $newPartido;
   }
 
   public function calcularEstadisticas($equipoId, $golesAFavor, $golesContra)
   {
      $estadisticas = 
      [
         'partidosJugados' => 1,
         'golesAFavor' => $golesAFavor,
         'golesContra' => $golesContra
      ];

      if ($golesAFavor > $golesContra) { //Gano
            $estadisticas['partidosGanados'] = 1;
            $estadisticas['puntos'] = 3;
      } elseif ($golesAFavor == $golesContra) { //Empato
            $estadisticas['partidosEmpatados'] = 1;
            $estadisticas['puntos'] = 1;
      } else {
            $estadisticas['partidosPerdidos'] = 1; //Perdio
            $estadisticas['puntos'] = 0;
      }

     return $estadisticas;
   }
}  


//    public function get($nombre){
//       $equipo = $this->queryBuilder->selectNombreEquipo($this->table, $nombre);
//       return $equipo;
//    }