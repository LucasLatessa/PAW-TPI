<?php
namespace Paw\App\Models;

use Paw\App\Models\Usuario;
use Paw\Core\Model;

class UsuariosCollections extends Model
{
    public $table = 'usuarios';

    public function getAll()
    {

    }

    public function get($correo)
    {
        $usuarioData = $this->queryBuilder
            ->select($this->table, ['correo' => $correo])
            ->execute();
        if ($usuarioData) {
            // Creo instancia de Usuario
            $usuario = new Usuario();
            $usuario->set($usuarioData[0]); // Cargar datos en el modelo Usuario
            return $usuario;
        }
        return null;
    }

    public function create($nombre, $apellido, $correo, $contraseña)
    {
        $newUsuario = new Usuario;

        $data = [
            'nombre'     => $nombre,
            'apellido'   => $apellido,
            'correo'     => $correo,
            'contraseña' => $contraseña,
        ];

        $newUsuario->setQueryBuilder($this->queryBuilder);
        $newUsuario->set($data);

        $this->queryBuilder->insert($this->table, $data);
        return $newUsuario;
    }

    public function updateUsuario($params)
    {
        $id = $params['id'];
        unset($params['id']);

        $where = ['id' => $id];

        // Devolvemos el resultado del QueryBuilder
        return $this->queryBuilder->update($this->table, $params, $where);
    }

}
