<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class NoticiasSeeder extends AbstractSeed
{

  public function run(): void
  {
    $noticias = [
      [
        'titulo' => 'Arranca el Torneo Apertura 2026',
        'descripcion' => 'Este fin de semana comienza el Torneo Apertura con grandes expectativas.',
        'contenido' => 'El Torneo Apertura 2026 dará inicio este sábado con partidos en todas las categorías...',
        'imagen' => 'noticia.jpg',
        'fecha_publicacion' => '2026-02-01',
        'autor' => 'Liga Chivilcoyana',
      ],
      [
        'titulo' => 'Independiente ganó en el debut',
        'descripcion' => 'El Rojo arrancó el torneo con una sólida victoria como local.',
        'contenido' => 'Independiente mostró un gran nivel colectivo y se impuso 2 a 0...',
        'imagen' => 'noticia2.jpg',
        'fecha_publicacion' => '2026-02-03',
        'autor' => 'Redacción LCF',
      ],
      [
        'titulo' => 'Gimnasia presentó su nuevo cuerpo técnico',
        'descripcion' => 'El Lobo confirmó su nuevo DT de cara a la temporada.',
        'contenido' => 'En conferencia de prensa, Gimnasia presentó oficialmente a su nuevo entrenador...',
        'imagen' => 'noticia3.jpg',
        'fecha_publicacion' => '2026-01-27',
        'autor' => 'Prensa Gimnasia',
      ],
    ];

    foreach ($noticias as $noticia) {

      // Copiar imagen
      $rutaDestinoRelativa = null;

      if (!empty($noticia['imagen'])) {

        $rutaOrigen = __DIR__ . '/assets/' . $noticia['imagen'];
        $rutaDestinoRelativa = $noticia['imagen'];
        $rutaDestinoFisica = __DIR__ . '/../../public/' . $rutaDestinoRelativa;

        if (file_exists($rutaOrigen)) {
          if (!is_dir(dirname($rutaDestinoFisica))) {
            mkdir(dirname($rutaDestinoFisica), 0777, true);
          }
          copy($rutaOrigen, $rutaDestinoFisica);
        }
      }

      // Insertar noticia
      $this->table('noticias')->insert([
        'titulo' => $noticia['titulo'],
        'descripcion' => $noticia['descripcion'],
        'contenido' => $noticia['contenido'],
        'imagen' => $rutaDestinoRelativa,
        'fecha_publicacion' => $noticia['fecha_publicacion'],
        'autor' => $noticia['autor'],
        'visitas' => rand(0, 50)
      ])->save();
    }
  }

}
