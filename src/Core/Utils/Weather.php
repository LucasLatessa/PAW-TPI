<?php

namespace Paw\Core\Utils;
require __DIR__ . '/http.php';

class Weather
{
    private string $lat;
    private string $lon;
    private string $host = "https://pro.openweathermap.org/data/2.5/";

    public function __construct(string $lat, string $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }
    public function getAjaxWeather() {
        global $request;
        
        $lat = $request->get('lat');
        $lng = $request->get('lng');

        if (!$lat || !$lng) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'coordenadas faltantes']);
            return;
        }

        $weatherService = new Weather($lat, $lng);
        $data = $weatherService->getCurrentWeather();

        header('Content-Type: application/json');
        echo json_encode($data);
        exit; // cortamos aca para que no renderice todo el sitio
    }

    public function getCurrentWeather(): array
    {
        try {
            $body = http_get_body($this->host . 'weather', [
              'lat' => $this->lat,
              'lon' => $this->lon,
              'appid' => getenv("WEATHER_API_KEY"),
              'units' => 'metric',
              'lang' => 'es',
            ]);

            return json_decode($body, true);
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getForecastWeather(int $cnt): array
    {
        try {
            $body = http_get_body($this->host . 'forecast/climate', [
                'lat' => $this->lat,
                'lon' => $this->lon,
                'appid' => getenv("WEATHER_API_KEY"),
                'units' => 'metric',
                'cnt' => $cnt,
                'lang' => 'es'
            ]);

            return json_decode($body, true);
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}