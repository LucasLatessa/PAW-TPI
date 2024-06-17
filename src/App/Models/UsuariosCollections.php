<?php

namespace Paw\App\Models;

use Paw\Core\Model;
use Paw\App\Models\Usuario;

class UsuariosCollections extends Model
{
    public $table = 'usuario';

    public function getAll()
    {

    
    }

    public function get($correo)
    {
        $usuarioData = $this->queryBuilder->selectViejo($this->table, ['correo' => $correo]);
        var_dump($usuarioData);
        if ($usuarioData) {
            // Creo instancia de Usuario
            $usuario = new Usuario();
            $usuario->set($usuarioData[0]); // Cargar datos en el modelo Usuario
            return $usuario;
        }
        var_dump("Nulo");
        return null;
    }

    public function create($nombre, $apellido, $correo, $contraseña)
    {
        $newUsuario = new Usuario;

        $data = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'correo' => $correo,
            'contraseña' => $contraseña,
        ];

        $newUsuario->setQueryBuilder($this->queryBuilder);
        $newUsuario->set($data);

        $this->queryBuilder->insert($this->table, $data);
        return $newUsuario;
    }

}