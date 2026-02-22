<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class Torneo extends Model
{

  private $table   = 'torneos';
  private ?int $id = null;
  private string $nombre;
  private string $categoria;
  private string $temporada;
  private ?string $descripcion = null;
  private string $estado       = 'activo';
  private string $fecha_inicio;
  private ?string $fecha_fin = null;

  private array $partidos = [];

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

  /* ====== UTILS ====== */

  /* ====== GETTERS ====== */

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getNombre(): string
  {
    return $this->nombre;
  }

  public function getCategoria(): string
  {
    return $this->categoria;
  }
  public function getTemporada(): string
  {
    return $this->temporada;
  }

  public function getDescripcion(): ?string
  {
    return $this->descripcion;
  }

  public function getEstado(): string
  {
    return $this->estado;
  }

  public function getFechaInicio(): string
  {
    return $this->fecha_inicio;
  }

  public function getFechaFin(): ?string
  {
    return $this->fecha_fin;
  }

  public function getPartidos(): array
  {
    return $this->partidos;
  }

  /* ====== SETTERS ====== */

  public function setId(?int $id): void
  {
    $this->id = $id;
  }

  public function setNombre(string $nombre): void
  {
    $this->nombre = $nombre;
  }

  public function setCategoria(string $categoria): void
  {
    $this->categoria = $categoria;
  }

  public function setTemporada(string $temporada): void
  {
    $this->temporada = $temporada;
  }

  public function setDescripcion(?string $descripcion): void
  {
    $this->descripcion = $descripcion;
  }

  public function setEstado(string $estado): void
  {
    $this->estado = $estado;
  }

  public function setFechaInicio(string $fecha_inicio): void
  {
    $this->fecha_inicio = $fecha_inicio;
  }

  public function setFechaFin(?string $fecha_fin): void
  {
    $this->fecha_fin = $fecha_fin;
  }

  public function setPartidos(array $partidos): void
  {
    $this->partidos = $partidos;
  }
}
