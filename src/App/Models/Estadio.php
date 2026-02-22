<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class Estadio extends Model
{
    private $table = 'estadios';

    private ?int $id = null;
    private string $nombre;
    private ?float $latitud = null;
    private ?float $longitud = null;

    /* ====== CONSTRUCTOR ====== */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->set($data);
        }
    }

    /* ====== SET ====== */
    public function set(array $values)
    {
        foreach ($values as $field => $value) {
            $camelCase = str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
            $method = 'set' . $camelCase;

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /* ====== GETTERS ====== */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getLatitud(): ?float
    {
        return $this->latitud;
    }

    public function getLongitud(): ?float
    {
        return $this->longitud;
    }

    /* ====== SETTERS ====== */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function setLatitud($latitud): self
    {
        //casteamos porque puede venir como string
        $this->latitud = $latitud !== null ? (float) $latitud : null;
        return $this;
    }

    public function setLongitud($longitud): self
    {
        $this->longitud = $longitud !== null ? (float) $longitud : null;
        return $this;
    }
    
}