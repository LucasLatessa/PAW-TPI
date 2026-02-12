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
            // 'cantidadEquipos' => $cantidadEquipos,
            // 'cantidadFechas' => $cantidadFechas
        ];

        // Asignar el QueryBuilder y establecer los datos del equipo
        $newTorneo->setQueryBuilder($this->queryBuilder);
        $newTorneo->set($data);

        // Insertar los datos en la base de datos
        $this->queryBuilder->insert($this->table, $data);

        // Retornar la instancia del nuevo equipo creado
        return $newTorneo;
    }

    public function getAllTorneos()
    {
        $torneos           = $this->queryBuilder->selectViejo($this->table);
        $torneosCollection = [];
        foreach ($torneos as $torneo) {
            $nuevoTorneo = new Torneo();
            $nuevoTorneo->set($torneo);
            $torneosCollection[] = $nuevoTorneo;
        }
        return $torneosCollection;
    }

    public function getTorneo($idTorneo)
    {
        // Buscamos la data en la tabla 'torneos'
        $res = $this->queryBuilder->selectViejo($this->table, ['id' => $idTorneo]);
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
        $tabla = $this->queryBuilder->selectJoin(
            'equipo_torneo et',
            [
                ['equipos e', 'e.id = et.equipo_id'],
            ],
            ['torneo_id' => $idTorneo],
            'et.puntos DESC, et.diferencia_goles DESC, et.goles_favor DESC',
        );
        return $tabla ? $tabla : null;
    }
    public function vincularEquiposAlTorneo($torneoId, $equiposIds)
    {
        try {
            foreach ($equiposIds as $equipoId) {
              // verificamos si la relacion ya existe para no duplicar
              $existe = $this->queryBuilder->selectViejo('equipo_torneo', [
                  'torneo_id' => $torneoId,
                  'equipo_id' => $equipoId
              ]);

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
        $equipos = $this->queryBuilder->selectJoin(
            'equipo_torneo et',
            [
                ['equipos e', 'e.id = et.equipo_id'],
            ],
            ['torneo_id' => $torneoId]
        );

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

    public function getFixture(int $idTorneo)
    {
        $fechaCollection = new FechaCollections();
        $fechaCollection->setQueryBuilder($this->queryBuilder);

        return $fechaCollection->getByTorneo($idTorneo);
    }

    public function getFecha(int $idTorneo, int $idFecha)
    {
        $fechaCollection = new FechaCollections();
        $fechaCollection->setQueryBuilder($this->queryBuilder);

        return $fechaCollection->getFecha($idTorneo, $idFecha);
    }

    public function getPartidosFecha($idTorneo, $idFecha)
    {
        $partidoCollection = new PartidoCollections();
        $partidoCollection->setQueryBuilder($this->queryBuilder);

        return $partidoCollection->getPartidosByFecha($idTorneo, $idFecha);
    }
}
