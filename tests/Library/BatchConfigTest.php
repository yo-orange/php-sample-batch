<?php

namespace App\Library;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BatchConfigTest extends KernelTestCase
{
    public function test()
    {
        self::bootKernel();

        $target = self::$container->get(BatchConfig::class);

        static::assertEquals(
            ["hello_world" => "test1"],
            $target->getConfig()
        );
    }
}
