<?php

namespace Paw\App\Models;

use Paw\Core\Model;

class Noticia extends Model
{

  private $table = 'noticias';

  private ?int $id = null;
  private string $titulo;
  private string $slug;
  private string $descripcion;
  private string $contenido;
  private string $imagen;
  private string $fecha_publicacion;
  private int $visitas;
  private string $autor;
  
  /* ====== CONSTRUCTOR ====== */
  public function __construct(array $data = [])
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->$key = $value;
      }
    }
  }

  /* ====== LOAD ====== */
  public function load($id)
  {
    $params = ["id" => $id];
    $record = current($this->queryBuilder->selectViejo($this->table, $params));

    if ($record !== false) {
      $this->set($record);
      return $this;
    } else {
      return null;
    }
  }

  /* ====== SET ====== */
  // public function set(array $values)
  // {
  //   foreach ($values as $field => $value) {
  //     #Creo el methodo y si existe lo ejecuto
  //     $method = "set" . ucfirst($field);
  //     if (method_exists($this, $method)) {
  //       $this->$method($value);
  //     }
  //   }
  // }
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

  /* ====== UTILS ====== */

  /* ====== GETTERS ====== */
  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTitulo(): string
  {
    return $this->titulo;
  }

  public function getSlug(): string
  {
    return $this->slug;
  }

  public function getDescripcion(): string
  {
    return $this->descripcion;
  }

  public function getContenido(): string
  {
    return $this->contenido;
  }

  public function getImagen(): string
  {
    return $this->imagen;
  }

  public function getFechaPublicacion(): string
  {
    return $this->fecha_publicacion;
  }

  public function getVisitas(): int
  {
    return $this->visitas;
  }

  public function getAutor(): string
  {
    return $this->autor;
  }

  /* ====== SETTERS ====== */
  public function setId(?int $id): self
  {
    $this->id = $id;
    return $this;
  }

  public function setTitulo(string $titulo): self
  {
    $this->titulo = $titulo;
    return $this;
  }

  public function setSlug(string $slug): self
  {
    $this->slug = $slug;
    return $this;
  }

  public function setDescripcion(string $descripcion): self
  {
    $this->descripcion = $descripcion;
    return $this;
  }

  public function setContenido(string $contenido): self
  {
    $this->contenido = $contenido;
    return $this;
  }

  public function setImagen(string $imagen): self
  {
    $this->imagen = $imagen;
    return $this;
  }

  public function setFechaPublicacion(string $fechaPublicacion): self
  {
    $this->fecha_publicacion = $fechaPublicacion;
    return $this;
  }

  public function setVisitas(int $visitas): self
  {
    $this->visitas = $visitas;
    return $this;
  }

  public function setAutor(string $autor): self
  {
    $this->autor = $autor;
    return $this;
  }
}