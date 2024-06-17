<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Partido;

class Torneo extends Model {

    private $table = 'torneo';

    private $id;
    private $nombre;
    private $fechaInicio;
    private $fechaFin;
    // private $categoria;
    // private $cantidadEquipos;
    // private $cantidadFechas;
    // private $descripcion;

    private $partidos = [];

    // Constructor
    public function __construct() {
        
    }

    // Método para agregar un partido al torneo
    public function agregarPartido(Partido $partido) {
        $this->partidos[] = $partido;
    }

    // Método para obtener todos los partidos del torneo
    public function getPartidos() {
        return $this->partidos;
    }

    // Getters y setters para cada propiedad
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function setFechaInicio($fechaInicio) {
        $this->fechaInicio = $fechaInicio;
    }

    public function getFechaFin() {
        return $this->fechaFin;
    }

    public function setFechaFin($fechaFin) {
        $this->fechaFin = $fechaFin;
    }

    // public function getCategoria() {
    //     return $this->categoria;
    // }

    // public function setCategoria($categoria) {
    //     $this->categoria = $categoria;
    // }

    // public function getCantidadEquipos() {
    //     return $this->cantidadEquipos;
    // }

    // public function setCantidadEquipos($cantidadEquipos) {
    //     $this->cantidadEquipos = $cantidadEquipos;
    // }

    // public function getCantidadFechas() {
    //     return $this->cantidadFechas;
    // }

    // public function setCantidadFechas($cantidadFechas) {
    //     $this->cantidadFechas = $cantidadFechas;
    // }

    // public function getDescripcion() {
    //     return $this->descripcion;
    // }

    // public function setDescripcion($descripcion) {
    //     $this->descripcion = $descripcion;
    // }

    // Método para cargar datos de un torneo desde la base de datos
    public function load($id) {
      
        $params = ["id" => $id];
        $record = current($this->queryBuilder->select($this->table, $params));

        if ($record !== false) {
            $this->set($record); // Utiliza el método set para aplicar los datos obtenidos a las propiedades del objeto
            return $this;
        } else {
            return null;
        }
    }

    // Método para establecer múltiples propiedades a la vez
    public function set(array $values) {
        foreach ($values as $field => $value) {
            $method = "set" . ucfirst($field);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // Otros métodos relacionados con la manipulación de datos del torneo
    // Por ejemplo, guardar, actualizar, eliminar, etc.
}

