<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PrimerasTablasMigration extends AbstractMigration
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
        $equipo = $this->table('equipo');
        $equipo
        ->addColumn('nombre','string', ['limit' => 100]) 
        ->addColumn('fecha_creacion', 'datetime')
        ->addColumn('estadio','string', ['limit' => 100]) 
        ->addColumn('descripcion','string', ['limit' => 200])
        ->addColumn('escudo','string', ['limit' => 100]) #esto es imagen
        ->create();

        /* ESTA GENERACIÓN DE TABLAS NO LAS PROBE TODAVIA :) pero
        creeria que así deberian armarse las tablas con sus fk entre torneo y partido
        y partido con equipo  

        $partido = $this->table('partido');
        $partido
        ->addColumn('id_torneo', 'integer', ['signed' => false]) #corrobar si esto es fk contra la tabla torneo
        ->addColumn('id_equipo_local','integer', ['signed' => false]) #corrobar si esto es fk contra la tabla equipo
        ->addColumn('id_equipo_visitante','integer', ['signed' => false]) #corrobar si esto es fk contra la tabla equipo
        ->addColumn('fecha', 'integer') #ver que tipo de fecha seria 
        ->addColumn('golesLocal', 'integer') 
        ->addColumn('golesVisitante', 'integer') 
        ->create();
     
        #revisar esto, ver si ponemos id_torneo ya generado por nosotros o no 
        $torneo = $this->table('torneo');
        $torneo
        ->addColumn('id_partido', 'integer', ['signed' => false]) #corrobar si esto es fk contra la tabla partido 
        ->addColumn('nombre','string', ['limit' => 100]) 
        ->addColumn('fecha_inicio', 'datetime')
        ->addColumn('fecha_fin', 'datetime')
        ->addColumn('categoria','string', ['limit' => 100]) 
        ->addColumn('cantidadEquipos', 'integer')
        ->addColumn('cantidadFechas', 'integer')
        ->addColumn('descripcion','string', ['limit' => 200])
        ->create();

        */

    }


}
