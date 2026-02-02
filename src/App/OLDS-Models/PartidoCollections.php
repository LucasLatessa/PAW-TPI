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

   public function cargarResultado($idTorneo, $idPartido, $golesLocal, $golesVisitante)
{
    // RECUPERAR DATOS DEL PARTIDO
    $partido = new Partido();
    $partido->setQueryBuilder($this->queryBuilder);
    $partido->load($idPartido);

    $local = $partido->getIdEquipoLocal();
    $visitante = $partido->getIdEquipoVisitante();
    
    $idTorneo = $partido->getIdTorneo();

    // ACTUALIZAR EL PARTIDO CON LOS GOLES
    $data = [
        'golesLocal' => $golesLocal,
        'golesVisitante' => $golesVisitante
    ];

    $this->queryBuilder->update($this->table, $data, [
        'id' => $idPartido
    ]);

    // ACTUALIZAR ESTADISTICAS EN EQUIPO_TORNEO
    $equipoTorneo = new EquipoTorneoCollections();
    $equipoTorneo->setQueryBuilder($this->queryBuilder);

    // Estadisticas Local
    $estadisticasLocal = $equipoTorneo->getEstadisticas($idTorneo, $local);
    
    // Calculamos los nuevos numeros
    $fieldsLocal = $this->calcularEstadisticas($estadisticasLocal, $golesLocal, $golesVisitante);
    $equipoTorneo->updateEstadisticas($local, $fieldsLocal);

    // Estadisticas Visitante
    $estadisticasVisitante = $equipoTorneo->getEstadisticas($idTorneo, $visitante);
    $fieldsVisitante = $this->calcularEstadisticas($estadisticasVisitante, $golesVisitante, $golesLocal);
    $equipoTorneo->updateEstadisticas($visitante, $fieldsVisitante);
}
   public function programarPartido($idTorneo, $fechaTorneo, $local, $visitante, $fecha, $hora)
   {
      $newPartido = new Partido(); 

      $data = [
         'id_torneo' => $idTorneo,
         'fecha_torneo' => $fechaTorneo,
         'id_equipo_local' => $local,
         'id_equipo_visitante' => $visitante,
         'fecha' => $fecha,
         'horario' => $hora
      ];

      // Asignar el QueryBuilder y establecer los datos del equipo
      $newPartido->setQueryBuilder($this->queryBuilder);
      $newPartido->set($data);

      // Insertar los datos en la base de datos
      $this->queryBuilder->insert($this->table, $data);

      //Instacia nuevo partido creado
      return $newPartido;
   }
   public function getPartidosACargar($idTorneo){
       // traemos los datos de la base
       $partidosData = $this->queryBuilder->selectViejo($this->table, [
           "id_torneo" => $idTorneo,
           "golesLocal" => null,
           "golesVisitante" => null
       ]);

       $listaPartidos = [];
       
       // recorremos y armamos los objetos
       foreach ($partidosData as $data) {
           $partido = new Partido();
           $partido->setQueryBuilder($this->queryBuilder);
           $partido->set($data); 
           $local = new Equipo();
           $local->setQueryBuilder($this->queryBuilder); 
           if ($local->load($partido->getIdEquipoLocal())) {
               $partido->setEquipoLocal($local);
           }
           $visitante = new Equipo();
           $visitante->setQueryBuilder($this->queryBuilder);
           if ($visitante->load($partido->getIdEquipoVisitante())) {
               $partido->setEquipoVisitante($visitante);
           }

           $listaPartidos[] = $partido;
       }

       return $listaPartidos;
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