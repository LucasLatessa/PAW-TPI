<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class Fecha extends Model
{

  private $table = 'fechas';

  private ?int $id = null;
  private int $numero;
  private string $nombre;

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
    $record = current($this->queryBuilder->selectLoad($this->table, $params));

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

  public function getNumero(): int
  {
    return $this->numero;
  }

  public function getNombre(): string
  {
    return $this->nombre;
  }

  /* ====== SETTERS ====== */
  public function setId(?int $id): void
  {
    $this->id = $id;
  }

  public function setNumero(int $numero): void
  {
    $this->numero = $numero;
  }

  public function setNombre(string $nombre): void
  {
    $this->nombre = $nombre;
  }

}
