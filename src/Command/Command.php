<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Command;

use Cilex\Command as Cilex;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class Command extends Cilex\Command
{
    /** @var string */
    private $namespace;

    /**
     * @param string $namespace
     */
    public function __construct($namespace = null)
    {
        $this->namespace = $namespace;
        parent::__construct();
    }

    /**
     * @return \Doctrine\DBAL\Connection
     *
     * @throws \RuntimeException if doctrine service is not defined
     */
    public function getDbConnection()
    {
        $connection = $this->getService('db');
        if ($connection) {
            return $connection;
        }
        throw new \RuntimeException('DoctrineServiceProvider is not registered.');
    }

    /**
     * @return \Monolog\Logger
     *
     * @throws \RuntimeException if monolog service is not defined
     */
    public function getLogger()
    {
        $logger = $this->getService('monolog');
        if ($logger) {
            return $logger;
        }
        throw new \RuntimeException('MonologServiceProvider is not registered.');
    }

    /**
     * Дополняет название команды своим пространством имен.
     *
     * @param string $name
     *
     * @return \Symfony\Component\Console\Command\Command
     *
     * @throws \InvalidArgumentException
     *  {@link \Symfony\Component\Console\Command\Command::setName}
     */
    public function setName($name)
    {
        if (null === $this->namespace) {
            return parent::setName($name);
        }
        return parent::setName(sprintf('%s:%s', $this->namespace, $name));
    }

    /**
     * Set OutputInterface for ConsoleHandler.
     *
     * @param OutputInterface $outputInterface
     *
     * @return $this
     *
     * @uses \Symfony\Bridge\Monolog\Handler\ConsoleHandler
     */
    public function initConsoleHandler(OutputInterface $outputInterface)
    {
        $outputInterface->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        $this
            ->getContainer()
            ->offsetGet('monolog.handlers')
            ->offsetGet('console')
            ->setOutput($outputInterface)
        ;
        return $this;
    }

    /**
     * @deprecated use {@link initConsoleHandler} instead.
     *
     * @param OutputInterface $outputInterface
     *
     * @return $this
     */
    public function setOutputInterface(OutputInterface $outputInterface)
    {
        return $this->initConsoleHandler($outputInterface);
    }
}
