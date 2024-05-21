<?php

#Punto de entrada a mi aplicacion -> ubicado en controlador

require __DIR__ . '/../src/bootstrap.php';

$router->direct($request);