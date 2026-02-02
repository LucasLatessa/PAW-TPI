<?php

namespace Paw\App\Controllers;

use Paw\App\Models\Equipo;
use Paw\App\Models\EquipoCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class EquipoController extends Controlador
{

  public ?string $modelName = EquipoCollections::class;

  // Ver lista de equipos en la Liga
  public function equipos()
  {
    $hayLogin = $_SESSION['login'];
    $title = 'Equipos - LigaCF';
    $equipos = $this->model->getAllEquipos();

    //var_dump($equipos);
    echo $this->twig->render('equipos/index.view.twig', [
      'title' => $title,
      'equipos' => $equipos, // Pasar la lista de equipos a la vista
    ]);
  }

  // Muestra un equipo del torneo
  public function show()
  {
    global $request;

    $title = 'Equipo - LigaCF';
    $equipo_id = $request->get('id');
    $equipo = $this->model->getID($equipo_id);

    $title = $equipo['nombre_pila'] . ' - LigaCF';

    echo $this->twig->render('equipos/show.view.twig', [
      'title' =>  $title,
      'equipo' => $equipo
    ]);
  }
}
