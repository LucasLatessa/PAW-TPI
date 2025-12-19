<?php

namespace Paw\App\Controllers;

use Paw\App\Models\Noticia;
use Paw\App\Models\NoticiaCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class NoticiaController extends Controlador {

    public ?string $modelName = NoticiaCollections::class;

    public string $viewsDir; // Dirección a la vista indicada

    // CONSTRUCTOR
    public function __construct() {
        parent::__construct();
        $loader = new FilesystemLoader(__DIR__ . '/../../App/Views');
    }

    public function noticias() {
        global $request;
        $title = 'Noticias - LigaCF';
    
        // Cargar todas las noticias usando el modelo
        $noticias = $this->model->getAllNoticias();
    /* 
        // Verifica si se han obtenido noticias
        if (empty($noticias)) {
            echo 'No hay noticias disponibles';
            return;
        }
     */
        // Renderiza la vista y pasa las noticias
        echo $this->twig->render('institucional/noticias.view.twig', [
            'title' => $title,
            'noticias' => $noticias
        ]);
    }
    public function formCrearNoticia() {
        global $request;
        $title = 'Crear noticia - LigaCF';
        echo $this->twig->render('institucional/crearNoticia.view.twig', [
            'title' => $title,
        ]);
    }
    public function crearNoticia() {
    global $request; 

    $titulo = $request->getRequest('titulo');
    $descripcion = $request->getRequest('descripcion');
    $fecha = $request->getRequest('fecha');

    // Intentamos subir la imagen
    $nombreImagen = $this->subirImagen($_FILES, 'noticias');

    //  si nombreImagen NO es false, es porque subio bien
    if ($nombreImagen !== false) {
        $this->model->create($titulo, $descripcion, $fecha, $nombreImagen);

        header('Location: /noticias');
        exit();

    } else {
        $errorMessage = "La imagen excede el tamaño permitido (1MB) o hubo un error en la carga.";
        $title = 'Crear noticia - LigaCF';

        echo $this->twig->render('institucional/crearNoticia.view.twig', [
            'title' => $title,
            'errorMessage' => $errorMessage,
            'titulo_ingresado' => $titulo,
            'descripcion_ingresada' => $descripcion,
            'fecha_ingresada' => $fecha
        ]);
    }
}
    // Metodo para mostrar los detalles de una noticia
    public function detalleNoticia() {
        global $request;
        $title = 'Detalle de la Noticia - LigaCF';
        $noticia_id = $request->get('id');  // Suponiendo que pasamos el ID de la noticia en la URL

        // Obtener la noticia por ID
        $noticia = $this->model->getById($noticia_id);

        // Si no se encuentra la noticia, podemos redirigir o mostrar un error
        if (!$noticia) {
            // Redirigir a la lista de noticias o mostrar un mensaje de error
            echo $this->twig->render('noticias/error.view.twig', [
                'title' => 'Error',
                'mensaje' => 'Noticia no encontrada',
            ]);
            return;
        }

        echo $this->twig->render('noticias.view.twig', [
            'title' =>  $title,
            'noticia' => $noticia, 
        ]);
    }
}
