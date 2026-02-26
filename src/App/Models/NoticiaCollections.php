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

    # insertar los datos en la base de datos
    $this->queryBuilder->insert($this->table, $data);

    # asignar el querybuilder y establecer los datos
    $nuevaNoticia->setQueryBuilder($this->queryBuilder);
    $idInsertado = $this->queryBuilder->getPdo()->lastInsertId();
    $data['id'] = $idInsertado;
    $nuevaNoticia->set($data);


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

  public function getID($idNoticia)
  {
    $noticia = $this->queryBuilder->select($this->table, ['id' => $idNoticia])->execute();
    return $noticia[0];
  }
}
