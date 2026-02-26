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
    $noticias = $this->queryBuilder->select($this->table)->execute();

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
  public function getNoticiasPaginadas($pagina = 1, $porPagina = 6)
    {
        $offset = ($pagina - 1) * $porPagina;

        $noticias = $this->queryBuilder
            ->select($this->table)
            ->limit($porPagina)
            ->offset($offset)
            ->execute();

        $noticiasCollection = [];
        foreach ($noticias as $noticiaData) {
            $nuevaNoticia = new Noticia;
            $nuevaNoticia->set($noticiaData);
            $noticiasCollection[] = $nuevaNoticia;
        }

        return $noticiasCollection;
    }
    public function getTotalNoticias()
    {
        $res = $this->queryBuilder
            ->select($this->table)
            ->execute();

        return count($res);
    }

  public function getUltimasNoticias($cantidad)
  {
    # obtener las ultimas noticias ordenadas por fecha descendente
    $noticias = $this->queryBuilder
      ->select($this->table)
      ->order('fecha_publicacion DESC')
      ->limit($cantidad)
      ->execute();

    $noticiasCollection = [];
    foreach ($noticias as $noticiaData) {
      $nuevaNoticia = new Noticia();
      $nuevaNoticia->setQueryBuilder($this->queryBuilder);
      $nuevaNoticia->set($noticiaData);
      $noticiasCollection[] = $nuevaNoticia;
    }

    return $noticiasCollection;
  }


  public function create(Noticia $noticia, $qb)
  {
    $data = [
      'titulo' => $noticia->getTitulo(),
      'descripcion' => $noticia->getDescripcion(),
      'fecha_publicacion' => $noticia->getFechaPublicacion(),
      'imagen' => $noticia->getImagen(),
      'contenido' => $noticia->getContenido(),
      'autor' => $noticia->getAutor()

    ];
    $qb->insert($this->table, $data);
    
    $id = $qb->getPdo()->lastInsertId();
    $noticia->set(['id' => $id]); 

    return $noticia;
  }

  public function update(Noticia $noticia, $qb)
  {
    $data = [
      'titulo' => $noticia->getTitulo(),
      'descripcion' => $noticia->getDescripcion(),
      'fecha_publicacion' => $noticia->getFechaPublicacion(),
      'imagen' => $noticia->getImagen(),
      'contenido' => $noticia->getContenido(),
      'autor' => $noticia->getAutor()
    ];
    $qb->update($this->table, $data, ['id' => $noticia->getId()]);
    
    return $noticia;
  }

  public function delete($id, $qb){
    $qb->delete($this->table, ['id' => $id]);
  }

  public function incrementarVisitas($idNoticia)
  {
    $noticia = $this->getID($idNoticia);
    if ($noticia) {
      $nuevaCantidadVisitas = $noticia['visitas'] + 1;
      $this->queryBuilder->update($this->table, ['visitas' => $nuevaCantidadVisitas], ['id' => $idNoticia]);
    }
  }

  public function getID($idNoticia)
  {
    $noticia = $this->queryBuilder->select($this->table, ['id' => $idNoticia])->execute();
    return $noticia[0];
  }

  public function getImagen($idNoticia)
  {
    $noticia = $this->getID($idNoticia);
    return $noticia['imagen'];
  }
}
