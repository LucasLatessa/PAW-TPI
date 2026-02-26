<?php
namespace Paw\App\Controllers;

use Paw\App\Models\NoticiaCollections;
use Paw\Core\Controlador;

class NoticiaController extends Controlador
{

    public ?string $modelName = NoticiaCollections::class;

    // Ver lista de noticias en la Liga
    public function noticias()
    {
        global $request;
        $hayLogin = $_SESSION['login'];
        $title    = 'Noticias - LigaCF';
        // Paginacion
        $paginaActual = $request->getRequest('p') ?: 1;
        $porPagina    = $request->get('per_page') ?? 2; // Cantidad de noticias por pagina

        $noticias      = $this->model->getNoticiasPaginadas($paginaActual, $porPagina);
        $totalNoticias = $this->model->getTotalNoticias();
        $totalPaginas  = ceil($totalNoticias / $porPagina);

        echo $this->twig->render('noticias/index.view.twig', [
            'title'        => $title,
            'noticias'     => $noticias,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas,
            'porPagina'    => $porPagina,
        ]);
    }

    // Muestra una noticia
    public function show()
    {
        global $request;

        $noticia_id = $request->get('id');
        $noticia    = $this->model->getID($noticia_id);
        $this->model->incrementarVisitas($noticia_id);

        $title = $noticia['titulo'] . ' - LigaCF';

        echo $this->twig->render('noticias/show.view.twig', [
            'title'   => $title,
            'noticia' => $noticia,
        ]);
    }

    // Mostrar formulario para crear una noticia
    public function formCrearnoticia()
    {
        global $request;

        $title = 'Crear noticia - LigaCF';

        echo $this->twig->render('noticias/create.view.twig', [
            'title' => $title,
        ]);
    }

    // Crear noticia
    public function crearNoticia()
    {
        global $request;

        $titulo      = $request->getRequest('titulo');
        $descripcion = $request->getRequest('descripcion');
        $fecha       = $request->getRequest('fecha');
        $autor       = $request->getRequest('autor');
        $contenido   = $request->getRequest('contenido');

        $errorMessage = null;

        /* si el metodo es POST pero el titulo esta vacio, es porque PHP descarto el POST por exceso de tamaño*/
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($titulo) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
            $errorMessage = "La imagen excede el tamaño máximo permitido.";
        }
        // validamos si el campo imagen existe y si tiene un error
        elseif (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE) {
        $errorMessage = "Tenés que subir una imagen de portada para la noticia.";
        }
        else {
            // si hay archivo, intentamos subirlo(aca valida el tamaño de 1MB)
            $nombreImagen = $this->subirImagen($_FILES, 'noticias');

            if ($nombreImagen !== false) {
                $noticia= $this->model->create($titulo, $descripcion, $contenido, $autor, $fecha, $nombreImagen);
                $idRecienCreado = is_object($noticia) ? $noticia->getId() : $noticia;
                header('Location: /noticias/noticia?id=' . $idRecienCreado);
                exit();
            } else {
                $errorMessage = "La imagen es demasiado pesada (máx 1MB) o el formato no es válido.";
            }
        }

        // si llegamos aca es porque hubo un error
        $title = 'Crear noticia - LigaCF';
        echo $this->twig->render('noticias/create.view.twig', [
            'title'                 => $title,
            'errorMessage'          => $errorMessage,
            'titulo_ingresado'      => $titulo,
            'descripcion_ingresada' => $descripcion,
            'fecha_ingresada'       => $fecha,
            'contenido_ingresado'   => $contenido,
            'autor_ingresado'       => $autor,
        ]);
    }
}
