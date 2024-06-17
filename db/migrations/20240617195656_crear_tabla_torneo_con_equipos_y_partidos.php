<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearTablaTorneoConEquiposYPartidos extends AbstractMigration
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
        $torneo = $this->table('torneo');
        $torneo
        ->addColumn('nombre','string', ['limit' => 60])
        ->addColumn('fecha_inicio','date')
        ->addColumn('fecha_fin','date')
        ->create();

        $equipoEnTorneo = $this->table('equipoTorneo');
        $equipoEnTorneo
        ->addColumn('id_torneo','integer', ['signed' => false])
        ->addColumn('id_equipo','integer', ['signed' => false])
        ->addColumn('partidosJugados','integer', ['default' => 0])
        ->addColumn('partidosGanados','integer', ['default' => 0])
        ->addColumn('partidosEmpatados','integer', ['default' => 0])
        ->addColumn('partidosPerdidos','integer', ['default' => 0])
        ->addColumn('golesAFavor','integer', ['default' => 0])
        ->addColumn('golesContra','integer', ['default' => 0])
        ->addColumn('puntos','integer', ['default' => 0])
        ->addForeignKey('id_equipo', 'equipo', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
        ->addForeignKey('id_torneo', 'torneo', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
        ->create();

        $fecha = $this->table('fecha');
        $fecha
        ->addColumn('id_torneo','integer', ['signed' => false])
        ->addColumn('numero_fecha','integer')
        ->addForeignKey('id_torneo', 'torneo', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
        ->create();

        $partido = $this->table('partido');
        $partido
        ->addColumn('id_fecha','integer', ['signed' => false])
        ->addColumn('id_equipo_local','integer', ['signed' => false])
        ->addColumn('id_equipo_visitante','integer', ['signed' => false])
        ->addColumn('fecha','date')
        ->addColumn('golesLocal','integer')
        ->addColumn('golesVisitante','integer')
        ->addColumn('horario','time')
        ->addForeignKey('id_equipo_local', 'equipo', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
        ->addForeignKey('id_equipo_visitante', 'equipo', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
        ->addForeignKey('id_fecha', 'fecha', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
        ->create();

    }
}
