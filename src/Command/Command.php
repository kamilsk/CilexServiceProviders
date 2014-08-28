<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Command;

use Cilex\Command as Cilex;

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
     * Дополняет название команды своим пространством имен.
     *
     * @param string $name
     *
     * @return \Symfony\Component\Console\Command\Command
     *
     * @throws \InvalidArgumentException
     */
    public function setName($name)
    {
        if ($this->namespace === null) {
            return parent::setName($name);
        }
        return parent::setName($this->namespace . ':' . $name);
    }
}
