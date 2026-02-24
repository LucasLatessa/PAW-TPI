<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class EstadiosSeeder extends AbstractSeed
{
    public function run(): void
    {
        $estadios = [
            ['nombre' => 'Estadio Raul Orlando Lungarzo', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio José María Paz', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio X', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Alsina', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Ceramica', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Ciclon', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Colon', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Huracan', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Moquehua', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Once Tigres', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Pellegrini', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Alberti', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Varela', 'latitud' => -34.9639, 'longitud' => -60.0333],
            ['nombre' => 'Estadio Villarino', 'latitud' => -34.9639, 'longitud' => -60.0333],
        ];

        $this->table('estadios')->insert($estadios)->saveData();
    }
}