<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class Partido extends Model
{
  private $table = 'partidos';
  private ?int $id = null;
  private int $torneo_id;
  private int $fecha_id;
  private string $fecha_partido;
  private string $hora_partido;
  private int $equipo_local_id;
  private int $equipo_visitante_id;
  private $equipoLocal; // Objeto Equipo representando al equipo local
  private $equipoVisitante; // Objeto Equipo representando al equipo visitante
  private ?int $goles_local = null;
  private ?int $goles_visitante = null;
  private string $estado;
  private ?string $cancha = null;

  /* ====== CONSTRUCTOR ====== */
  public function __construct(array $data = [])
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->$key = $value;
      }
    }
  }

  /* ====== LOAD ====== */
  public function load(int $id): ?self
  {
    $params = ['id' => $id];
    $record = current($this->queryBuilder->select($this->table, $params));

    if (!$record) {
      return null;
    }

    $this->set($record);
    return $this;
  }

  private function snakeToCamel(string $string): string
  {
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
  }

  /* ====== SET ====== */
  public function set(array $values): void
  {
    foreach ($values as $field => $value) {
      $method = 'set' . $this->snakeToCamel($field);
      if (method_exists($this, $method)) {
        $this->$method($value);
      }
    }
  }

  /* ====== UTILS ====== */


  /* ====== GETTERS ====== */
  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTorneoId(): int
  {
    return $this->torneo_id;
  }

  public function getFechaId(): int
  {
    return $this->fecha_id;
  }

  public function getFechaPartido(): string
  {
    return $this->fecha_partido;
  }

  public function getHoraPartido(): string
  {
    return $this->hora_partido;
  }

  public function getEquipoLocalId(): int
  {
    return $this->equipo_local_id;
  }

  public function getEquipoVisitanteId(): int
  {
    return $this->equipo_visitante_id;
  }

  public function getEquipoLocal()
  {
    return $this->equipoLocal;
  }

  public function getEquipoVisitante()
  {
    return $this->equipoVisitante;
  }

  public function getGolesLocal(): int
  {
    return $this->goles_local;
  }

  public function getGolesVisitante(): int
  {
    return $this->goles_visitante;
  }

  public function getEstado(): string
  {
    return $this->estado;
  }

  public function getCancha(): string
  {
    return $this->cancha;
  }

  /* ====== SETTERS ====== */

  public function setId(?int $id): void
  {
    $this->id = $id;
  }

  public function setTorneoId(int $torneo_id): void
  {
    $this->torneo_id = $torneo_id;
  }

  public function setFechaId(int $fecha_id): void
  {
    $this->fecha_id = $fecha_id;
  }

  public function setFechaPartido(string $fecha_partido): void
  {
    $this->fecha_partido = $fecha_partido;
  }

  public function setHoraPartido(string $hora_partido): void
  {
    $this->hora_partido = $hora_partido;
  }

  public function setEquipoLocalId(int $equipo_local_id): void
  {
    $this->equipo_local_id = $equipo_local_id;
  }

  public function setEquipoVisitanteId(int $equipo_visitante_id): void
  {
    $this->equipo_visitante_id = $equipo_visitante_id;
  }

  public function setEquipoLocal($equipoLocal): void
  {
    $this->equipoLocal = $equipoLocal;
  }

  public function setEquipoVisitante($equipoVisitante): void
  {
    $this->equipoVisitante = $equipoVisitante;
  }

  public function setGolesLocal(int $goles_local = null): void
  {
    $this->goles_local = $goles_local;
  }

  public function setGolesVisitante(int $goles_visitante = null): void
  {
    $this->goles_visitante = $goles_visitante;
  }

  public function setEstado(string $estado): void
  {
    $this->estado = $estado;
  }

  public function setCancha(string $cancha = null): void
  {
    $this->cancha = $cancha;
  }
}
