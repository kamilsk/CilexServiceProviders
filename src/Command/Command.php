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
     * @param null|string $path is a key or path in a special format (e.g. "some:component:config") of configuration
     * @param mixed $default default value if a key or path is cannot be resolved
     *
     * @return \OctoLab\Common\Config\SimpleConfig|mixed
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
     * @throws \InvalidArgumentException if "connection"/"connections" or the identifier (alias) are not defined
     *
     * @api
     */
    public function getDbConnection($alias = null)
    {
        if ($alias === null) {
            return $this->getContainer()->offsetGet('connection');
        } else {
            return $this->getContainer()->offsetGet('connections')[$alias];
        }
    }

    /**
     * @param null|string $channel
     *
     * @return \Psr\Log\LoggerInterface
     *
     * @throws \InvalidArgumentException if "logger"/"loggers" are not defined
     * @throws \OutOfRangeException
     *
     * @api
     */
    public function getLogger($channel = null)
    {
        if ($channel === null) {
            return $this->getContainer()->offsetGet('logger');
        } else {
            return $this->getContainer()->offsetGet('loggers')[$channel];
        }
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
     * @param OutputInterface $output
     *
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function setUpMonologBridge(OutputInterface $output)
    {
        call_user_func($this->getContainer()->offsetGet('monolog.bridge'), $output);
    }
}
