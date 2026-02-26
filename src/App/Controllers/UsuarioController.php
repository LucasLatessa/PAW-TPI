<?php
namespace Paw\App\Controllers;

use Paw\App\Models\EquipoCollections;
use Paw\App\Models\UsuariosCollections;
use Paw\Core\Controlador;
use Paw\App\Models\Usuario;

class UsuarioController extends Controlador
{
    public ?string $modelName = UsuariosCollections::class;

    public function formSignUp()
    {
        $title = 'Registrarse - LigaCF';
        echo $this->twig->render('cuenta/registrarse.view.twig', [
            'title' => $title,
        ]);
    }

    #Registro de usuarios
    public function signUp()
    {
        global $request;

        // si ya esta logueado, lo mandamos al perfil
        if ($this->hayLogin) {
            header('Location: /cuenta/perfil');
            exit();
        }

        // GET mostramos el formulario
        if ($request->method() !== 'POST') {
            echo $this->twig->render('cuenta/registrarse.view.twig', [
                'title' => 'Registrarse - LigaCF',
            ]);
            return;
        }

        // POST procesamos
        $nombre          = $request->getRequest("nombre");
        $apellido        = $request->getRequest("apellido");
        $email           = $request->getRequest("email");
        $password        = $request->getRequest("password");
        $passwordConfirm = $request->getRequest("password_confirm");
        $palabraclave    = $request->getRequest("palabraClave");

        $errorMessage = null;

        // validamos contraseñas y palabra clave
        if ($password !== $passwordConfirm) {
            $errorMessage = "Las contraseñas no coinciden.";
        } elseif (getenv('PALABRA_CLAVE') !== $palabraclave) {
            $errorMessage = "La palabra clave es incorrecta.";
        }
        // si hubo error cortamos
        if ($errorMessage) {
            echo $this->twig->render('cuenta/registrarse.view.twig', [
                'title'              => 'Registrarse - LigaCF',
                'errorMessage'       => $errorMessage,
                'nombre_ingresado'   => $nombre,
                'apellido_ingresado' => $apellido,
                'email_ingresado'    => $email,
            ]);
            return;
        }
        try {
            $contraHash = password_hash($password, PASSWORD_DEFAULT);

            $usuarioACrear = new Usuario();
            $usuarioACrear->set([
                'nombre'     => $nombre,
                'apellido'   => $apellido,
                'correo'     => $email,
                'contraseña' => $contraHash,
            ]);

            $this->model->create($usuarioACrear);

            header('Location: /login');
            exit();

        } catch (\Exception $e) {
            // Por si falla la DB
            echo $this->twig->render('cuenta/registrarse.view.twig', [
                'title'              => 'Registrarse - LigaCF',
                'errorMessage'       => "Hubo un error al crear la cuenta.",
                'nombre_ingresado'   => $nombre,
                'apellido_ingresado' => $apellido,
                'email_ingresado'    => $email,
            ]);
        }
    }
    public function formLogin()
    {
        $title = 'Ingresar - LigaCF';
        if ($this->hayLogin) {
            header('Location: /cuenta/perfil');
            exit();
        }
        echo $this->twig->render('cuenta/login.view.twig', [
            'title' => $title,
        ]);
    }

    #Login
    public function login()
    {
        global $request;
        // Si ya esta logueado, al perfil
        if ($this->hayLogin) {
            header('Location: /cuenta/perfil');
            exit();
        }

        // GET mostramos form
        if ($request->method() !== 'POST') {
            echo $this->twig->render('cuenta/login.view.twig', [
                'title' => 'Iniciar Sesión - LigaCF',
            ]);
            return;
        }

        // POST procesamos
        $email    = $request->getRequest("email");
        $password = $request->getRequest("password");

        $usuario = $this->model->get($email);
    // comparamos la contraseña con el hash de la DB
    if ($usuario && password_verify($password, $usuario->getContraseña())) {

            $_SESSION['login']      = true;
            $_SESSION['username']   = $usuario->getCorreo();
            $_SESSION['usuario_id'] = $usuario->getId();

            header('Location: /cuenta/perfil');
            exit();

        } else {
            // Login fallido
            $errorMessage = "Credenciales incorrectas";

            echo $this->twig->render('cuenta/login.view.twig', [
            'title'          => 'Iniciar Sesión - LigaCF',
            'errorMessage'   => 'El correo electrónico o la contraseña son incorrectos.',
            'email_ingresado' => $email,
        ]);
        }
    }
    public function logout()
    {
        // Vaciamos sesion
        $_SESSION = [];

        // Borramos cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        // Destruimos
        session_destroy();

        // Redirigimos al home
        header('Location: /');
        exit();
    }
    public function perfil()
    {
        // Validamos seguridad usando la propiedad del padre
        if (! $this->hayLogin) {
            header('Location: /cuenta/login');
            exit();
        }

        $title = 'Mi Perfil - LigaCF';

        // Recuperamos datos del usuario
        $emailUsuario = $_SESSION['username'];
        $usuario_info = $this->model->get($emailUsuario);

        $equipoModel = new EquipoCollections($this->getQb());
        $listaEquipos = $equipoModel->getAllEquipos();

        echo $this->twig->render('cuenta/perfil.view.twig', [
            'title'        => $title,
            'usuario_info' => $usuario_info,
            'equipos'      => $listaEquipos,
        ]);
    }

    # Update Perfil
    public function updateperfil()
    {
        global $request;

        // Seguridad
        if (! $this->hayLogin) {
            header('Location: /cuenta/login');
            exit();
        }

        if ($request->method() == 'POST') {
            $id             = $request->getRequest("id");
            $nombre         = $request->getRequest("nombre");
            $apellido       = $request->getRequest("apellido");
            $equipoFavorito = $request->getRequest("equipoFavorito") ?: null;
            $correo         = $_SESSION['username'];

            $data = [
                'id'                 => $id,
                'correo'             => $correo,
                'nombre'             => $nombre,
                'apellido'           => $apellido,
                'equipo_favorito_id' => $equipoFavorito,
            ];

            $this->model->updateUsuario($data);
        }

        header('Location: /cuenta/perfil');
        exit();
    }
}
