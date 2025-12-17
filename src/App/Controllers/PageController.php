<?php

namespace Paw\App\Controllers;
use Paw\App\Models\EquipoTorneoCollections;
use Paw\App\Models\NoticiaCollections;
use Paw\App\Models\TorneoCollections;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class PageController extends Controlador
{

    public function index()
    {
        /*Lista de equipos en torneo para ver la tabla*/
        $modelEquipoTorneo = new EquipoTorneoCollections();
        $modelEquipoTorneo->setQueryBuilder($this->getQb());
        $tabla = $modelEquipoTorneo->getUltimaTabla(); //Traigo el primero, que es el de primera
        
        $modelNoticias = new NoticiaCollections();
        $modelNoticias->setQueryBuilder($this->getQb());

        $noticias= $modelNoticias->getUltimasNoticias(4); // 4 ultimas noticias


        $title = 'Home - LigaCF';
        echo $this->twig->render('index.view.twig', [
            'title' =>  $title,
            'nombre_torneo' => $tabla['nombre_torneo'],
            'equipos' => $tabla['tabla'],
            'noticias' => $noticias
        ]);
    }


    public function noticias(){
        $title = 'Noticias - LigaCF';
        echo $this->twig->render('institucional/noticias.view.twig', [
            'title' =>  $title,
        ]);
    }

    public function partidos(){
        $title = 'Partidos - LigaCF';
        echo $this->twig->render('competencia/partidos.view.twig', [
            'title' =>  $title,
        ]);
    }

    public function reglamento(){
        $title = 'Reglamento - LigaCF';
        echo $this->twig->render('competencia/reglamento.view.twig', [
            'title' =>  $title,
        ]);
    }

    public function ingresar(){
        $title = 'Ingresar - LigaCF';
        if ($this->hayLogin) {
            header('Location: /cuenta/perfil');
            exit();
        }
        echo $this->twig->render('cuenta/login.view.twig', [
            'title' =>  $title,
        ]);
    }
    
    public function registrarse(){
        $title = 'Registrarse - LigaCF';
        echo $this->twig->render('cuenta/registrarse.view.twig', [
            'title' =>  $title,
        ]);
    }

    public function contacto(){
        $title = 'Contacto - LigaCF';
        echo $this->twig->render('institucional/contacto.view.twig', [
            'title' =>  $title,
        ]);
    }

    public function nosotros(){
        $title = 'Nosotros - LigaCF';
        echo $this->twig->render('institucional/nosotros.view.twig', [
            'title' =>  $title,
        ]);
    }
   


    public function cargarEquipo(){
        $title = 'Cargar - LigaCF';
        echo $this->twig->render('liga/cargarEquipo.view.twig', [
            'title' =>  $title,
        ]);
    }

    
    public function crearTorneo(){
        $title = 'Crear Torneo - LigaCF';
        echo $this->twig->render('liga/crearTorneo.view.twig', [
            'title' =>  $title,
        ]);
    }

}