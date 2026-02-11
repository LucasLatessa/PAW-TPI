<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearTablaNoticias extends AbstractMigration
{
  public function change(): void
  {
    $table = $this->table('noticias', [
      'id' => true,
      'signed' => false
    ]);

    $table
      ->addColumn('titulo', 'string', [
        'limit' => 255
      ])
      ->addColumn('descripcion', 'text')
      ->addColumn('contenido', 'text', [
        'null' => true,
        'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG
      ])
      ->addColumn('imagen', 'string', [
        'limit' => 255,
        'null' => true
      ])
      ->addColumn('fecha_publicacion', 'date', [
        'null' => true
      ])
      ->addColumn('visitas', 'integer', [
        'signed' => false,
        'default' => 0
      ])
      ->addColumn('autor', 'string', [
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
