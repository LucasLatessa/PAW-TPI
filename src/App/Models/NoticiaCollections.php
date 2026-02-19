<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Noticia;

class NoticiaCollections extends Model
{

  public $table = 'noticias';

  public function getAllNoticias()
  {
    # obtener todas las noticias
    $noticias = $this->queryBuilder->selectViejo($this->table);

    # crear una coleccion de objetos noticia
    $noticiasCollection = [];
    foreach ($noticias as $noticiaData) {
      $nuevaNoticia = new Noticia();
      # pasamos el querybuilder por si se usa el load despues
      $nuevaNoticia->setQueryBuilder($this->queryBuilder);
      $nuevaNoticia->set($noticiaData);
      $noticiasCollection[] = $nuevaNoticia;
    }

    return $noticiasCollection;
  }

  public function getUltimasNoticias($cantidad)
  {
    # obtener las ultimas noticias ordenadas por fecha descendente
    $noticias = $this->queryBuilder->selectViejo($this->table, [], 'fecha_publicacion DESC', $cantidad);

    $noticiasCollection = [];
    foreach ($noticias as $noticiaData) {
      $nuevaNoticia = new Noticia();
      $nuevaNoticia->setQueryBuilder($this->queryBuilder);
      $nuevaNoticia->set($noticiaData);
      $noticiasCollection[] = $nuevaNoticia;
    }

    return $noticiasCollection;
  }


  public function create($titulo, $descripcion, $contenido, $autor, $fecha, $imagen)
  {
    $nuevaNoticia = new Noticia();

    $data = [
      'titulo' => $titulo,
      'descripcion' => $descripcion,
      'fecha_publicacion' => $fecha,
      'imagen' => $imagen,
      'contenido' => $contenido,
      'autor' => $autor

    ];

    # asignar el querybuilder y establecer los datos
    $nuevaNoticia->setQueryBuilder($this->queryBuilder);
    $nuevaNoticia->set($data);

    # insertar los datos en la base de datos
    $this->queryBuilder->insert($this->table, $data);

    # retornar la instancia de la nueva noticia creada
    return $nuevaNoticia;
  }
  public function incrementarVisitas($idNoticia)
  {
    $noticia = $this->getID($idNoticia);
    if ($noticia) {
      $nuevaCantidadVisitas = $noticia['visitas'] + 1;
      $this->queryBuilder->update($this->table, ['visitas' => $nuevaCantidadVisitas], ['id' => $idNoticia]);
    }
  }


  // public function getID($id)
  // {
  //   $noticia = new Noticia();
  //   $noticia->setQueryBuilder($this->queryBuilder);

  //   # usamos el metodo load de la entidad noticia que acabamos de crear
  //   return $noticia->load($id);
  // }
  public function getID($idNoticia)
  {
    $noticia = $this->queryBuilder->selectViejo($this->table, ["id" => $idNoticia]);
    return $noticia[0];
  }
}
