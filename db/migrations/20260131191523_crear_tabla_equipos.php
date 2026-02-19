<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearTablaEquipos extends AbstractMigration
{

  public function change(): void
  {
    $table = $this->table('equipos', [
      'id' => true,             
      'signed' => false
    ]);

    $table
      ->addColumn('nombre', 'string', [
        'limit' => 100
      ])
      ->addColumn('nombre_institucional', 'string', [
        'limit' => 100
      ])
      ->addColumn('fecha_creacion', 'date', [
        'null' => true
      ])
      ->addColumn('escudo', 'string', [
        'null' => true
      ])
      ->addColumn('estadio_id', 'integer', [
        'signed' => false, 
        'null' => true, 
      ])
      ->addColumn('descripcion', 'text', [
        'null' => true
      ])
      ->addColumn('activo', 'boolean', [
        'default' => true
      ])
      ->addColumn('created_at', 'timestamp', [
        'default' => 'CURRENT_TIMESTAMP'
      ])
      ->addColumn('updated_at', 'timestamp', [
        'null' => true,
        'update' => 'CURRENT_TIMESTAMP'
      ])
      ->addForeignKey(
        'estadio_id',
        'estadios',
        'id',['delete'=> 'SET_NULL','update'=> 'CASCADE']
      )
      ->create();
  }
}
