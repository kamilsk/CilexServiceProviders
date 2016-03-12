<?php

namespace OctoLab\Cilex\Command;

use OctoLab\Common\Monolog\Util\LoggerLocator;

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
     * @param null|string $namespace
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
     * @param null|string $path Is a key or path in a special format (e.g. "some:component:config") of configuration
     * @param mixed $default Default value if a key or path is cannot be resolved
     *
     * @return array|mixed
     *
     * @api
     */
    public function getConfig($path = null, $default = null)
    {
        $config = $this->getService('config');
        if ($path === null) {
            return $config ?: [];
        } else {
            return $config[$path] ?: $default;
        }
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
     * @param null|string $channel
     *
     * @return \Psr\Log\LoggerInterface
     *
     * @throws \RuntimeException if monolog service is not defined or channel is not registered
     * @throws \OutOfRangeException
     *
     * @api
     */
    public function getLogger($channel = null)
    {
        /** @var LoggerLocator $loggers */
        $loggers = $this->getService('loggers');
        if ($loggers === null) {
            throw new \RuntimeException('MonologServiceProvider is not registered.');
        }
        if ($channel !== null && !isset($loggers[$channel])) {
            throw new \RuntimeException(sprintf('Logger with ID "%s" not found.', $channel));
        }
        return $channel === null ? $loggers->getDefaultChannel() : $loggers[$channel];
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
}
