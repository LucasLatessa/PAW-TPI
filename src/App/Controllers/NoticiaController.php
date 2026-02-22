<?php

namespace Paw\App\Controllers;

use Paw\App\Models\EquipoTorneoCollections;
use Paw\App\Models\NoticiaCollections;
use Paw\App\Models\TorneoCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class NoticiaController extends Controlador
{

  public ?string $modelName = NoticiaCollections::class;

  // Ver lista de noticias en la Liga
  public function noticias()
  {
    global $request;
    $hayLogin = $_SESSION['login'];
    $title = 'Noticias - LigaCF';
    // Paginacion
    $paginaActual = $request->getRequest('p') ?: 1;
    $porPagina    = $request->get('per_page') ?? 2; // Cantidad de noticias por pagina

    $noticias = $this->model->getNoticiasPaginadas($paginaActual, $porPagina);
    $totalNoticias = $this->model->getTotalNoticias();
    $totalPaginas = ceil($totalNoticias / $porPagina);

    echo $this->twig->render('noticias/index.view.twig', [
      'title' =>  $title,
      'noticias' => $noticias,
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
    $noticia = $this->model->getID($noticia_id);
    $this->model->incrementarVisitas($noticia_id);

    $title = $noticia['titulo'] . ' - LigaCF';

    echo $this->twig->render('noticias/show.view.twig', [
      'title' =>  $title,
      'noticia' => $noticia
    ]);
  }

  // Mostrar formulario para crear una noticia
  public function formCrearnoticia()
  {
    global $request;

    $title = 'Crear noticia - LigaCF';

    echo $this->twig->render('noticias/create.view.twig', [
      'title' =>  $title
    ]);
  }

  // Crear noticia
  public function crearNoticia()
  {
    global $request;

    $titulo = $request->getRequest('titulo');
    $descripcion = $request->getRequest('descripcion');
    $fecha = $request->getRequest('fecha');
    $autor = $request->getRequest('autor');
    $contenido = $request->getRequest('contenido');

    // Intentamos subir la imagen
    $nombreImagen = $this->subirImagen($_FILES, 'noticias');

    // Si nombreImagen NO es false, es porque subio bien
    if ($nombreImagen !== false) {
      $this->model->create($titulo, $descripcion,$contenido, $autor, $fecha, $nombreImagen);

      header('Location: /noticias');
      exit();
    } else {
      $errorMessage = "La imagen excede el tamaño permitido (1MB) o hubo un error en la carga.";
      $title = 'Crear noticia - LigaCF';

      echo $this->twig->render('noticias/create.view.twig', [
        'title' => $title,
        'errorMessage' => $errorMessage,
        'titulo_ingresado' => $titulo,
        'descripcion_ingresada' => $descripcion,
        'fecha_ingresada' => $fecha,
        'contenido_ingresado' => $contenido,
        'autor_ingresado' => $autor
      ]);
    }
  }
}
