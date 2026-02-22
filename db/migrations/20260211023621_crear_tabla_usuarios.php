<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CrearTablaUsuarios extends AbstractMigration
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
    $usuario = $this->table('usuarios');
    $usuario
        ->addColumn('nombre', 'string', ['limit' => 60])
        ->addColumn('apellido', 'string', ['limit' => 60])
        ->addColumn('correo', 'string', ['limit' => 60])
        ->addColumn('contraseña', 'string')
        
        ->addColumn('equipo_favorito_id', 'integer', ['null' => true, 'signed' => false])
        
        ->addForeignKey('equipo_favorito_id', 'equipos', 'id', [
            'delete'=> 'SET_NULL', // si borran el equipo, el usuario queda en null
            'update'=> 'CASCADE'
        ])
        ->create();
}
}
