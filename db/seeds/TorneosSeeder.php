<?php

declare (strict_types = 1);

use Phinx\Seed\AbstractSeed;

class TorneosSeeder extends AbstractSeed
{
    public function run(): void
    {
        $tablaTorneos  = 'torneos';
        $tablaPivote   = 'equipo_torneo';
        $tablaPartidos = 'partidos';

        $torneos = [
            [
                'nombre'       => 'Primera División',
                'categoria'    => 'Primera',
                'temporada'    => '2026',
                'descripcion'  => 'Torneo oficial de la Liga Chivilcoyana de Fútbol',
                'estado'       => 'activo',
                'fecha_inicio' => '2026-03-01',
            ],
        ];

        foreach ($torneos as $data) {
            $this->table($tablaTorneos)->insert($data)->save();
            $torneoId = (int) $this->getAdapter()->getConnection()->lastInsertId();

            $equipos    = $this->fetchAll('SELECT id FROM equipos');
            $equiposIds = array_column($equipos, 'id');

            foreach ($equiposIds as $id) {
                $this->insertarSiNoExiste($tablaPivote, [
                    'torneo_id' => $torneoId,
                    'equipo_id' => $id,
                    'puntos'    => 0,
                ]);
            }
            $this->generarPartidosSeeder($torneoId, $equiposIds, $tablaPartidos, $data['fecha_inicio']);
        }
    }

    private function generarPartidosSeeder(int $torneoId, array $equiposIds, string $tabla): void
    {
        $cantidad = count($equiposIds);

        // Si la cantidad es impar, agregamos un null para que alguien quede libre
        if ($cantidad % 2 != 0) {
            $equiposIds[] = null;
            $cantidad++;
        }

        $totalFechas      = $cantidad - 1;
        $partidosPorFecha = $cantidad / 2;
        $partidos         = [];

        for ($fecha = 0; $fecha < $totalFechas; $fecha++) {
            for ($partido = 0; $partido < $partidosPorFecha; $partido++) {
                $local     = $equiposIds[$partido];
                $visitante = $equiposIds[$cantidad - 1 - $partido];

                if ($local !== null && $visitante !== null) {
                    $partidos[] = [
                        'torneo_id'           => $torneoId,
                        'equipo_local_id'     => $local,
                        'equipo_visitante_id' => $visitante,
                        'estado'              => 'programado',
                        'fecha_id'            => $fecha + 1,
                        'fecha_partido'       =>'2026-03-01',
                        'hora_partido'        => '15:00:00',
                        'cancha'              => $local
                    ];
                }
            }

            // Rotación de equipos para la siguiente fecha (dejamos el primero fijo)
            $ultimoEquipo = array_pop($equiposIds);
            array_splice($equiposIds, 1, 0, [$ultimoEquipo]);
        }

        $this->table($tabla)->insert($partidos)->save();
    }

    private function insertarSiNoExiste(string $tabla, array $data): void
    {
        $existe = $this->fetchRow("SELECT id FROM {$tabla} WHERE torneo_id = {$data['torneo_id']} AND equipo_id = {$data['equipo_id']}");
        if (! $existe) {
            $this->table($tabla)->insert($data)->save();
        }
    }
}
