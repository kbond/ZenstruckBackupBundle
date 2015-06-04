<?php

namespace Zenstruck\BackupBundle\Source;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class MySqlDumpSource implements Source
{
    const DEFAULT_USER      = 'root';
    const DEFAULT_SSH_PORT  = 22;

    private $database;
    private $host;
    private $user;
    private $password;
    private $sshHost;
    private $sshUser;
    private $sshPort;

    /**
     * @param string      $database
     * @param string|null $host
     * @param string      $user
     * @param string|null $password
     * @param string|null $sshHost
     * @param string|null $sshUser
     * @param int         $sshPort
     */
    public function __construct($database, $host = null, $user = self::DEFAULT_USER, $password = null, $sshHost = null, $sshUser = null, $sshPort = self::DEFAULT_SSH_PORT)
    {
        $this->database = $database;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->sshHost = $sshHost;
        $this->sshUser = $sshUser;
        $this->sshPort = $sshPort;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($scratchDir, LoggerInterface $logger)
    {
        $logger->info(sprintf('Running mysqldump for: %s', $this->database));

        $processBuilder = new ProcessBuilder();

        if ($this->sshHost && $this->sshUser) {
            $processBuilder->add('ssh');
            $processBuilder->add(sprintf('%s@%s', $this->sshUser, $this->sshHost));
            $processBuilder->add(sprintf('-p %s', $this->sshPort));
        }

        $processBuilder->add('mysqldump');
        $processBuilder->add(sprintf('-u%s', $this->user));

        if ($this->host) {
            $processBuilder->add(sprintf('-h%s', $this->host));
        }

        if ($this->password) {
            $processBuilder->add(sprintf('-p%s', $this->password));
        }

        $processBuilder->add($this->database);

        $process = $processBuilder->getProcess();

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        file_put_contents(sprintf('%s/%s.sql', $scratchDir, $this->database), $process->getOutput());
    }
}
