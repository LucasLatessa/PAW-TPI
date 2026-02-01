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





  public function cargarEquipo()
  {
    global $request;

    $nombreEquipo = $request->getRequest('equipo');
    $fechaCreacion = $request->getRequest('fecha');
    $nombreEstadio = $request->getRequest('estadio');
    $descripcion = $request->getRequest('descripcion');

    $nombreArchivo = $this->subirImagen($_FILES, 'equipos');

    if ($nombreArchivo !== false) {

      $this->model->create($nombreEquipo, $fechaCreacion, $nombreEstadio, $descripcion, $nombreArchivo);

      header('Location: /listaEquipos');
      exit();
    } else {
      $errorMessage = "La imagen excede el tamaño permitido (1MB) o hubo un error en la carga.";
      $title = "Cargar Equipo - Liga";

      echo $this->twig->render('liga/cargarEquipo.view.twig', [
        'title' => $title,
        'errorMessage' => $errorMessage,

        'equipo_ingresado' => $nombreEquipo,
        'fecha_ingresada' => $fechaCreacion,
        'estadio_ingresado' => $nombreEstadio,
        'descripcion_ingresada' => $descripcion
      ]);
    }
  }



  public function listaEquipos()
  {
    $hayLogin = $_SESSION['login'];
    $title = 'Equipos - LigaCF';
    $equipos = $this->model->getAllEquipos();
    
    //var_dump($equipos);
    echo $this->twig->render('competencia/listaEquipos.view.twig', [
      'title' => $title,
      'equipos' => $equipos, // Pasar la lista de equipos a la vista
    ]);
  }


  public function torneos()
  {
    $title = 'Torneos - LigaCF';
    #$listaTorneos = $this->model->getAllTorneos(); 

    echo $this->twig->render('competencia/torneos.view.twig', [
      'title' => $title,
      #'listaEquipos' => $listaEquipos // Pasar la lista de equipos a la vista
    ]);
  }

  public function crearTorneo()
  {
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
