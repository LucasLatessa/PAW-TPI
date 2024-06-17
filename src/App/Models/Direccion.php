<?php

namespace Paw\App\Models;

class Direccion{

    private $pais;
    private $provincia;
    private $ciudad;
    private $ccpp;
    private $direccion;
    private $aclaraciones;

    public function __construct($pais, $provincia, $ciudad, $ccpp, $direccion, $aclaraciones ) {
        if (empty($pais) || empty($provincia) || empty($ciudad) || empty($ccpp) || empty($direccion)) {
            throw new \InvalidArgumentException("Los campos país, provincia, ciudad, código postal y dirección son obligatorios.");
        }
        $this->pais = $pais;
        $this->provincia = $provincia;
        $this->ciudad = $ciudad;
        $this->ccpp = $ccpp;
        $this->direccion = $direccion;
        $this->aclaraciones = $aclaraciones;
    }

    public function getPais() {
        return $this->pais;
    }

    public function getProvincia() {
        return $this->provincia;
    }

    public function getCiudad() {
        return $this->ciudad;
    }

    public function getCcpp() {
        return $this->ccpp;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getAclaraciones() {
        return $this->aclaraciones;
    }

}