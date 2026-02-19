<?php

declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CrearTablaEstadios extends AbstractMigration
{

    public function change(): void
    {
        $table = $this->table('estadios', ['id' => true, 'signed' => false]);

        $table
            ->addColumn('nombre', 'string', ['limit' => 100])
            ->addColumn('latitud', 'decimal', ['precision' => 10, 'scale' => 8, 'null' => true])
            ->addColumn('longitud', 'decimal', ['precision' => 11, 'scale' => 8, 'null' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
