<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UsuarioSeeder extends AbstractSeed
{
  public function run(): void
  {
      // Insertar
      $this->table('usuarios')->insert([
        'nombre' => 'admin',
        'apellido' => 'admin',
        'correo' => 'admin@admin.com',
        'contraseña' => password_hash('admin', PASSWORD_DEFAULT)
      ])->save();
  }
  
  
}