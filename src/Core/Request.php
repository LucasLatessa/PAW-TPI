<?php

namespace Paw\Core;

#Manejador toda la informacion de peticiones
class Request{
    public function uri(){
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); #URI
    }

    public function method(){
        return $_SERVER['REQUEST_METHOD']; #METODO
    }

    public function route(){
        return [
            $this->uri(),
            $this->method()
        ];
    }

    public function getRequest($valores){
        return $_POST[$valores] ?? $_GET[$valores] ?? null;
    }
}