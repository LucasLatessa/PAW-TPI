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
