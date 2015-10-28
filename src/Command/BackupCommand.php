<?php

namespace Zenstruck\BackupBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zenstruck\BackupBundle\BackupRegistry;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class BackupCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('zenstruck:backup')
            ->setDescription('Run a backup')
            ->addArgument('profile', InputArgument::OPTIONAL, 'The backup profile to run (leave blank for listing)')
            ->addOption('clear', null, InputOption::VALUE_NONE, 'Set this flag to clear scratch directory before backup')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var BackupRegistry $registry */
        $registry = $this->getContainer()->get('zenstruck_backup.registry');

        if (!$profile = $input->getArgument('profile')) {
            $this->listProfiles($output, $registry);

            return;
        }

        $manager = $registry->get($profile);

        $manager->backup($input->getOption('clear'));
    }

    private function listProfiles(OutputInterface $output, BackupRegistry $registry)
    {
        $output->writeln('<info>Available Profiles:</info>');

        foreach ($registry->all() as $name => $profile) {
            $output->writeln(' - '.$name);
        }
    }
}
