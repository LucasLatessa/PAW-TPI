<?php

namespace Paw\App\Models;
use Paw\Core\Model;

class EquipoTorneo extends Model{

    #Asocio el model con la tabla
    private $table = 'equipoTorneo';

    # Defino las propiedades del modelo
    private $id;

    private $id_torneo;

    private $id_equipo;
    private $partidosJugados;
    private $partidosGanados;
    private $partidosEmpatados;
    private $partidosPerdidos;
    private $golesAFavor;
    private $golesContra;
    private $puntos;

    public $equipo;

    # Getters y setters para cada propiedad
    public function getId() {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId_torneo() {
        return $this->id_torneo;
    }

    public function setId_torneo($id_torneo) {
        $this->id_torneo = $id_torneo;
    }

    public function getId_equipo() {
        return $this->id_equipo;
    }

    public function setId_equipo($id_equipo) {
        $this->id_equipo = $id_equipo;
    }

    public function getPartidosJugados()
    {
        return $this->partidosJugados;
    }

    public function setPartidosJugados($partidosJugados){
        $this->partidosJugados = $partidosJugados;
    }

    public function getPartidosGanados()
    {
        return $this->partidosGanados;
    }

    public function setPartidosGanados($partidosGanados){
        $this->partidosGanados = $partidosGanados;
    }

    public function getPartidosEmpatados()
    {
        return $this->partidosEmpatados;
    }

    public function setPartidosEmpatados($partidosEmpatados){
        $this->partidosEmpatados = $partidosEmpatados;
    }

    public function getPartidosPerdidos()
    {
        return $this->partidosPerdidos;
    }

    public function setPartidosPerdidos($partidosPerdidos){
        $this->partidosPerdidos = $partidosPerdidos;
    }

    public function getGolesAFavor()
    {
        return $this->golesAFavor;
    }

    public function setGolesAFavor($golesAFavor){
        $this->golesAFavor = $golesAFavor;
    }

    public function getGolesContra()
    {
        return $this->golesContra;
    }

    public function setGolesContra($golesContra){
        $this->golesContra = $golesContra;
    }

    public function getPuntos()
    {
        return $this->puntos;
    }

    public function setPuntos($puntos){
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