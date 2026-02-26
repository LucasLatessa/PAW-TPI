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

    public function create(Usuario $usuario, $qb)
    {
        $data = [
            'nombre'     => $usuario->getNombre(),
            'apellido'   => $usuario->getApellido(),
            'correo'     => $usuario->getCorreo(),
            'contraseña' => $usuario->getContraseña(),
        ];

        $qb->insert($this->table, $data);

        $id = $qb->getPdo()->lastInsertId();
        $usuario->set(['id' => $id]);

        return $usuario;
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
