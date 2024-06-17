<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Paw\Core\Database\QueryBuilder;

final class AltaEquiposSemilla extends AbstractMigration
{
    public function change(): void
    {
        $data = [
            [
                'nombre' => 'Club atletico independiente de Chivilcoy',
                'fecha_creacion' => '1930-11-11 00:00:00',
                'estadio' => 'Raúl Orlando Lungarzo',
                'descripcion' => 'Fundado en el año 1930 en la ciudad de Chivilcoy',
                'escudo' => 'independiente3.jpg'
            ],
            [
                'nombre' => 'Club Atletico Ciclón de Chivilcoy',
                'fecha_creacion' => '1941-11-27 00:00:00',
                'estadio' => 'Jorge Ramón Leva',
                'descripcion' => 'Fundado en el año 1941 en la ciudad de Chivilcoy',
                'escudo' => 'ciclon.jpg'
            ],
            [
                'nombre' => 'Club Social y Deportivo Cerámica',
                'fecha_creacion' => '1926-07-14 00:00:00',
                'estadio' => 'Club Cerámica Argentina',
                'descripcion' => 'Fundado en el año 1926 en la ciudad de Chivilcoy',
                'escudo' => 'ceramica.jpg'
            ],
            [
                'nombre' => 'Club Atlético Huracán de Chivilcoy',
                'fecha_creacion' => '1944-11-24 00:00:00',
                'estadio' => 'Jorge Ramón Leva',
                'descripcion' => 'Fundado en el año 1944 en la ciudad de Chivilcoy',
                'escudo' => 'huracan.jpg'
            ],
            [
                'nombre' => 'Club Atlético Villarino',
                'fecha_creacion' => '1940-04-18 00:00:00',
                'estadio' => 'Club Atlético Villarino',
                'descripcion' => 'Fundado en el año 1940 en la ciudad de Chivilcoy',
                'escudo' => 'villarino.png'
            ],
            [
                'nombre' => 'Club Atlético Trocha',
                'fecha_creacion' => '1925-03-01 00:00:00',
                'estadio' => 'Club Atlético Trocha',
                'descripcion' => 'Fundado en el año 1925 en la ciudad de Mercedes',
                'escudo' => 'trocha.jpg'
            ],
            [
                'nombre' => 'Club Social y Deportivo 22 de Octubre',
                'fecha_creacion' => '1949-08-01 00:00:00',
                'estadio' => 'Club 22 de Octubre',
                'descripcion' => 'Fundado en el año 1949 en la ciudad de Chivilcoy',
                'escudo' => '22Octubre.jpg'
            ],
            [
                'nombre' => 'Club Deportivo Pellegrini',
                'fecha_creacion' => '1927-03-10 00:00:00',
                'estadio' => 'CDP',
                'descripcion' => 'Fundado en el año 1927 en la ciudad de Chivilcoy',
                'escudo' => 'pelegrini.jpg'
            ],
            [
                'nombre' => 'Club Social y Deportivo Once Tigres',
                'fecha_creacion' => '1958-10-10 00:00:00',
                'estadio' => 'CDOnce',
                'descripcion' => 'Fundado en el año 1958 en la ciudad de Chivilcoy',
                'escudo' => 'once_tigres.jpg'
            ],
            [
                'nombre' => 'Club Deportivo Alsina Chivilcoy',
                'fecha_creacion' => '1976-03-24 00:00:00',
                'estadio' => 'CDAC',
                'descripcion' => 'Fundado en el año 1976 en la ciudad de Chivilcoy',
                'escudo' => 'alsina.jpg'
            ]
        ];

        $tablaEquipos = 'equipo';

        $queryBuilder = new QueryBuilder($this->getAdapter()->getConnection());

        foreach ($data as $equipo) {
            $queryBuilder->insert($tablaEquipos, $equipo);
        }
    }
}

