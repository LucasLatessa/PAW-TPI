<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class EquipoTorneo extends Model
{

  #Asocio el model con la tabla
  private $table = 'equipo_torneo';

  # Defino las propiedades del modelo
  private $id;

  private $torneo_id;

  private $equipo_id;
  private $partidosJugados;
  private $partidosGanados;
  private $partidosEmpatados;
  private $partidosPerdidos;
  private $golesAFavor;
  private $golesContra;
  private $puntos;

  private $diferencia;

  public $equipo;

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

  # Getters y setters para cada propiedad
  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getTorneo_id()
  {
    return $this->torneo_id;
  }

  public function setTorneo_id($torneo_id)
  {
    $this->torneo_id = $torneo_id;
  }

  public function getEquipo_id()
  {
    return $this->equipo_id;
  }

  public function setEquipo_id($equipo_id)
  {
    $this->equipo_id = $equipo_id;
  }

  public function getPartidosJugados()
  {
    return $this->partidosJugados;
  }

  public function setPartidosJugados($partidosJugados)
  {
    $this->partidosJugados = $partidosJugados;
  }

  public function getPartidosGanados()
  {
    return $this->partidosGanados;
  }

  public function setPartidosGanados($partidosGanados)
  {
    $this->partidosGanados = $partidosGanados;
  }

  public function getPartidosEmpatados()
  {
    return $this->partidosEmpatados;
  }

  public function setPartidosEmpatados($partidosEmpatados)
  {
    $this->partidosEmpatados = $partidosEmpatados;
  }

  public function getPartidosPerdidos()
  {
    return $this->partidosPerdidos;
  }

  public function setPartidosPerdidos($partidosPerdidos)
  {
    $this->partidosPerdidos = $partidosPerdidos;
  }

  public function getGolesAFavor()
  {
    return $this->golesAFavor;
  }

  public function setGolesAFavor($golesAFavor)
  {
    $this->golesAFavor = $golesAFavor;
  }

  public function getGolesContra()
  {
    return $this->golesContra;
  }

  public function setGolesContra($golesContra)
  {
    $this->golesContra = $golesContra;
  }

  public function getPuntos()
  {
    return $this->puntos;
  }

  public function setPuntos($puntos)
  {
    $this->puntos = $puntos;
  }

  public function getEquipo()
  {
    return $this->equipo;
  }

  public function setEquipo($equipo)
  {
    $this->equipo = $equipo;
  }

  public function getDiferencia()
  {
    return $this->diferencia;
  }

  public function setDiferencia()
  {
    $this->diferencia = $this->golesAFavor - $this->golesContra;
  }
}
