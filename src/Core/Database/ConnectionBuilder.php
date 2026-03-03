<?php
namespace Paw\Core\Database;

use Paw\Core\Config;
use Paw\Core\Traits\Loggable;
use PDO;
use PDOException;

class ConnectionBuilder
{
    use Loggable;

    public function make(Config $config): PDO
    {
        $max_intentos = 3;
        $intento      = 0;

        while ($intento < $max_intentos) {
            try {
                $adapter  = $config->get('DB_ADAPTER');
                $hostname = $config->get('DB_HOSTNAME');
                $dbname   = $config->get('DB_DBNAME');
                $port     = $config->get('DB_PORT');
                $charset  = $config->get('DB_CHARSET');

                return new PDO(
                    "{$adapter}:host={$hostname};dbname={$dbname};port={$port};charset={$charset}",
                    $config->get('DB_USERNAME'),
                    $config->get('DB_PASSWORD'),
                    [
                        'options' => [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        ],
                    ]
                );
            } catch (PDOException $e) {
                $intento++;
                if ($intento >= $max_intentos) {
                    $this->logger->error('fallo definitivo tras 3 intentos', ["error" => $e]);
                    throw $e;
                }
                // esperamos 2 segundos antes de reintentar
                sleep(2);
            }
        }
    }
}
