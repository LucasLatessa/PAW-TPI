<?php

namespace Paw\App\Controllers;

use Paw\Core\Utils\Weather; // importamos la utilidad

class WeatherController
{
    public function getAjaxWeather() {
        $lat = $_GET['lat'] ?? null;
        $lng = $_GET['lng'] ?? null;

        if (!$lat || !$lng) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'coordenadas faltantes']);
            return;
        }

        // llamamos a la clase que hace el laburo pesado
        $weatherService = new Weather($lat, $lng);
        $data = $weatherService->getCurrentWeather();

        header('Content-Type: application/json');
        echo json_encode($data);
        exit; 
    }
}