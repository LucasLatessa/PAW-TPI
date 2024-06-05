<?php

namespace Paw\App\Controllers;
use Paw\Core\Controlador;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class ErrorController extends Controlador
{
    public string $viewsDir; #Direccion a la vista indicada
    private $twig;

    public function __construct()
    {
        parent::__construct();
        $loader = new FilesystemLoader(__DIR__ . '/../../App/views');
        $this->twig = new Environment($loader);
}

    public function notFound(){
        http_response_code(404);
        $title = "Pagina no encontrada";
        echo $this->twig->render('not-found.view.twig', [
            'title' => $title,
            ]);
    }

    public function internalError()
    {
        http_response_code(500);
        $title = "Internal error";
        echo $this->twig->render('internal-error.view.twig', [
            'title' => $title,
            ]);
    }
}