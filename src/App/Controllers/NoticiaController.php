<?php
namespace Paw\App\Controllers;

use Paw\App\Models\NoticiaCollections;
use Paw\App\Models\Noticia;
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

        try{
            $noticia = $this->model->getID($noticia_id);
        } catch (\Exception $e) {
            http_response_code(404);
            $title = "Pagina no encontrada";
            echo $this->twig->render('not-found.view.twig', [
              'title' => $title,
            ]);
            return;
        };

        $this->model->incrementarVisitas($noticia_id);

        $title = $noticia['titulo'] . ' - LigaCF';

        echo $this->twig->render('noticias/show.view.twig', [
            'title'   => $title,
            'noticia' => $noticia,
        ]);
    }

    public function formNoticia()
    {
      global $request;

      $id = $request->get('id');
      $noticia = null;

      // Si hay ID, estoy en un update, sino en un create
      if ($id) {
          $noticia = $this->model->getID($id);
          $title = 'Editar noticia - LigaCF';
          $action = '/noticias/editar?id=' . $id;
      } else {
          $title = 'Crear noticia - LigaCF';
          $action = '/noticias/crear';
      }

      echo $this->twig->render('noticias/form.view.twig', [
          'title'   => $title,
          'noticia' => $noticia,
          'action'  => $action,
          'isEdit'  => $id ? true : false
      ]);
    }

    public function updateNoticia(){
        global $request;

        $id          = $request->get('id');
        $titulo      = $request->getRequest('titulo');
        $descripcion = $request->getRequest('descripcion');
        $fecha       = $request->getRequest('fecha');
        $autor       = $request->getRequest('autor');
        $contenido   = $request->getRequest('contenido');

        $errorMessage = null;

        // Traigo la imagen actual por defecto
        $nombreImagen = $this->model->getImagen($id); 

        /* si el metodo es POST pero el titulo esta vacio, es porque PHP descarto el POST por exceso de tamaño*/
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($titulo) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
            $errorMessage = "La imagen excede el tamaño máximo permitido.";
        }

        // Si el usuario subió una nueva imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {

            $nuevaImagen = $this->subirImagen($_FILES, 'noticias');

            if ($nuevaImagen !== false) {
                $nombreImagen = $nuevaImagen;

                // Borrar imagen vieja
                $imagen = $this->model->getImagen($id);
                if ($imagen) {
                    $rutaImagen = __DIR__ . '/../../../public/assets/noticias/' . $imagen;

                    if (file_exists($rutaImagen)) {
                        unlink($rutaImagen);
                    }
                }
            } else {
                $errorMessage = "La imagen excede el tamaño permitido (máx 1MB) o el formato no es válido.";
            }
        }

        // Creamos objeto con datos actualizados
        $noticiaActualizada = new Noticia();
        $noticiaActualizada->set([
            'id'                 => $id,
            'titulo'             => $titulo,
            'descripcion'        => $descripcion,
            'fecha_publicacion'  => $fecha,
            'autor'              => $autor,
            'contenido'          => $contenido,
            'imagen'             => $nombreImagen,
        ]);

        // Si no hubo error → actualizamos
        if (!$errorMessage) {

            $this->model->update($noticiaActualizada, $this->getQb());

            header('Location: /noticias/noticia?id=' . $id);
            exit();
        }

        // Error en la carga
        $title = 'Editar noticia - LigaCF';

        echo $this->twig->render('noticias/form.view.twig', [
            'title'        => $title,
            'errorMessage' => $errorMessage,
            'noticia'      => $noticiaActualizada,
            'isEdit'       => true,
            'action'       => '/noticias/editar?id=' . $id
        ]);

    }

    // Crear noticia (POST)
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

            $noticiaACrear = new Noticia();
            $noticiaACrear->set([
                'titulo'             => $titulo,
                'descripcion'        => $descripcion,
                'fecha_publicacion'  => $fecha,
                'autor'              => $autor,
                'contenido'          => $contenido,
                'imagen'             => $nombreImagen,
            ]);

            if ($nombreImagen !== false) {
                $noticia= $this->model->create($noticiaACrear, $this->getQb());
                header('Location: /noticias/noticia?id=' . $noticia->getId());
                exit();
            } else {
                $errorMessage = "La imagen excede el tamaño permitido (máx 1MB) o el formato no es válido.";
            }
        }

        // si llegamos aca es porque hubo un error
        $title = 'Crear noticia - LigaCF';
        echo $this->twig->render('noticias/create.view.twig', [
            'title'                 => $title,
            'errorMessage'          => $errorMessage,
            'noticia'               => $noticiaACrear,
            'isEdit'                => false,
            'action'                => '/noticias/crear'
        ]);
    }

    public function deleteNoticia()
    {
        global $request;

        $id = $request->get('id');

        if (!$id) {
          die('ID inválido');
        }

        // Borrar imagen del servidor si existe
        $imagen = $this->model->getImagen($id);
        if ($imagen) {
            $rutaImagen = __DIR__ . '/../../../public/assets/noticias/' . $imagen;

            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }

        // Borrar de la base de datos
        //$this->model->delete($id, $this->getQb());

        header('Location: /noticias');
        exit();
    }

}
