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
      ->addColumn('slug', 'string', [
        'limit' => 255
      ])
      ->addColumn('nombre_pila', 'string', [
        'limit' => 100
      ])
      ->addColumn('fecha_creacion', 'date', [
        'null' => true
      ])
      ->addColumn('escudo', 'string', [
        'null' => true
      ])
      ->addColumn('estadio', 'string', [
        'limit' => 100,
        'null' => true
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
      ->addIndex(['slug'], [
        'unique' => true
      ])
      ->create();
  }
}
