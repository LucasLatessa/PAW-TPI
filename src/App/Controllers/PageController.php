<?php

namespace Paw\App\Controllers;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class PageController extends Controlador
{
    private $twig;
    public function __construct()
    {
        parent::__construct();
        $loader = new FilesystemLoader(__DIR__ . '/../../App/Views');
        $this->twig = new Environment($loader);
    }

    public function index()
    {
        $title = 'Home - LigaCF';
        echo $this->twig->render('index.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
        ]);
    }

    
    public function tabla()
    {
        $title = 'Tabla - LigaCF';
        echo $this->twig->render('competencia/tabla.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
        ]);
    }

    public function noticias(){
        $title = 'Noticias - LigaCF';
        echo $this->twig->render('institucional/noticias.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
        ]);
    }

    public function partidos(){
        $title = 'Partidos - LigaCF';
        echo $this->twig->render('competencia/partidos.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
        ]);
    }

    public function equipos(){
        $title = 'Equipos - LigaCF';
        echo $this->twig->render('competencia/equipos.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
        ]);
    }

    public function reglamento(){
        $title = 'Reglamento - LigaCF';
        echo $this->twig->render('competencia/reglamento.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
        ]);
    }

    public function ingresar(){
        $title = 'Ingresar - LigaCF';
        echo $this->twig->render('cuenta/login.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
        ]);
    }

    public function contacto(){
        $title = 'Contacto - LigaCF';
        echo $this->twig->render('institucional/contacto.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
        ]);
    }

    public function nosotros(){
        $title = 'Nosotros - LigaCF';
        echo $this->twig->render('institucional/nosotros.view.twig', [
            'title' =>  $title,
            'rutasLogoHeader' => $this->rutasLogoHeader, 
            'rutasHeaderDer' => $this->rutasHeaderDer, 
            'rutasFooter' => $this->rutasFooter, 
        ]);
    }
}