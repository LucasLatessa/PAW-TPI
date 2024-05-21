<?php

namespace Paw\Core;

class Controlador
{
    public string $viewsDir; #Direccion a la vista indicada
    public function __construct(){

        
        $this->viewsDir = __DIR__ . "/../App/views/";

        $this->rutasMenuBurger = [
            
            [
                "href" => '../',
                "name" => "",
            ],
            [
                "href" => '../',
                "name" => "",
            ],
            [
                "href" => '../',
                "name" => ""
            ]
            ];
        $this->rutasLogoHeader =
            /*Logo header 3*/
            [
                "href" => '../',
                "name" => "Home",
            ];
        $this->rutasHeaderDer = [
            /*Header parte derecha 4-5*/
            [
                "href" => '../',
                "name" => "ingresar",
            ],
            [
                "href" => '../',
                "name" => ""
            ],
        ];
        $this->rutasFooter = [
            /*Footer 6-*/
            [
                "href" => '../',
                "name" => ""
            ],
            [
                "href" => '../',
                "name" => ""
            ],
            [
                "href" => '../',
                "name" => ""
            ],
            [
                "href" => '../',
                "name" => ""
            ]
            ];
    }
}