<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Paw\Core\Database\QueryBuilder;

final class AltaNoticias extends AbstractMigration
{
    public function change(): void
    {
        $data = [
            [
                'titulo' => 'Independiente Campeón',
                'descripcion' => 'Final del Torneo Apertura de 1ra. División 2024.',
                'fecha' => '2024-12-20',
                'imagen' => './independiente_campeon.jpg',
            ],
            [
                'titulo' => 'Remodelación 2023',
                'descripcion' => 'Se comenzó a realizar los arreglos en el frente de la Liga Chivilcoyana de Fútbol.',
                'fecha' => '2023-11-15',
                'imagen' => './remodelacion.jpg',
            ],
            [
                'titulo' => 'Copa oro femenino',
                'descripcion' => 'Cerámica se impuso en la final de la copa oro femenino.',
                'fecha' => '2024-01-10',
                'imagen' => './copa_oro_femenino.jpg',
            ],
        ];

        $tablaNoticias = 'noticia'; 
        $queryBuilder = new QueryBuilder($this->getAdapter()->getConnection());

        foreach ($data as $noticia) {
            $queryBuilder->insert($tablaNoticias, $noticia);
        }
    }
}
