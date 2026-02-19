<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearTablaFechas extends AbstractMigration
{
  public function change(): void
  {
    $table = $this->table('fechas', [
      'id' => true,
      'signed' => false
    ]);

    $table
      ->addColumn('numero', 'integer')
      ->addColumn('nombre', 'string', [
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
      ->create();
  }
}
