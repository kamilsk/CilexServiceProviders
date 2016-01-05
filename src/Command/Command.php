<?php

namespace OctoLab\Cilex\Command;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Cilex\Provider\Console\ContainerAwareApplication getApplication()
 * @see \Cilex\Command\Command
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
abstract class Command extends \Symfony\Component\Console\Command\Command
{
    /** @var string */
    protected $namespace;

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
     * @param string $path Is a key or path in a special format (e.g. "some:component:config") of configuration
     * @param mixed $default Default value if a key or path is cannot be resolved
     *
     * @return array|mixed
     *
     * @api
     */
    public function getConfig($path = null, $default = null)
    {
        if ($path === null) {
            $config = $this->getService('config');
            return $config ?: [];
        }
        $rawConfig = $this->getService('config.raw');
        if ($rawConfig) {
            $config = $rawConfig[$path];
        }
        return isset($config) ? $config : $default;
    }

    /**
     * @param null|string $alias
     *
     * @return \Doctrine\DBAL\Connection
     *
     * @throws \RuntimeException if doctrine service is not defined
     * @throws \InvalidArgumentException if the identifier (alias) is not defined
     *
     * @api
     */
    public function getDbConnection($alias = null)
    {
        $connection = null;
        if ($alias === null) {
            $connection = $this->getService('db');
        } else {
            $dbs = $this->getService('dbs');
            if ($dbs instanceof \Pimple) {
                $connection = $dbs->offsetGet($alias);
            }
        }
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
