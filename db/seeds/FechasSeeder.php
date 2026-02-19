<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class FechasSeeder extends AbstractSeed
{
    public function run(): void
    {

        $fechas = [
            [
                'numero' => 1,
                'nombre' => 'Fecha 1',
            ],
            [
                'numero' => 2,
                'nombre' => 'Fecha 2',
            ],
            [
                'numero' => 3,
                'nombre' => 'Fecha 3',
            ],
             [
                'numero' => 4,
                'nombre' => 'Fecha 4',
            ],
             [
                'numero' => 5,
                'nombre' => 'Fecha 5',
            ],
             [
                'numero' => 6,
                'nombre' => 'Fecha 6',
            ],
             [
                'numero' => 7,
                'nombre' => 'Fecha 7',
            ],
             [
                'numero' => 8,
                'nombre' => 'Fecha 8',
            ],
             [
                'numero' => 9,
                'nombre' => 'Fecha 9',
            ],
             [
                'numero' => 10,
                'nombre' => 'Fecha 10',
            ],
             [
                'numero' => 11,
                'nombre' => 'Fecha 11',
            ],
             [
                'numero' => 12,
                'nombre' => 'Fecha 12',
            ],
             [
                'numero' => 13,
                'nombre' => 'Fecha 13',
            ],
             [
                'numero' => 14,
                'nombre' => 'Fecha 14',
            ],
             [
                'numero' => 15,
                'nombre' => 'Fecha 15',
            ],
             [
                'numero' => 16,
                'nombre' => 'Fecha 16',
            ],
             [
                'numero' => 17,
                'nombre' => 'Fecha 17',
            ],
             [
                'numero' => 18,
                'nombre' => 'Fecha 18',
            ],
             [
                'numero' => 19,
                'nombre' => 'Fecha 19',
            ],
             [
                'numero' => 20,
                'nombre' => 'Fecha 20',
            ],
        ];

        foreach ($fechas as $data) {
            // Insertar fecha
            $this->table('fechas')->insert([
                'numero' => $data['numero'],
                'nombre' => $data['nombre'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ])->save();
        }
        echo "Fechas creadas correctamente\n";
    }
}
