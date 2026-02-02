<?php

namespace Paw\App\Models;
use Paw\Core\Model;

class Noticia extends Model {

    #Asocio el model con la tabla
    private $table = 'noticia';

    # Defino las propiedades del modelo
    private $id;
    private $titulo;
    private $descripcion;
    private $fecha;
    private $imagen;

    # Getters y setters para cada propiedad
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    # Para aplicar todos los setters junto con sus validaciones
    public function set(array $values) {
        foreach ($values as $field => $value) {
            # Creo el método y si existe lo ejecuto
            $method = "set" . ucfirst($field);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    # Cargar una noticia específica por ID
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
