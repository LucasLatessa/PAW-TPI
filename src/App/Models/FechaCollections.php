<?php
namespace Paw\App\Models;

use Paw\Core\Model;

class FechaCollections extends Model
{
    protected $table = 'fechas';

    public function getByTorneo(int $torneoId): array
    {
        $partidos = $this->queryBuilder->selectViejo('partidos', ['torneo_id' => $torneoId]);

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
        $fechaData = $this->queryBuilder->selectViejo($this->table, ['id' => $id]);

        if (! $fechaData) {
            return null;
        }

        $data = $fechaData[0];

        $fecha = new Fecha();
        $fecha->set($data);
        return $fecha;
    }

}
