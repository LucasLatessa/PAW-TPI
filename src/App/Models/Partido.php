<?php

namespace Paw\App\Models;

use Paw\App\Models\Equipo;
use Paw\App\Models\Torneo;
use Paw\Core\Model;

class Partido extends Model {
    protected $table = 'partido';
    private $id;
    private $id_torneo; // ID del torneo al que pertenece el partido
    private $id_equipo_local;
    private $id_equipo_visitante;
    private $equipoLocal; // Objeto Equipo representando al equipo local
    private $equipoVisitante; // Objeto Equipo representando al equipo visitante
    private $fecha_torneo;
    private $fecha;
    private $horario;
    private $golesLocal;
    private $golesVisitante;

    // Getters y setters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdTorneo() {
        return $this->id_torneo;
    }

    public function setIdTorneo($id_torneo) {
        $this->id_torneo = $id_torneo;
    }
    public function setIdEquipoLocal($id) {
        $this->id_equipo_local = $id;
    }
    public function setIdEquipoVisitante($id) {
        $this->id_equipo_visitante = $id;
    }
    public function getIdEquipoLocal() {
        return $this->id_equipo_local;
    }
    public function getIdEquipoVisitante() {
        return $this->id_equipo_visitante;
    }

    public function getEquipoLocal() {
        return $this->equipoLocal;
    }

    public function setEquipoLocal($equipoLocal) {
        $this->equipoLocal = $equipoLocal;
    }

    public function getEquipoVisitante() {
        return $this->equipoVisitante;
    }

    public function setEquipoVisitante($equipoVisitante) {
        $this->equipoVisitante = $equipoVisitante;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function getHorario() {
        return $this->horario;
    }

    public function setHorario($horario) {
        $this->horario = $horario;
    }
     public function getFechaTorneo() {
        return $this->fecha_torneo;
    }

    public function setFechaTorneo($fecha_torneo) {
        $this->fecha_torneo = $fecha_torneo;
    }

    public function getGolesLocal() {
        return $this->golesLocal;
    }

    public function setGolesLocal($golesLocal) {
        $this->golesLocal = $golesLocal;
    }

    public function getGolesVisitante() {
        return $this->golesVisitante;
    }

    public function setGolesVisitante($golesVisitante) {
        $this->golesVisitante = $golesVisitante;
    }



    public function set(array $values) {
        foreach ($values as $field => $value) {
            $camelField = str_replace('_', '', ucwords($field, '_'));
            
            $method = "set" . $camelField;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
    
    public function load($id) {
        $params = ["id" => $id];
        $record = current($this->queryBuilder->selectViejo($this->table, $params));

        if ($record !== false) {
            $this->set($record); 
            return $this;
        } else {
            return null;
        }
    }
}