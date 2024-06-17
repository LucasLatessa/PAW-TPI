<?php

namespace Paw\App\Controllers;

use Paw\App\Models\EquipoCollections;
use Paw\App\Models\Equipo;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class LigaController extends Controlador
{

    public ?string $modelName = EquipoCollections::class;
    public string $viewsDir; #Direccion a la vista indicada
    private $twig;
    
    #CONSTRUCTOR
    public function __construct()
    {
        parent::__construct();
        $loader = new FilesystemLoader(__DIR__ . '/../../App/Views');
        $this->twig = new Environment($loader);
    }

    private function validarImagen($tamañoArchivo, $nombreArchivo, $archivoTemporal) {
        $limiteTamaño = 1048576; // 1MB en bytes
        if ($tamañoArchivo <= $limiteTamaño) {
            $rutaDestino = __DIR__ . '/../../../public/assets/' . $nombreArchivo;
            move_uploaded_file($archivoTemporal, $rutaDestino);
            return true;
        } else {
            return false;
        }
    }

    public function cargarEquipo() {
        global $request;
        session_start();
        $nombreArchivo = $_FILES['imagen']['name'];
        $tamanioArchivo = $_FILES['imagen']['size'];
        $archivoTemporal = $_FILES['imagen']['tmp_name'];

        if (!isset($_SESSION['login'])) { #si no esta logueado
            $title = "Cargar Equipo - Liga";   
            $errorMessage = "Usuario no logeado"; #usuario no logueado

            echo $this->twig->render('liga/cargarEquipo.view.twig', [  #muestro misma vista con error de usuario no logueado
                'title' => $title,
                'errorMessage' => $errorMessage
            ]);
        } else { #si esta logueado
            if ($this->validarImagen($tamanioArchivo, $nombreArchivo, $archivoTemporal)) { #valido tamaño imagen 
                $nombreEquipo = $request->getRequest('equipo');  #guardo todos los datos del formulario
                $fechaCreacion = $request->getRequest('fecha');
                $nombreEstadio = $request->getRequest('estadio');
                $contenido = $request->getRequest('contenido');
                $imagen = $nombreArchivo;

                #$equipo = $this->model->create($nombreEquipo, $fechaCreacion, $nombreEstadio, $contenido, $imagen); #creo un equipo en el modelo

                $title = "Equipo agregado - Liga";
                echo $this->twig->render('liga/equipoAgregado.view.twig', [ #actualizo vista equipo agregado
                    'title' => $title,
                #    'equipo' => $equipo
                ]);
            } else {  #si el tamaño de la iamgen no es adecuado
                $errorMessage = "El archivo es muy pesado.";
                $title = "Cargar Equipo - Liga";
                echo $this->twig->render('liga/cargarEquipo.view.twig', [ #vuelvo a mostrar misma ventana de carga con error
                    'title' => $title,
                    'errorMessage' => $errorMessage
                ]);
            }
        }
    }
    


}