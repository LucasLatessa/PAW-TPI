<?php
namespace Paw\App\Models;

use Paw\App\Models\Equipo;
use Paw\Core\Database\QueryBuilder;
use Paw\Core\Model;

class EquipoCollections extends Model
{
    public $table = 'equipos';

    public function create($nombreEquipo, $nombreEquipoInstitucional, $fechaCreacion, $estadio, $descripcion, $imagen)
    {
        $newEquipo = new Equipo;
        $pathImagen = 'escudos/' . $imagen;
        $data = [
            'nombre'               => $nombreEquipo,
            'nombre_institucional' => $nombreEquipoInstitucional,
            'fecha_creacion'       => $fechaCreacion,
            'estadio_id'           => $estadio,
            'descripcion'          => $descripcion,
            'escudo'               => $pathImagen,
        ];

        $this->queryBuilder->insert($this->table, $data);
        
        $idInsertado = $this->queryBuilder->getPdo()->lastInsertId();

        $newEquipo->setQueryBuilder($this->queryBuilder);
        
        $data['id'] = $idInsertado;
        $newEquipo->set($data); 

        return $newEquipo;
    }

    public function getAllEquipos()
    {
        // Obtener todos los equipos usando el metodo select de QueryBuilder
        $equipos = $this->queryBuilder->select($this->table)->execute();

        // Crear una coleccion de objetos Equipo
        $equiposCollection = [];
        foreach ($equipos as $equipoData) {
            $nuevoEquipo = new Equipo;
            $nuevoEquipo->set($equipoData);
            $equiposCollection[] = $nuevoEquipo;
        }

        return $equiposCollection;
    }
    public function getEquiposPaginados($pagina = 1, $porPagina = 12)
    {
        $offset = ($pagina - 1) * $porPagina;

        $equipos = $this->queryBuilder
            ->select($this->table)
            ->limit($porPagina)
            ->offset($offset)
            ->execute();

        $equiposCollection = [];
        foreach ($equipos as $equipoData) {
            $nuevoEquipo = new Equipo;
            $nuevoEquipo->set($equipoData);
            $equiposCollection[] = $nuevoEquipo;
        }

        return $equiposCollection;
    }
    public function getTotalEquipos()
    {
        $res = $this->queryBuilder
            ->select($this->table)
            ->execute();

        return count($res);
    }
    public function getID(int $id): ?Equipo
    { 
        $record = $this->queryBuilder->select('equipos', ['id' => $id])->execute();

        if (empty($record)) {
            return null;
        }

        $equipo = new Equipo();
        $equipo->set($record[0]);

        // si el equipo tiene un estadio_id
        if ($equipo->getEstadioId()) {
            $dataEstadio = $this->queryBuilder
                ->select('estadios', ['id' => $equipo->getEstadioId()])
                ->execute();

            if (! empty($dataEstadio)) {
                $estadio = new Estadio();
                $estadio->set($dataEstadio[0]);
                // inyectamos objeto estadio al equipo
                $equipo->setEstadio($estadio);
            }
        }

        return $equipo;
    }
}
