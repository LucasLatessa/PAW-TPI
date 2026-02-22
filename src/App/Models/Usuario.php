<?php

namespace Paw\App\Models;

use Paw\Core\Exceptions\EmailException;
use Paw\Core\Model;

class Usuario extends Model
{
  public $table = 'usuarios';
  private $id;
  private $nombre;

  private $apellido;

  private $correo;

  private $contraseña;

  private $equipoFavoritoId;

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

  #Getters
  public function getNombre()
  {
    return $this->nombre;
  }

  public function getApellido()
  {
    return $this->apellido;
  }

  public function getCorreo()
  {
    return $this->correo;
  }

  public function getContraseña()
  {
    return $this->contraseña;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getEquipoFavoritoId()
  {
    return $this->equipoFavoritoId;
  }
  #Setters
  public function setNombre($nombre)
  {
    $this->nombre = $nombre;
  }

  public function setApellido($apellido)
  {
    $this->apellido = $apellido;
  }

  public function setCorreo($correo)
  {
    $this->correo = $correo;
  }

  public function setContraseña($contraseña)
  {
    $this->contraseña = $contraseña;
  }

  public function setId($id)
  {
    $this->id = $id;
  }
  public function setEquipoFavoritoId($equipoFavorito)
{
    $this->equipoFavoritoId = $equipoFavorito;
}
}
