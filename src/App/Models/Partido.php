<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class Partido extends Model
{
  private $table   = 'partidos';
  private ?int $id = null;
  private int $torneo_id;
  private int $fecha_id;
  private ?string $fecha_partido = null;
  private ?string $hora_partido = null;

  private int $equipo_local_id;
  private int $equipo_visitante_id;

    private ?Equipo $equipoLocal     = null;
    private ?Equipo $equipoVisitante = null;
    private ?Torneo $torneo          = null;
    private $fecha;
    private ?int $goles_local        = null;
    private ?int $goles_visitante    = null;
    private string $estado;
    private ?int $cancha;
    private ?Estadio $estadio        = null;

  /* ====== CONSTRUCTOR ====== */
  public function __construct(array $data = [])
  {
    if (!empty($data)) {
      $this->set($data);
    }
  }

  /* ====== LOAD ====== */
  public function load($id)
  {
    $params = ["id" => $id];
    $record = current(
      $this->queryBuilder
        ->select($this->table)
        ->where($params)
        ->execute()
    );

    if ($record !== false) {
      $this->set($record);
      return $this;
    } else {
      return null;
    }
  }

  /* ====== SET ====== */
  public function set(array $values)
  {
    foreach ($values as $field => $value) {
      $camelCase = str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
      $method    = 'set' . $camelCase;

      if (method_exists($this, $method)) {
        $this->$method($value);
      }
    }
  }

  /* ====== GETTERS ====== */
  public function getId(): ?int
  {
    return $this->id;
  }
  public function getTorneoId(): int
  {
    return $this->torneo_id;
  }
  public function getTorneo(): ?Torneo
  {
    return $this->torneo;
  }
  public function getFechaId(): int
  {
    return $this->fecha_id;
  }
  public function getFecha()
  {
    return $this->fecha;
  }
  public function getFechaPartido(): ?string
  {
    return $this->fecha_partido;
  }
  public function getHoraPartido(): ?string
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
  public function getEquipoLocal(): ?Equipo
  {
    return $this->equipoLocal;
  }
  public function getEquipoVisitante(): ?Equipo
  {
    return $this->equipoVisitante;
  }

  // Goles pueden ser null (partido no jugado)
  public function getGolesLocal(): ?int
  {
    return $this->goles_local;
  }
  public function getGolesVisitante(): ?int
  {
    return $this->goles_visitante;
  }

    public function getEstado(): string { return $this->estado; }
    public function getCancha(): ?int { return $this->cancha; }

    public function getEstadio(): ?Estadio { return $this->estadio; }

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

  public function setFechaPartido(?string $fecha_partido): void
  {
    $this->fecha_partido = $fecha_partido;
  }

  public function setHoraPartido(?string $hora_partido): void
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

  public function setEquipoLocal(?Equipo $equipoLocal): void
  {
    $this->equipoLocal = $equipoLocal;
  }
  public function setEquipoVisitante(?Equipo $equipoVisitante): void
  {
    $this->equipoVisitante = $equipoVisitante;
  }
  public function setTorneo(?Torneo $torneo): void
  {
    $this->torneo = $torneo;
  }
  public function setFecha($fecha): void
  {
    $this->fecha = $fecha;
  }

    public function setGolesLocal(?int $goles_local): void { $this->goles_local = $goles_local; }
    public function setGolesVisitante(?int $goles_visitante): void { $this->goles_visitante = $goles_visitante; }
    public function setEstado(string $estado): void { $this->estado = $estado; }
    public function setCancha(?int $cancha): void { $this->cancha = $cancha; }
    public function setEstadio(?Estadio $estadio): void { $this->estadio = $estadio; }
}
