<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearTablaTorneos extends AbstractMigration
{

  public function change(): void
  {
    $table = $this->table('torneos', [
      'id' => true,
      'signed' => false
    ]);

    $table
      ->addColumn('nombre', 'string', [
        'limit' => 255
      ])
      ->addColumn('categoria', 'string', [
        'limit' => 255
      ])
      ->addColumn('temporada', 'string', [
        'limit' => 255
      ])
      ->addColumn('descripcion', 'text', [
        'null' => true
      ])
      ->addColumn('estado', 'enum', [
        'values' => ['activo', 'finalizado'],
        'default' => 'activo'
      ])
      ->addColumn('fecha_inicio', 'date')
      ->addColumn('fecha_fin', 'date', [
        'null' => true
      ])
      ->addColumn('created_at', 'timestamp', [
        'default' => 'CURRENT_TIMESTAMP'
      ])
      ->addColumn('updated_at', 'timestamp', [
        'null' => true,
        'update' => 'CURRENT_TIMESTAMP'
      ])
      ->create();
  }
}
