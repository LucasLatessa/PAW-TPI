<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class EstadiosSeeder extends AbstractSeed
{
    public function run(): void
    {
        $estadios = [
            ['nombre' => 'Estadio Raul Orlando Lungarzo', 'latitud' =>null, 'longitud' => null],
            ['nombre' => 'Estadio José María Paz', 'latitud' =>null, 'longitud' => null],
            ['nombre' => 'Estadio X', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Alsina', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Ceramica', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Ciclon', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Colon', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Huracan', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Moquehua', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Once Tigres', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Pellegrini', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Alberti', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Varela', 'latitud' => null, 'longitud' => null],
            ['nombre' => 'Estadio Villarino', 'latitud' => null, 'longitud' => null],
        ];

        $this->table('estadios')->insert($estadios)->saveData();
    }
}