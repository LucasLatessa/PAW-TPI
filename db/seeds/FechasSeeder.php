<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class FechasSeeder extends AbstractSeed
{
    public function run(): void
    {
        // Buscar primer torneo
        $torneo = $this->fetchRow('SELECT id FROM torneos LIMIT 1');

        if (!$torneo) {
            echo "⚠️ No hay torneo creado\n";
            return;
        }

        $fechas = [
            [
                'numero' => 1,
                'nombre' => 'Fecha 1',
            ],
            [
                'numero' => 2,
                'nombre' => 'Fecha 2',
            ],
        ];

        foreach ($fechas as $data) {

            // Insertar fecha
            $this->table('fechas')->insert([
                'torneo_id' => $torneo['id'],
                'numero' => $data['numero'],
                'nombre' => $data['nombre'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ])->save();
        }

        echo "Fechas creadas correctamente\n";
    }
}
