<?php

namespace Paw\App\Models;

use Paw\App\Models\Equipo;
use Paw\App\Models\Torneo;
use Paw\Core\Model;

class Partido extends Model {
    private $id;
    private $id_torneo; // ID del torneo al que pertenece el partido
    private $equipoLocal; // Objeto Equipo representando al equipo local
    private $equipoVisitante; // Objeto Equipo representando al equipo visitante
    private $fecha;
    private $golesLocal;
    private $golesVisitante;

    // Constructor para inicializar las propiedades
    public function __construct() {
   
    }

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

    public function getEquipoLocal() {
        return $this->equipoLocal;
    }

    public function setEquipoLocal(Equipo $equipoLocal) {
        $this->equipoLocal = $equipoLocal;
    }

    public function getEquipoVisitante() {
        return $this->equipoVisitante;
    }

    public function setEquipoVisitante(Equipo $equipoVisitante) {
        $this->equipoVisitante = $equipoVisitante;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
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
}
