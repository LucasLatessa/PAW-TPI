<?php

namespace Paw\Core\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Weather
{
    private string $lat;
    private string $lon;
    private string $host = "https://pro.openweathermap.org/data/2.5/";
    private Client $client;

    public function __construct(string $lat, string $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
        $this->client = new Client([
            'verify' => false,
        ]);
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
            $response = $this->client->request('GET', $this->host . 'weather', [
                'query' => [
                    'lat' => $this->lat,
                    'lon' => $this->lon,
                    'appid' => getenv("WEATHER_API_KEY"),
                    'units' => 'metric',
                    'lang' => 'es'
                ]
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getForecastWeather(int $cnt): array
    {
        try {
            $response = $this->client->request('GET', $this->host . 'forecast/climate', [
                'query' => [
                    'lat' => $this->lat,
                    'lon' => $this->lon,
                    'appid' => getenv("WEATHER_API_KEY"),
                    'units' => 'metric',
                    'cnt' => $cnt,
                    'lang' => 'es'
                ]
            ]);

            return json_decode($response->getBody(), true);

        } catch (GuzzleException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}