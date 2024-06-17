<?php

namespace Paw\App\Models;
use Paw\Core\Model;

class Equipo extends Model{

    #Asocio el model con la tabla
    private $table = 'equipo';

    # Defino las propiedades del modelo
    private $id;
    private $nombre;
    private $fecha_creacion;
    private $escudo;
    private $estadio;
    private $descripcion;

    # Getters y setters para cada propiedad
    public function getId() {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getFechaCreacion() {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getEscudo() {
        return $this->escudo;
    }

    public function setEscudo($escudo) {
        $this->escudo = $escudo;
    }

    public function getEstadio() {
        return $this->estadio;
    }

    public function setEstadio($estadio) {
        $this->estadio = $estadio;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    #Para aplicar todos los seters junto con sus validaciones
    public function set(array $values)
    {
        foreach ($values as $field => $value) {
            #Creo el methodo y si existe lo ejecuto
            $method = "set" . ucfirst($field);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function load($id){
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