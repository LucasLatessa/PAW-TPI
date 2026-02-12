<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class PartidosSeeder extends AbstractSeed
{
    public function run(): void
    {
        // Buscar primer torneo
        $torneo = $this->fetchRow('SELECT id FROM torneos LIMIT 1');

        if (!$torneo) {
            echo "⚠️ No hay torneo creado\n";
            return;
        }

        $partidos = [
            // FECHA 1
            [
                'fecha_numero' => 1,
                'local' => 'Independiente',
                'visitante' => 'Gimnasia',
                'fecha_partido' => '2026-03-10',
                'hora_partido' => '16:00',
                'goles_local' => 2,
                'goles_visitante' => 1,
                'estado' => 'finalizado',
            ],
            [
                'fecha_numero' => 1,
                'local' => 'Alsina',
                'visitante' => '22 de Octubre',
                'fecha_partido' => '2026-03-10',
                'hora_partido' => '18:00',
                'goles_local' => 0,
                'goles_visitante' => 0,
                'estado' => 'finalizado',
            ],

            // FECHA 2
            [
                'fecha_numero' => 2,
                'local' => 'Gimnasia',
                'visitante' => 'Alsina',
                'fecha_partido' => '2026-03-17',
                'hora_partido' => '16:00',
                'goles_local' => null,
                'goles_visitante' => null,
                'estado' => 'programado',
            ],
            [
                'fecha_numero' => 2,
                'local' => '22 de Octubre',
                'visitante' => 'Independiente',
                'fecha_partido' => '2026-03-17',
                'hora_partido' => '18:00',
                'goles_local' => null,
                'goles_visitante' => null,
                'estado' => 'programado',
            ],
        ];

        foreach ($partidos as $data) {

            // Buscar fecha
            $fecha = $this->fetchRow(
                'SELECT id FROM fechas WHERE torneo_id = ' . $torneo['id'] . ' AND numero ='  . $data['fecha_numero']
            );

            // Buscar equipos
            $pdo = $this->getAdapter()->getConnection();

            $stmt = $pdo->prepare(
                'SELECT id FROM equipos WHERE nombre = :nombre'
            );

            $stmt->execute([
                'nombre' => $data['local']
            ]);

            $equipoLocal = $stmt->fetch();

            $stmt->execute([
                'nombre' => $data['visitante']
            ]);

            $equipoVisitante = $stmt->fetch();

            // $equipoVisitante = $this->fetchRow(
            //     'SELECT id FROM equipos WHERE nombre_pila = ' . $data['visitante']
            // );

            if (!$fecha || !$equipoLocal || !$equipoVisitante) {
                continue;
            }

            // Insertar partido
            $this->table('partidos')->insert([
                'torneo_id' => $torneo['id'],
                'fecha_id' => $fecha['id'],
                'equipo_local_id' => $equipoLocal['id'],
                'equipo_visitante_id' => $equipoVisitante['id'],
                'fecha_partido' => $data['fecha_partido'],
                'hora_partido' => $data['hora_partido'],
                'goles_local' => $data['goles_local'],
                'goles_visitante' => $data['goles_visitante'],
                'estado' => $data['estado'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ])->save();
        }

        echo "Partidos creados correctamente\n";
    }
}