<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Equipo;
use Paw\App\Models\Partido;

class EquipoTorneoCollections extends Model
{
  public $table = 'equipo_torneo';

  public function getAllEquipos($idTorneo)
  {
    // Obtener todos los equipos usando el método selectViejo de QueryBuilder
    $equipos = $this->queryBuilder->selectViejo($this->table, ["torneo_id" => $idTorneo]);

    $equipoCollection = new EquipoCollections();
    $equipoCollection->setQueryBuilder($this->queryBuilder);

    // Crear una colección de objetos Equipo
    $equiposTorneoCollection = [];
    foreach ($equipos as $equipoData) {
      $nuevoEquipo = new EquipoTorneo; // Suponiendo que tienes una clase Equipo
      $nuevoEquipo->set($equipoData);

      $equipo = $equipoCollection->getId($nuevoEquipo->getEquipo_id());
      $nuevoEquipo->equipo = $equipo;
      $equiposTorneoCollection[] = $nuevoEquipo;
    }
    //var_dump($equiposTorneoCollection);
    return $equiposTorneoCollection;
  }

  public function getTabla($idTorneo)
  {
    // Obtener todos los equipos usando el método selectViejo de QueryBuilder
    $equipos = $this->queryBuilder->selectTabla($this->table, ["torneo_id" => $idTorneo]);

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

  /* Devuelve la ultima tabla creada + el nombre del torneo */
  public function getUltimaTabla()
  {
    // buscar el ultimo torneo creado
    $torneos = $this->queryBuilder->selectViejo('torneo', [], 'id DESC', 1);

    // si no hay torneos, devolvemos array vacio
    if (empty($torneos)) {
      return [];
    }

    $ultimoTorneo = $torneos[0];
    $tabla = $this->getTabla($ultimoTorneo['id']);

    return [
      'nombre_torneo' => $ultimoTorneo['nombre'],
      'tabla' => $tabla
    ];
  }
  public function create($idEquipo, $idTorneo)
  {
    $newEquipoTorneo = new EquipoTorneo;

    $data = [
      'equipo_id' => $idEquipo,
      'torneo_id' => $idTorneo
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
    $stats = $this->queryBuilder->selectViejo($this->table, ['torneo_id' => $idTorneo, 'equipo_id' => $equipoId]);

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

  public function actualizarEstadisticas($localId, $visitanteId, $torneoId, $gl, $gv)
  {
    // Local
    $statsLocal = $this->getEstadisticas($torneoId,$localId);
    $dataLocal = $this->calcularStats($statsLocal,$gl,$gv);

    $this->queryBuilder->update($this->table, $dataLocal, ['equipo_id' => $localId, "torneo_id" => $torneoId]);

    // Visitante
    $statsVisitante = $this->getEstadisticas($torneoId,$visitanteId);
    $dataVisitante = $this->calcularStats($statsVisitante,$gv,$gl);

    $this->queryBuilder->update($this->table, $dataVisitante, ['equipo_id' => $visitanteId, "torneo_id" => $torneoId]);
  }

  private function calcularStats($statsActuales, $gf, $gc)
  {
    $pj = $statsActuales['partidos_jugados'] + 1;
    $pg = $statsActuales['ganados'];
    $pe = $statsActuales['empatados'];
    $pp = $statsActuales['perdidos'];
    $pts = $statsActuales['puntos'];

    if ($gf > $gc) {
      $pg += 1;
      $pts += 3;
    } elseif ($gf == $gc) {
      $pe += 1;
      $pts += 1;
    } else {
      $pp += 1;
    }

    return [
      'partidos_jugados'  => $pj,
      'ganados'  => $pg,
      'empatados'  => $pe,
      'perdidos'  => $pp,
      'goles_favor'  => $statsActuales['goles_favor'] + $gf,
      'goles_contra'  => $statsActuales['goles_contra'] + $gc,
      'diferencia_goles'  => ($statsActuales['goles_favor'] + $gf) - ($statsActuales['goles_contra'] + $gc),
      'puntos' => $pts
    ];
  }
}
