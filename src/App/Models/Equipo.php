<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class Equipo extends Model
{

  private $table = 'equipos';

  private ?int $id = null;
  private string $nombre;
  private string $nombre_institucional;
  private string $fecha_creacion;
  private string $escudo;
  private string $estadio;
  private string $descripcion;
  private bool $activo;

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
    $record = current($this->queryBuilder->selectViejo($this->table, $params));

    if ($record !== false) {
      $this->set($record);
      return $this;
    } else {
      return null;
    }
  }

  /* ====== SET ====== */
  // public function set(array $values)
  // {
  //   foreach ($values as $field => $value) {
  //     #Creo el methodo y si existe lo ejecuto
  //     $method = "set" . ucfirst($field);
  //     if (method_exists($this, $method)) {
  //       $this->$method($value);
  //     }
  //   }
  // }
  public function set(array $values)
  {
    foreach ($values as $field => $value) {

      $camelCase = str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
      $method = 'set' . $camelCase;

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
  public function getNombreInstitucional(): string
  {
    return $this->nombre_institucional;
  }

  public function getFechaCreacion(): string
  {
    return $this->fecha_creacion;
  }

  public function getEscudo(): string
  {
    return $this->escudo;
  }

  public function getEstadio(): string
  {
    return $this->estadio;
  }

  public function getDescripcion(): string
  {
    return $this->descripcion;
  }

  public function isActivo(): bool
  {
    return $this->activo;
  }

  /* ====== SETTERS ====== */
  public function setId(?int $id): self
  {
    $this->id = $id;
    return $this;
  }

  public function setNombre(string $nombre): self
  {
    $this->nombre = $nombre;
    return $this;
  }
  public function setNombreInstitucional(string $nombre_institucional): self
  {
    $this->nombre_institucional = $nombre_institucional;
    return $this;
  }

  public function setFechaCreacion(string $fecha_creacion): self
  {
    $this->fecha_creacion = $fecha_creacion;
    return $this;
  }

  public function setEscudo(string $escudo): self
  {
    $this->escudo = $escudo;
    return $this;
  }

  public function setEstadio(string $estadio): self
  {
    $this->estadio = $estadio;
    return $this;
  }

  public function setDescripcion(string $descripcion): self
  {
    $this->descripcion = $descripcion;
    return $this;
  }

  public function setActivo(bool $activo): self
  {
    $this->activo = $activo;
    return $this;
  }
}
