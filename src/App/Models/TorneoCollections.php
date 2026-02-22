<?php
namespace Paw\App\Models;

use Paw\App\Models\Torneo;
use Paw\Core\Model;

class TorneoCollections extends Model
{
    public $table = 'torneos';

    public function create($nombreTorneo, $categoria, $temporada, $descripcion, $fechaInicio, $fechaFin)
    {
        $newTorneo = new Torneo;
        $data      = [
            'nombre'       => $nombreTorneo,
            'categoria'    => $categoria,
            'temporada'    => $temporada,
            'descripcion'  => $descripcion,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
        ];

        $this->queryBuilder->insert($this->table, $data);

        $idInsertado = $this->queryBuilder->getPdo()->lastInsertId();

        $newTorneo->setQueryBuilder($this->queryBuilder);

        $data['id'] = $idInsertado;
        $newTorneo->set($data);

        return $newTorneo;
    }

    public function getAllTorneos()
    {
        $torneos = $this->queryBuilder
            ->select($this->table)
            ->execute();
        $torneosCollection = [];
        foreach ($torneos as $torneo) {
            $nuevoTorneo = new Torneo();
            $nuevoTorneo->set($torneo);
            $torneosCollection[] = $nuevoTorneo;
        }
        return $torneosCollection;
    }
    public function getTorneosPaginados($pagina = 1, $porPagina = 12)
    {
        $offset = ($pagina - 1) * $porPagina;

        $torneos = $this->queryBuilder
            ->select($this->table)
            ->limit($porPagina)
            ->offset($offset)
            ->execute();

        $torneoCollection = [];
        foreach ($torneos as $torneoData) {
            $nuevoTorneo = new Torneo;
            $nuevoTorneo->set($torneoData);
            $torneoCollection[] = $nuevoTorneo;
        }

        return $torneoCollection;
    }
    public function getTotalTorneos()
    {
        $res = $this->queryBuilder
            ->select($this->table)
            ->execute();

        return count($res);
    }

    public function getPrimerTorneo()
    {
        $torneos = $this->queryBuilder
            ->select($this->table)
            ->order('id ASC')
            ->limit(1)
            ->execute();
        return $torneos ? $torneos[0] : null;
        //return null;
    }

    public function getTorneo($idTorneo)
    {
        // Buscamos la data en la tabla 'torneos'
        $res = $this->queryBuilder
            ->select($this->table, ['id' => $idTorneo])
            ->execute();
        // Si no hay nada, devolvemos null
        if (! $res) {
            return null;
        }
        // Tomamos el primer registro del array
        $data   = $res[0];
        $torneo = new Torneo();
        $torneo->set($data);

        return $torneo;
    }

