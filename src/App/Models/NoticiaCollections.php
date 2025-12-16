<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Noticia;

class NoticiaCollections extends Model {
    
    public $table = 'noticia';

    /**
     * Trae todas las noticias y devuelve un array de objetos Noticia
     */
    public function getAllNoticias() {
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
    /**
     * Trae las ultimas N noticias ordenadas por fecha descendente
     */
    public function getUltimasNoticias($cantidad) {
        # obtener las ultimas noticias ordenadas por fecha descendente
        $noticias = $this->queryBuilder->selectViejo($this->table, [], 'fecha DESC', $cantidad);
        
        $noticiasCollection = [];
        foreach ($noticias as $noticiaData) {
            $nuevaNoticia = new Noticia();
            $nuevaNoticia->setQueryBuilder($this->queryBuilder);
            $nuevaNoticia->set($noticiaData);
            $noticiasCollection[] = $nuevaNoticia;
        }
        
        return $noticiasCollection;
    }


    public function create($titulo, $descripcion, $fecha, $imagen) {
        $nuevaNoticia = new Noticia(); 
        
        $data = [
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'fecha' => $fecha,
            'imagen' => $imagen
        ];

        # asignar el querybuilder y establecer los datos
        $nuevaNoticia->setQueryBuilder($this->queryBuilder);
        $nuevaNoticia->set($data);

        # insertar los datos en la base de datos
        $this->queryBuilder->insert($this->table, $data);

        # retornar la instancia de la nueva noticia creada
        return $nuevaNoticia;
    }

    # busqueda por id devolviendo objeto (mas prolijo que array suelto)
    public function getById($id) {
        $noticia = new Noticia();
        $noticia->setQueryBuilder($this->queryBuilder);
        # usamos el metodo load de la entidad noticia que acabamos de crear
        return $noticia->load($id);
    }
}