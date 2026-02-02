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
