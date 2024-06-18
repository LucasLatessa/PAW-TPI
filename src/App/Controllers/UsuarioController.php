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
    public string $viewsDir; #Direccion a la vista indicada
    private $twig;

    public function __construct()
    {
        parent::__construct();
        $loader = new FilesystemLoader(__DIR__ . '/../../App/Views');
        $this->twig = new Environment($loader);
    }

    #Registro de usuarios
    public function registrarse(){
        global $request;

         #Obtengo los datos de la peticion
        $nombre = $request->getRequest("nombre");
        $apellido = $request->getRequest("apellido");
        $email = $request->getRequest("email");
        $contraseña = $request->getRequest("contraseña");
        $validarcontraseña = $request->getRequest("validarContraseña");
        $palabraclave = $request->getRequest("palabraClave");

        if(($contraseña == $validarcontraseña) && (getenv('PALABRA_CLAVE') == $palabraclave)){
            $contraHash = password_hash($request->getRequest("contraseña"),PASSWORD_DEFAULT);
            $usuario = $this->model->create($nombre,$apellido, $email, $contraHash);
            $resultado = "¡Cuenta creada!";
            header('Location: /');
            exit();
        }
        else{
            $errorMessage = "Las contranseñas no coinciden"; 
            $title = 'Crear Torneo - LigaCF';
            echo $this->twig->render('cuenta/registrarse.view.twig', [
                'title' => $title,
                'errorMessage' => $errorMessage, 
                'rutasLogoHeader' => $this->rutasLogoHeader, 
                'rutasHeaderDer' => $this->rutasHeaderDer, 
                'rutasFooter' => $this->rutasFooter, 
            ]);
        }
        
    }

    #Login
    public function login(){
        global $request;

        #Obtengo los datos de la peticion
        $email = $request->getRequest("email");
        $contraseña = $request->getRequest("contraseña");

        #Obtengo los datos de la BD par aver si existe
        $usuario = $this->model->get($email);

        #Compruebo que exista en el sistema
        if ($usuario && password_verify($contraseña,$usuario->getContraseña())){
            // Iniciar sesión
            session_start();
            $_SESSION['login'] = true;
            $_SESSION['username'] = $usuario->getCorreo();
            $_SESSION['usuario_id'] = $usuario->getId(); 
            
            // Redirigir al perfil del usuario
            header('Location: /cuenta/perfil');
            //print_r ("holaa", $_SESSION['username']); 
            exit();
        } else {
            $errorMessage = "Credenciales incorrectas";
            $title = 'Crear Torneo - LigaCF';
            $usuario_no_encontrado = true;
            echo $this->twig->render('cuenta/login.view.twig', [
                'title' => $title,
                'errorMessage' => $errorMessage,
                'usuario_no_encontrado' => $usuario_no_encontrado,
                'rutasLogoHeader' => $this->rutasLogoHeader, 
                'rutasHeaderDer' => $this->rutasHeaderDer, 
                'rutasFooter' => $this->rutasFooter,
            ]);
        }


    }
    public function logout(){        
        global $request;
        session_start();
        if (isset($_SESSION['login'])){
            #Vacio el array de sesion
            $_SESSION = [];

            #Obtener los parametros de la cookie de sesion
            $params = session_get_cookie_params();

            // Establecer la cookie de sesión con una fecha de expiración en el pasado
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );

            #Destruir la sesion
            session_destroy();
            
            $title = 'Crear Torneo - LigaCF';
            $errorMessage = "Deslogueado";
            header('Location: /');
            exit();
            // echo $this->twig->render('cuenta/login.view.twig', [
            //     'title' => $title,
            //     'errorMessage' => $errorMessage
            // ]);
        }
        else{

            $title = 'Crear Torneo - LigaCF';
            $errorMessage = "Error al desloguearte!";
            echo $this->twig->render('/login.view.twig', [
                'title' => $title,
                'errorMessage' => $errorMessage,
                'rutasLogoHeader' => $this->rutasLogoHeader, 
                'rutasHeaderDer' => $this->rutasHeaderDer, 
                'rutasFooter' => $this->rutasFooter,
            ]);
        }
    }
    public function perfil($algo = ""){
        if (!$algo == "1"){
            session_start();
        }

        $title = 'Ingresar - LigaCF';
        if (!isset($_SESSION['login'])) {
             $_SESSION['login'] = "";
        }

        $hayLogin = $_SESSION['login'];

        if ($hayLogin) {
            $usuario = $_SESSION['username'];
            $usuario_info = $this->model->get($usuario);
            //var_dump($usuario_info);
        }

        echo $this->twig->render('cuenta/perfil.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
            'usuario_info'=> $usuario_info,
        ]);
    }

    public function updateperfil(){
        global $request;
        session_start();
        $nombre = $request->getRequest("nombre");
        $apellido = $request->getRequest("apellido");
        $equipoFavorito = $request->getRequest("equipoFavorito");
        

        $correo = $_SESSION['username'];

        $data = [
            'correo' => $correo,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'equipoFavorito' => $equipoFavorito,
        ];
        $this->model->updateUsuario($data);
        $algo = "2";
        $this->perfil($algo);

    }
}