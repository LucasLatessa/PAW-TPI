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

    public function validarImagen(&$nombreArchivo) {
        // Verificar si se está subiendo un archivo
        if (!isset($_FILES['imagen']['error']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            return false; // No se subió correctamente o no se subió ningún archivo
        }
        
        // Obtener el tamaño del archivo en bytes
        $tamanioArchivo = $_FILES['imagen']['size'];
    
        // Establecer el tamaño máximo permitido (1 MB = 1048576 bytes)
        $tamanoMaximo = 1048576; // 1 MB en bytes
    
        // Validar el tamaño del archivo
        if ($tamanioArchivo > $tamanoMaximo) {
            return false; // El archivo supera el tamaño máximo permitido
        }
    
        // Obtener el nombre original del archivo
        $nombreArchivo = $_FILES['imagen']['name'];
    
        // Obtener el archivo temporal
        $archivoTemporal = $_FILES['imagen']['tmp_name'];
    
        // Definir la ruta de destino donde se guardará el archivo
        $rutaDestino = __DIR__ . '/../../../public/assets/equipos/' . $nombreArchivo;
    
        // Mover el archivo desde su ubicación temporal a la ruta de destino
        if (!move_uploaded_file($archivoTemporal, $rutaDestino)) {
            return false; // No se pudo mover el archivo
        }
    
        // Si pasó todas las validaciones y se movió correctamente, es un archivo válido
        return true;
    }
    
    

    public function cargarEquipo() {
        global $request; 
    
        $nombreArchivo = null;
    
        // Verificar si la imagen es válida y moverla a la ubicación deseada
        if ($this->validarImagen($nombreArchivo)) {
            // Si la imagen es válida y se ha guardado correctamente, proceder con el resto de los datos del formulario
            $nombreEquipo = $request->getRequest('equipo');
            $fechaCreacion = $request->getRequest('fecha');
            $nombreEstadio = $request->getRequest('estadio');
            $descripcion = $request->getRequest('descripcion');
            $imagen = $nombreArchivo; // Nombre de archivo obtenido de validarImagen
    
            $equipo = $this->model->create($nombreEquipo, $fechaCreacion, $nombreEstadio, $descripcion, $imagen);
    
            $title = "Equipo agregado - Liga";
            echo $this->twig->render('liga/equipoAgregado.view.twig', [
                'title' => $title,
                'equipo' => $equipo // Puedes pasar más datos a la vista si es necesario
            ]);
        } else {
            // Si la imagen no es válida (por tamaño excesivo, error al mover, etc.), mostrar mensaje de error
            $errorMessage = "La imagen a cargar excede el tamaño permitido - 1MB.";
            $title = "Cargar Equipo - Liga";
            echo $this->twig->render('liga/cargarEquipo.view.twig', [
                'title' => $title,
                'errorMessage' => $errorMessage
            ]);
        }
    }



    public function listaEquipos() {
        session_start();
        if (!isset($_SESSION['login'])) {
             $_SESSION['login'] = "";
        }

        $hayLogin = $_SESSION['login'];
        $title = 'Equipos - LigaCF';
        $listaEquipos = $this->model->getAllEquipos(); 
        echo $this->twig->render('competencia/listaEquipos.view.twig', [
            'title' => $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter,
            'listaEquipos' => $listaEquipos, // Pasar la lista de equipos a la vista
            'hayLogin' => $hayLogin
        ]);
    }
    
    
    public function torneos() {
        $title = 'Torneos - LigaCF';
        #$listaTorneos = $this->model->getAllTorneos(); 
    
        echo $this->twig->render('competencia/torneos.view.twig', [
            'title' => $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter,
            #'listaEquipos' => $listaEquipos // Pasar la lista de equipos a la vista
        ]);
    }

    public function crearTorneo() {
        global $request;
        $modelTorneo = TorneoCollections::class; #ver si esto esta bien de usar otro modelo para TorneoColelctiones 
        // Obtener los datos del formulario
        $nombreTorneo = $request->getRequest('nombre_torneo');
        $fechaInicio = $request->getRequest('fechaInicio');
        $fechaFin = $request->getRequest('fechaFin');
        $categoria = $request->getRequest('categoria');
        $cantidadEquipos = $request->getRequest('cantidad_equipos');
        $cantidadFechas = $request->getRequest('cantidad_fechas');
        $descripcion = $request->getRequest('descripcion');

        // Aca deberiamos validar los datos 

        // Crear instancia de Torneo y guardar en la base de datos
            // Lógica para guardar el torneo en la base de datos

            #falta probar esta logica de crear
      #  $torneo = $modelTorneo->create($nombreTorneo,$fechaInicio, $fechaFin,$categoria, $cantidadEquipos, $cantidadFechas, $descripcion);

        // Verificar si el torneo se guardó correctamente
        /*if ($torneo) {
            $title = "Torneo Creado - Liga";
            echo $this->twig->render('liga/torneoCreado.view.twig', [
                'title' => $title,
                #'torneo' => $torneo 
            ]);
        } else {
            // Manejar errores si no se pudo guardar el torneo en la base de datos
            $errorMessage = "No se pudo crear el torneo. Por favor, intenta nuevamente.";
            $title = "Crear Torneo - Liga";
            echo $this->twig->render('liga/crearTorneo.view.twig', [
                'title' => $title,
                'errorMessage' => $errorMessage
            ]);
        }*/

        $title = "Torneo Creado - Liga";
            echo $this->twig->render('liga/torneoCreado.view.twig', [
                'title' => $title,
            ]);           
    }
    

}