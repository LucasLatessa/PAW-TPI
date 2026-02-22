<?php
namespace Paw\App\Models;

use Paw\Core\Model;

class FechaCollections extends Model
{
    protected $table = 'fechas';

    public function getByTorneo(int $torneoId): array
    {
        $partidos = $this->queryBuilder
            ->select('partidos', ['torneo_id' => $torneoId])
            ->execute();

        $idsFechas = array_unique(array_column($partidos, 'fecha_id'));
        
        sort($idsFechas);

        $fechasCollection = [];
        foreach ($idsFechas as $id) {
            $fechaObjeto = $this->getFecha((int) $id);
            if ($fechaObjeto) {
                $fechasCollection[] = $fechaObjeto;
            }
        }
        return $fechasCollection;
    }
  public function getFecha(int $id): ?Fecha
    {
        $fechaData = $this->queryBuilder
              ->select($this->table, ['id' => $id])
              ->execute();

        if (! $fechaData) {
            return null;
        }

        $data = $fechaData[0];

        $fecha = new Fecha();
        $fecha->set($data);
        return $fecha;
    }

}
