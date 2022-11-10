<?php

namespace Zenstruck\BackupBundle\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zenstruck\Backup\Console\Command\ListCommand as BaseListCommand;
use Zenstruck\Backup\Console\Helper\BackupHelper;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ListCommand extends BaseListCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Application $application */
        $application = $this->getApplication();

        if (!$application instanceof Application) {
            throw new \RuntimeException('Application must be instance of Symfony\Bundle\FrameworkBundle\Console\Application');
        }

        $container = $application->getKernel()->getContainer();

        $this->getHelperSet()->set(new BackupHelper(
            $container->get('zenstruck_backup.profile_registry'),
            $container->get('zenstruck_backup.executor')
        ));

        return parent::execute($input, $output);
    }
}
