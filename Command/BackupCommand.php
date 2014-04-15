<?php

namespace Zenstruck\BackupBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zenstruck\BackupBundle\BackupRegistry;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class BackupCommand extends Command
{
    private $registry;

    public function __construct(BackupRegistry $registry)
    {
        $this->registry = $registry;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('zenstruck:backup')
            ->setDescription('Run a backup')
            ->addArgument('profile', InputArgument::REQUIRED, 'The backup profile to run')
            ->addOption('clear', null, InputOption::VALUE_NONE, 'Set this flag to clear scratch directory before backup')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->registry->get($input->getArgument('profile'));

        $manager->backup($input->getOption('clear'));
    }
}
