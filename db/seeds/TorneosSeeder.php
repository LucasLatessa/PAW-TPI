<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class TorneosSeeder extends AbstractSeed
{
  public function run(): void
  {
    $tablaTorneos = 'torneos';
    $tablaPivote = 'equipo_torneo';

    $torneos = [
      [
        'nombre' => 'Primera División',
        'categoria' => 'Primera',
        'temporada' => '2025',
        'descripcion' => 'Torneo oficial de la Liga Chivilcoyana de Fútbol',
        'estado' => 'activo',
        'fecha_inicio' => '2025-02-01',
      ],
      [
        'nombre' => 'Segunda División',
        'categoria' => 'Segunda',
        'temporada' => '2025',
        'descripcion' => 'Torneo oficial de la Liga Chivilcoyana de Fútbol',
        'estado' => 'activo',
        'fecha_inicio' => '2025-02-02',
      ],
    ];

    $torneoIds = [];

    foreach ($torneos as $data) {
      $this->table($tablaTorneos)->insert($data)->save();
      $torneoIds[] = (int) $this->getAdapter()->getConnection()->lastInsertId();
    }

    /**
     * EQUIPOS
     */
    $equipos = $this->fetchAll('SELECT id FROM equipos');

    /**
     * RELACIÓN TORNEO ↔ EQUIPO
     */
    foreach ($equipos as $equipo) {

      // PRIMERA DIVISIÓN
      $this->insertarSiNoExiste($tablaPivote, [
        'torneo_id' => $torneoIds[0],
        'equipo_id' => $equipo['id'],
        'partidos_jugados' => 0,
        'ganados' => 0,
        'empatados' => 0,
        'perdidos' => 0,
        'goles_favor' => 0,
        'goles_contra' => 0,
        'diferencia_goles' => 0,
        'puntos' => 0,
      ]);

      // SEGUNDA DIVISIÓN
      $this->insertarSiNoExiste($tablaPivote, [
        'torneo_id' => $torneoIds[1],
        'equipo_id' => $equipo['id'],
        'partidos_jugados' => 0,
        'ganados' => 0,
        'empatados' => 0,
        'perdidos' => 0,
        'goles_favor' => 0,
        'goles_contra' => 0,
        'diferencia_goles' => 0,
        'puntos' => 0,
      ]);
    }
  }

  private function insertarSiNoExiste(string $tabla, array $data): void
  {
    $existe = $this->fetchRow(
      "SELECT id FROM {$tabla}
             WHERE torneo_id = {$data['torneo_id']}
             AND equipo_id = {$data['equipo_id']}"
    );

    if (!$existe) {
      $this->table($tabla)->insert($data)->save();
    }
  }
}
