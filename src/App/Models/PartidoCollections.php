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

   public function create($idTorneo, $idFecha, $local, $visitante, $golesLocal, $golesVisitante, $fecha, $hora)
   {
      $newPartido = new Partido(); 

      $data = [
         'id_fecha' => $idFecha,
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
      $estadisticasLocal = $equipoTorneo->getEstadisticas($idTorneo,$local);
      $fieldsLocal = $this->calcularEstadisticas($estadisticasLocal ,$golesLocal,$golesVisitante);
      $equipoTorneo->updateEstadisticas($local,$fieldsLocal);

      //Estadisticas visitante
      $estadisticasVisitante = $equipoTorneo->getEstadisticas($idTorneo, $visitante);
      //var_dump($estadisticasVisitante);
      $fieldsVisitante = $this->calcularEstadisticas($estadisticasVisitante, $golesVisitante,$golesLocal);
      //var_dump($fieldsVisitante);
      $equipoTorneo->updateEstadisticas($visitante,$fieldsVisitante);

      //Instacia nuevo partido creado
      return $newPartido;
   }
 
   private function calcularEstadisticas($currentStats, $golesAFavor, $golesEnContra)
   {
      //var_dump($currentStats);
      //var_dump($golesAFavor);
      //var_dump($golesEnContra);

       // Actualiza las estadísticas basadas en el resultado del partido
       $currentStats['partidosJugados'] += 1;
       $currentStats['golesAFavor'] += $golesAFavor;
       //var_dump($currentStats['golesAFavor']);
       $currentStats['golesContra'] += $golesEnContra;

       if ($golesAFavor > $golesEnContra) {
           $currentStats['partidosGanados'] += 1;
           $currentStats['puntos'] += 3;
       } elseif ($golesAFavor == $golesEnContra) {
           $currentStats['partidosEmpatados'] += 1;
           $currentStats['puntos'] += 1;
       } else {
           $currentStats['partidosPerdidos'] += 1;
       }

       $currentStats['diferencia'] = $currentStats['golesAFavor'] - $currentStats['golesContra'];

       return $currentStats;
   }
}  


//    public function get($nombre){
//       $equipo = $this->queryBuilder->selectNombreEquipo($this->table, $nombre);
//       return $equipo;
//    }