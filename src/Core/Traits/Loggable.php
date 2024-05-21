<?php

namespace Paw\Core\Traits;

use Monolog\Logger;

trait Loggable
{
    public $logger;

    public function setLoggeable(Logger $logger){
        $this->logger = $logger;
    }
}