<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Command;

use App\Command\HelloWorldCommand;
use App\Repository\ProductRepository;
use App\Service\HelloWorldService;
use Monolog\Handler\TestHandler;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class HelloWorldCommandTest extends KernelTestCase
{

    protected function setUp(): void
    {
        exec('stty 2>&1', $output, $exitcode);
        $isSttySupported = 0 === $exitcode;

        if ('Windows' === PHP_OS_FAMILY || !$isSttySupported) {
            // $this->markTestSkipped('`stty` is required to test this command.');
        }
    }

    /**
     * @dataProvider argDataProvider
     *
     * This test provides all the arguments required by the command, so the
     * command runs non-interactively and it won't ask for any argument.
     */
    public function tests(string $arg): void
    {
        $input = [];
        $input["arg1"] = $arg;

        self::bootKernel();

        $service = $this->createMock(HelloWorldService::class);
        $service->method('helloWorld')->willReturn('goodbye.');

        $repo = self::$container->get(ProductRepository::class);
        $repo->findAll();

        $container = self::$container;
        $container->set(HelloWorldService::class, $service);

        $this->executeCommand($input);

        static::assertEquals(
            ["SELECT t0.id AS id_1, t0.name AS name_2, t0.col AS col_3 FROM product t0", "You passed an argument: $arg", "You have a new command! Now make it your own! Pass --help to see your options.", '"START TRANSACTION"'],
            $this->getTestRecords()
        );
    }

    public function argDataProvider(): ?\Generator
    {
        yield ["arg1"];
        yield ["arg2"];
    }


    /**
     * This helper method abstracts the boilerplate code needed to test the
     * execution of a command.
     *
     * @param array $arguments All the arguments passed when executing the command
     * @param array $inputs    The (optional) answers given to the command when it asks for the value of the missing arguments
     */
    private function executeCommand(array $arguments, array $inputs = []): void
    {
        // this uses a special testing container that allows you to fetch private services
        $command = self::$container->get(HelloWorldCommand::class);
        $command->setApplication(new Application(self::$kernel));

        $commandTester = new CommandTester($command);
        $commandTester->setInputs($inputs);
        $commandTester->execute($arguments);
    }

    public function getTestRecords()
    {
        $records = [];
        $container = self::$container;

        foreach ($container->get('logger')->getHandlers() as $handler) {
            if ($handler instanceof TestHandler) {
                $testHandler = $handler;
                break;
            }
        }

        if (!$testHandler) {
            throw new \RuntimeException('Oops, not exist "test" handler in monolog.');
        }

        // var_dump($testHandler->getRecords());
        foreach ($testHandler->getRecords() as $record) {
            $records[] = $record['message'];
        }
        return $records;
    }
}
