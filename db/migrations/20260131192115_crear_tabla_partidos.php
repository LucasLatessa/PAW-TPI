<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearTablaPartidos extends AbstractMigration
{
  public function change(): void
  {
    $table = $this->table('partidos', [
      'id' => true,
      'signed' => false
    ]);

    $table
      ->addColumn('torneo_id', 'integer', [
        'signed' => false
      ])
      ->addColumn('fecha_id', 'integer', [
        'signed' => false
      ])
      ->addColumn('fecha_partido', 'date')
      ->addColumn('hora_partido', 'time', [
        'null' => true
      ])
      ->addColumn('equipo_local_id', 'integer', [
        'signed' => false
      ])
      ->addColumn('equipo_visitante_id', 'integer', [
        'signed' => false
      ])
      ->addColumn('goles_local', 'integer', [
        'null' => true
      ])
      ->addColumn('goles_visitante', 'integer', [
        'null' => true
      ])
      ->addColumn('estado', 'enum', [
        'values' => ['programado', 'en_juego', 'finalizado'],
        'default' => 'programado'
      ])
      ->addColumn('cancha', 'string', [
        'limit' => 255,
        'null' => true
      ])
      ->addColumn('created_at', 'timestamp', [
        'default' => 'CURRENT_TIMESTAMP'
      ])
      ->addColumn('updated_at', 'timestamp', [
        'null' => true,
        'update' => 'CURRENT_TIMESTAMP'
      ])

      // Foreign Keys
      ->addForeignKey(
        'torneo_id',
        'torneos',
        'id',
        ['delete' => 'CASCADE']
      )
      ->addForeignKey(
        'fecha_id',
        'fechas',
        'id',
        ['delete' => 'CASCADE']
      )
      ->addForeignKey(
        'equipo_local_id',
        'equipos',
        'id'
      )
      ->addForeignKey(
        'equipo_visitante_id',
        'equipos',
        'id'
      )
      ->create();
  }
}
