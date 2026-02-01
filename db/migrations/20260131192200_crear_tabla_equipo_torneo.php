<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearTablaEquipoTorneo extends AbstractMigration
{
  public function change(): void
  {
    $table = $this->table('equipo_torneo', [
      'id' => true,
      'signed' => false
    ]);

    $table
      ->addColumn('equipo_id', 'integer', [
        'signed' => false
      ])
      ->addColumn('torneo_id', 'integer', [
        'signed' => false
      ])
      ->addColumn('partidos_jugados', 'integer', [
        'default' => 0
      ])
      ->addColumn('ganados', 'integer', [
        'default' => 0
      ])
      ->addColumn('empatados', 'integer', [
        'default' => 0
      ])
      ->addColumn('perdidos', 'integer', [
        'default' => 0
      ])
      ->addColumn('goles_favor', 'integer', [
        'default' => 0
      ])
      ->addColumn('goles_contra', 'integer', [
        'default' => 0
      ])
      ->addColumn('diferencia_goles', 'integer', [
        'default' => 0
      ])
      ->addColumn('puntos', 'integer', [
        'default' => 0
      ])
      ->addColumn('created_at', 'timestamp', [
        'default' => 'CURRENT_TIMESTAMP'
      ])
      ->addColumn('updated_at', 'timestamp', [
        'null' => true,
        'update' => 'CURRENT_TIMESTAMP'
      ])
      ->addIndex(['equipo_id', 'torneo_id'], [
        'unique' => true
      ])
      ->addForeignKey(
        'equipo_id',
        'equipos',
        'id',
        ['delete' => 'CASCADE']
      )
      ->addForeignKey(
        'torneo_id',
        'torneos',
        'id',
        ['delete' => 'CASCADE']
      )
      ->create();
  }
}
