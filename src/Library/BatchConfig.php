<?php

namespace App\Library;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BatchConfig
{
    private $batch_config;

    public function __construct(ContainerInterface $container) {
        $this->batch_config = $container->getParameter('batch_config');
    }

    public function getConfig() {
        return $this->batch_config;
    }

}
