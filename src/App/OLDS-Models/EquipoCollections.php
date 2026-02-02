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
 
   public function get($nombre){
      $equipo = $this->queryBuilder->selectNombreEquipo($this->table, $nombre);
      return $equipo;
   }
  public function getPartidosEquipo($idEquipo)
{
    // traemos los partidos donde es local y visitante
    $partidosLocal = $this->queryBuilder->selectViejo('partido', ['id_equipo_local' => $idEquipo]);
    $partidosVisitante = $this->queryBuilder->selectViejo('partido', ['id_equipo_visitante' => $idEquipo]);

    // unimos todo
    $todosLosPartidos = array_merge($partidosLocal, $partidosVisitante);

    // para evitar duplicados si la consulta fallo en algun lado
    $todosLosPartidos = array_unique($todosLosPartidos, SORT_REGULAR);

    // traemos todos los equipos para saber sus nombres
    $listaEquipos = $this->queryBuilder->selectViejo('equipo');
    
    // armamos un mapa rapido: id -> nombre
    $infoEquipos = [];
    foreach ($listaEquipos as $equipo) {
        $infoEquipos[$equipo['id']] = $equipo;
    }

    $jugados = [];
    $pendientes = [];

    // recorremos y traemos los datos
    foreach ($todosLosPartidos as $partido) {
        
        $datosLocal = $infoEquipos[$partido['id_equipo_local']] ?? null;
        $datosVisita = $infoEquipos[$partido['id_equipo_visitante']] ?? null;

        $partido['nombre_local'] = $datosLocal ? $datosLocal['nombre'] : 'Desconocido';
        $partido['nombre_visitante'] = $datosVisita ? $datosVisita['nombre'] : 'Desconocido';

        // asignamos el estadio
        $partido['estadio'] = $datosLocal['estadio'] ?? 'A confirmar';

        // separamos jugados de pendientes
        if (!is_null($partido['golesLocal']) && !is_null($partido['golesVisitante'])) {
            $jugados[] = $partido;
        } else {
            $pendientes[] = $partido;
        }
    }

    return [
        'jugados' => $jugados,
        'pendientes' => $pendientes
    ];
}
   public function getXid($idEquipo){
      $equipo = $this->queryBuilder->selectViejo($this->table, ["id" => $idEquipo]);
      return $equipo[0];
   }


}