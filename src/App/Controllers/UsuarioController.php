<?php

namespace Paw\App\Controllers;
use Paw\App\Models\Direccion;
use Paw\App\Models\UsuariosCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Paw\Core\Config;

class UsuarioController extends Controlador{ 
    public ?string $modelName = UsuariosCollections::class;
    
    public function signUp(){
        $title = 'Registrarse - LigaCF';
        echo $this->twig->render('cuenta/registrarse.view.twig', [
            'title' =>  $title,
        ]);
    }

    #Registro de usuarios
   public function registrarse()
    {
        global $request;

        // Si ya esta logueado, lo mandamos al perfil
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
        $nombre = $request->getRequest("nombre");
        $apellido = $request->getRequest("apellido");
        $email = $request->getRequest("email");
        $password = $request->getRequest("password"); 
        $passwordConfirm = $request->getRequest("password_confirm");
        $palabraclave = $request->getRequest("palabraClave");

        // Validamos contraseñas y palabra clave
        if (($password === $passwordConfirm) && (getenv('PALABRA_CLAVE') == $palabraclave)) {
            
            $contraHash = password_hash($password, PASSWORD_DEFAULT);
            $this->model->create($nombre, $apellido, $email, $contraHash);
            
            // Redirigimos al login
            header('Location: /login');
            exit();

        } else {
            // Error
            $errorMessage = "Las contraseñas no coinciden o la palabra clave es incorrecta"; 
            
            echo $this->twig->render('cuenta/registrarse.view.twig', [
                'title' => 'Registrarse - LigaCF',
                'errorMessage' => $errorMessage,
                
                // devolvemos lo que escribio
                'nombre_ingresado' => $nombre,
                'apellido_ingresado' => $apellido,
                'email_ingresado' => $email
            ]);
        }        
    }
    public function ingresar(){
        $title = 'Ingresar - LigaCF';
        if ($this->hayLogin) {
            header('Location: /cuenta/perfil');
            exit();
        }
        echo $this->twig->render('cuenta/login.view.twig', [
            'title' =>  $title,
        ]);
    }

    #Login
    public function login(){
        global $request;
        // Si ya esta logueado, al perfil
        if ($this->hayLogin) {
            header('Location: /cuenta/perfil');
            exit();
        }

        // GET mostramos form
        if ($request->method() !== 'POST') {
            echo $this->twig->render('cuenta/login.view.twig', [
                'title' => 'Iniciar Sesión - LigaCF'
            ]);
            return;
        }

        // POST procesamos
        $email = $request->getRequest("email");
        $password = $request->getRequest("password");
        
        $usuario = $this->model->get($email);
    
        if ($usuario && password_verify($password, $usuario->getContraseña())) {
            
            $_SESSION['login'] = true;
            $_SESSION['username'] = $usuario->getCorreo();
            $_SESSION['usuario_id'] = $usuario->getId(); 
            
            header('Location: /cuenta/perfil');
            exit();

        } else {
            // Login fallido
            $errorMessage = "Credenciales incorrectas";
            
            echo $this->twig->render('cuenta/login.view.twig', [
                'title' => 'Iniciar Sesión - LigaCF',
                'errorMessage' => $errorMessage,
                'usuario_no_encontrado' => true,
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
        if (!$this->hayLogin) {
            header('Location: /cuenta/login');
            exit();
        }

        $title = 'Mi Perfil - LigaCF';
        
        // Recuperamos datos del usuario
        $emailUsuario = $_SESSION['username'];
        $usuario_info = $this->model->get($emailUsuario);

        echo $this->twig->render('cuenta/perfil.view.twig', [
            'title' =>  $title,
            'usuario_info'=> $usuario_info,
        ]);
    }

    # Update Perfil
    public function updateperfil()
    {
        global $request;

        // Seguridad
        if (!$this->hayLogin) {
            header('Location: /cuenta/login');
            exit();
        }

        if ($request->method() == 'POST') {
            $id = $request->getRequest("id");
            $nombre = $request->getRequest("nombre");
            $apellido = $request->getRequest("apellido");
            $equipoFavorito = $request->getRequest("equipoFavorito");
            $correo = $_SESSION['username'];

            $data = [
                'id' => $id,
                'correo' => $correo,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'equipoFavorito' => $equipoFavorito,
            ];

            $this->model->updateUsuario($data);
        }

        header('Location: /cuenta/perfil');
        exit();
    }
}