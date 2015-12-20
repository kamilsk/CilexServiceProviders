<?php

namespace OctoLab\Cilex\Command;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Cilex\Provider\Console\ContainerAwareApplication getApplication()
 * @see \Cilex\Command\Command
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class Command extends \Symfony\Component\Console\Command\Command
{
    /** @var string */
    private $namespace;

    /**
     * @param string $namespace
     *
     * @throws \LogicException
     *
     * @api
     */
    public function __construct($namespace = null)
    {
        $this->namespace = $namespace;
        parent::__construct();
    }

    /**
     * @return \Pimple
     *
     * @api
     */
    public function getContainer()
    {
        return $this->getApplication()->getContainer();
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     *
     * @api
     */
    public function getService($name)
    {
        return $this->getApplication()->getService($name);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     *
     * @throws \RuntimeException if doctrine service is not defined
     *
     * @api
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
     * @return \Psr\Log\LoggerInterface
     *
     * @throws \RuntimeException if monolog service is not defined
     *
     * @api
     */
    public function getLogger()
    {
        $logger = $this->getService('logger');
        if ($logger) {
            return $logger;
        }
        throw new \RuntimeException('MonologServiceProvider is not registered.');
    }

    /**
     * Completes the command name with its namespace.
     *
     * @param string $name
     *
     * @return \Symfony\Component\Console\Command\Command
     *
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function setName($name)
    {
        if (!$this->namespace) {
            return parent::setName($name);
        }
        return parent::setName(sprintf('%s:%s', $this->namespace, $name));
    }

    /**
     * Set OutputInterface for ConsoleHandler.
     *
     * @param OutputInterface $output
     * @param string $handler
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     *
     * @uses \Symfony\Bridge\Monolog\Handler\ConsoleHandler
     *
     * @api
     */
    public function initConsoleHandler(OutputInterface $output, $handler = 'console')
    {
        $this->getContainer()->offsetGet('monolog') && $output->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
        /** @var \Pimple $handlers */
        $handlers = $this
            ->getContainer()
            ->offsetGet('monolog.handlers')
        ;
        if ($handlers->offsetExists($handler)) {
            $handlers->offsetGet($handler)->setOutput($output);
        }
        return $this;
    }
}
