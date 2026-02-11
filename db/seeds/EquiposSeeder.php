<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class EquiposSeeder extends AbstractSeed
{
  public function run(): void
  {
    $equipos = [
      [
        'nombre' => 'Club Atletico Independiente',
        'escudo' => 'Independiente.png',
        'fecha_creacion' => '1930-04-05',
        'estadio' => 'Estadio Raul Orlando Lungarzo',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Social, Cultural y Deportivo Gimnasia y Esgrima',
        'escudo' => 'Gimnasia.png',
        'fecha_creacion' => '1916-04-18',
        'estadio' => 'Estadio José María Paz',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Social y Deportivo 22 de Octubre',
        'escudo' => '22Octubre.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio X',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Deportivo Alsina',
        'escudo' => 'Alsina.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Alsina',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Social y Deportivo Ceramica Argentina',
        'escudo' => 'Ceramica.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Ceramica',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Atletico Ciclon',
        'escudo' => 'Ciclon.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Ciclon',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Social y Deportivo Colon',
        'escudo' => 'Colon.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Colon',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Social y Deportivo Huracan',
        'escudo' => 'Huracan.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Huracan',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Moquehua',
        'escudo' => 'Moquehua.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Moquehua',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Once Tigres',
        'escudo' => 'OnceTigres.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Once Tigres',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Deportivo Pellegrini',
        'escudo' => 'Pellegrini.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Pellegrini',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club San Lorenzo Alberti',
        'escudo' => 'SanLorenzo.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Alberti',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Deportivo, Social y Cultural Florencio Varela',
        'escudo' => 'Varela.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Varela',
        'descripcion' => 'Descripcion',
      ],
      [
        'nombre' => 'Club Atletico Villarino',
        'escudo' => 'Villarino.png',
        'fecha_creacion' => '1900-01-01',
        'estadio' => 'Estadio Villarino',
        'descripcion' => 'Descripcion',
      ],
    ];

    foreach ($equipos as $equipo) {

      // Copiar escudo
      $rutaOrigen = __DIR__ . '/assets/escudos/' . $equipo['escudo'];
      $rutaDestinoRelativa = 'escudos/' . $equipo['escudo'];
      $rutaDestinoFisica = __DIR__ . '/../../public/' . $rutaDestinoRelativa;

      if (file_exists($rutaOrigen)) {
        if (!is_dir(dirname($rutaDestinoFisica))) {
          mkdir(dirname($rutaDestinoFisica), 0777, true);
        }
        copy($rutaOrigen, $rutaDestinoFisica);
      }

      // Insertar
      $this->table('equipos')->insert([
        'nombre' => $equipo['nombre'],
        'fecha_creacion' => $equipo['fecha_creacion'],
        'estadio' => $equipo['estadio'],
        'descripcion' => $equipo['descripcion'],
        'escudo' => $rutaDestinoRelativa,
        'activo' => 1,
      ])->save();
    }
  }

}