    // La idea es que la Query sea casi automatica por el QueryBuilder
    public function getTablaPosiciones($idTorneo)
    {
        $tabla = $this->queryBuilder
            ->select('equipo_torneo')
            ->join('equipos', 'equipo_torneo.equipo_id = equipos.id')
            ->where("equipo_torneo.torneo_id = :idTorneo")
            ->setParam('idTorneo', $idTorneo)
            ->order('equipo_torneo.puntos DESC, equipo_torneo.diferencia_goles DESC, equipo_torneo.goles_favor DESC')
            ->execute();

        return $tabla ? $tabla : null;
    }
    public function vincularEquiposAlTorneo($torneoId, $equiposIds)
    {
        try {
            foreach ($equiposIds as $equipoId) {
                // verificamos si la relacion ya existe para no duplicar

                $existe = $this->queryBuilder
                    ->select('equipo_torneo', ['torneo_id' => $torneoId, 'equipo_id' => $equipoId])
                    ->execute();

                // insertamos si no existe
                if (empty($existe)) {
                    $data = [
                        'torneo_id' => $torneoId,
                        'equipo_id' => $equipoId,
                    ];
                    $this->queryBuilder->insert('equipo_torneo', $data);
                }
            }
            return true;

        } catch (\Exception $e) {
            error_log("Error en vincular_equipos: " . $e->getMessage());
            return false;
        }
    }
    public function generarFixtureAutomatico($torneoId, $equiposIds, $modelPartido, $fechaInicio)
    {
        $cantidadEquipos = count($equiposIds);
        if ($cantidadEquipos < 2) {
            return;
        }

        shuffle($equiposIds);

        // si es impar, agregamos un null (FECHA LIBRE)
        if ($cantidadEquipos % 2 != 0) {
            $equiposIds[] = null;
            $cantidadEquipos++;
        }
        $cantidadFechas   = $cantidadEquipos - 1;
        $partidosPorFecha = $cantidadEquipos / 2;

        for ($i = 0; $i < $cantidadFechas; $i++) {
            $nroFechaTorneo = $i + 1;

            for ($j = 0; $j < $partidosPorFecha; $j++) {
                $local     = $equiposIds[$j];
                $visitante = $equiposIds[$cantidadEquipos - 1 - $j];

                // Solo programamos si ninguno de los dos es el equipo LIBRE
                if ($local !== null && $visitante !== null) {
                    $modelPartidoCollections = new PartidoCollections();
                    $modelPartidoCollections->setQueryBuilder($this->queryBuilder);
                    $modelPartidoCollections->programarPartido(
                        $torneoId,
                        $nroFechaTorneo,
                        $local,
                        $visitante
                    );
                }
            }
            // Rotacion (fijamos el primero, rotamos el resto)
            $ultimo = array_pop($equiposIds);
            array_splice($equiposIds, 1, 0, [$ultimo]);
        }
    }

    public function getCantidadEquipos($idTorneo)
    {
        $cantEquipos = $this->queryBuilder->count(
            'equipo_torneo', ['torneo_id' => $idTorneo]
        );
        return $cantEquipos ? $cantEquipos : 0;
    }
    // Obtener los equipos vinculados a un torneo
    public function getAllEquipos($torneoId)
    {
        $equipos = $this->queryBuilder
            ->select('equipo_torneo')
            ->join('equipos', 'equipo_torneo.equipo_id = equipos.id')
            ->where("equipo_torneo.torneo_id = :torneoId")
            ->setParam('torneoId', $torneoId)
            ->execute();

        $equiposCollection = [];
        foreach ($equipos as $equipoData) {
            $nuevoEquipo = new Equipo;
            $nuevoEquipo->set($equipoData);
            $equiposCollection[] = $nuevoEquipo;
        }

        return $equiposCollection;
    }

    public function getUltimosPartidos($idTorneo)
    {
        $partidoCollection = new PartidoCollections();
        $partidoCollection->setQueryBuilder($this->queryBuilder);

        return $partidoCollection->getUltimosPorTorneo($idTorneo, 3);
    }

    public function getFechasDeTorneo($idTorneo)
    {
        $fechaCollection = new FechaCollections();
        $fechaCollection->setQueryBuilder($this->queryBuilder);

        return $fechaCollection->getByTorneo($idTorneo);
    }
    public function getFecha(int $idFecha): ?Fecha
    {
        $record = $this->queryBuilder
            ->select('fechas', ['id' => $idFecha])
            ->execute();
        if (! $record || empty($record)) {
            return null;
        }
        $nuevaFecha = new Fecha();
        $nuevaFecha->set($record[0]);
        return $nuevaFecha;
    }

    public function getPartidosFecha($idTorneo, $idFecha)
    {
        $partidoCollection = new PartidoCollections();
        $partidoCollection->setQueryBuilder($this->queryBuilder);

        return $partidoCollection->getPartidosByFecha($idTorneo, $idFecha);
    }

    public function getCategorias()
    {
        $rows = $this->queryBuilder
            ->select($this->table)
            ->addSelect('categoria')
            ->execute();

        return array_map(function ($row) {
            return $row['categoria'];
        }, $rows);
    }
}
