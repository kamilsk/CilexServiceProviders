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
     * @throws \InvalidArgumentException if doctrine service is not defined
     */
    public function getDbConnection()
    {
        return $this->getService('db');
    }

    /**
     * @return \Monolog\Logger
     * @throws \InvalidArgumentException if monolog service is not defined
     */
    public function getLogger()
    {
        return $this->getService('monolog');
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
     * Устанавливает интерфейс вывода для ConsoleHandler.
     *
     * @param OutputInterface $outputInterface
     *
     * @return $this
     *
     * @uses \Symfony\Bridge\Monolog\Handler\ConsoleHandler
     */
    public function setOutputInterface(OutputInterface $outputInterface)
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
}
