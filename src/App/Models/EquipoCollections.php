<?php
namespace Paw\App\Models;

use Paw\App\Models\Equipo;
use Paw\Core\Database\QueryBuilder;
use Paw\Core\Model;

class EquipoCollections extends Model
{
    public $table = 'equipos';

    public function create(Equipo $equipo)
    {
        $data = [
            'nombre'               => $equipo->getNombre(), 
            'nombre_institucional' => $equipo->getNombreInstitucional(),
            'fecha_creacion'       => $equipo->getFechaCreacion(),
            'estadio_id'           => $equipo->getEstadioId(),
            'descripcion'          => $equipo->getDescripcion(),
            'escudo'               => $equipo->getEscudo(),
        ];

        $this->queryBuilder->insert($this->table, $data);
        
        $id = $this->queryBuilder->getPdo()->lastInsertId();
        $equipo->set(['id' => $id]); 

        return $equipo;
    }

    public function update(Equipo $equipo, $qb)
    {
      $data = [
        'nombre' => $equipo->getNombre(),
        'nombre_institucional' => $equipo->getNombreInstitucional(),
        'fecha_creacion' => $equipo->getFechaCreacion(),
        'estadio_id' => $equipo->getEstadioId(),
        'descripcion' => $equipo->getDescripcion(),
        'escudo' => $equipo->getEscudo()
      ];
      $qb->update($this->table, $data, ['id' => $equipo->getId()]);
      
      return $equipo;
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

    public function getEscudo($idEquipo)
    {
      $equipo = $this->getID($idEquipo);
      return $equipo->getEscudo();
    }

    public function getEstadio($idEquipo)
    {
      $equipo = $this->getID($idEquipo);
      
      return $equipo->getEstadio()->getId();
    }
}
