<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Paw\Core\Database;
final class CrearTorneoEInsertarEquipos extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        // Crear el torneo
        $tablaTorneos = 'torneo';
        $torneo = [
            'nombre' => 'Torneo de Invierno 2024',
            'fecha_inicio' => '2024-07-01',
            'fecha_fin' => '2024-07-31'
        ];
        $this->table($tablaTorneos)->insert($torneo)->save();
        $torneoId = intval($this->getAdapter()->getConnection()->lastInsertId());

        var_dump($torneoId);
        // Obtener todos los equipos
        $equipos = $this->fetchAll('SELECT id FROM equipo');

        // Insertar cada equipo en la tabla equipo_torneo asociado al torneo
        $tablaEquiposTorneo = 'equipotorneo';
        foreach ($equipos as $equipo) {
            $equipoTorneo = [
                'id_torneo' => $torneoId,
                'id_equipo' => $equipo['id']
            ];
            $this->table($tablaEquiposTorneo)->insert($equipoTorneo)->save();
        }
    }
}