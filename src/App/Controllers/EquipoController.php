<?php

namespace Paw\App\Controllers;

use Paw\App\Models\Equipo;
use Paw\App\Models\EquipoCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class EquipoController extends Controlador{

    public ?string $modelName = EquipoCollections::class;

    public function datosEquipo(){
        global $request;
        $title = 'Equipos - LigaCF';
        $equipo_id = $request->get('id');
        $equipo = $this->model->getXid($equipo_id);
        $partidosEquipo= $this->model->getPartidosEquipo($equipo_id);
        echo $this->twig->render('competencia/equipo.view.twig', [
            'title' =>  $title,
            'equipo' => $equipo,
            'partidosEquipo' => $partidosEquipo,
        ]);

    }



}