<?php

namespace App\Command;

use App\Library\BatchConfig;
use App\Service\HelloWorldService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class HelloWorldCommand extends Command
{
    protected static $defaultName = 'HelloWorld';
    protected static $defaultDescription = 'Add a short description for your command';

    private $entityManager;
    private $config;
    private $service;
    private $logger;

    public function __construct(EntityManagerInterface $em, BatchConfig $config, HelloWorldService $service, LoggerInterface $logger)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->config = $config;
        $this->service = $service;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $this->logger->info(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $this->logger->info('You have a new command! Now make it your own! Pass --help to see your options.');

        $this->entityManager->getConnection()->beginTransaction();
        $io->note($this->service->helloWorld());

        return Command::SUCCESS;
    }
}
