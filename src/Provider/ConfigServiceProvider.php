<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Provider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @see \Cilex\Provider\ConfigServiceProvider
 * @see \Igorw\Silex\ConfigServiceProvider
 * @todo roadmap:
 * @todo - imports
 * @todo - parameters
 * @todo - placeholders
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /** @var string */
    protected $filename;
    /** @var array */
    protected $placeholders;
    /** @var array */
    protected $imports = [];
    /** @var array */
    protected $parameters = [];

    /**
     * @param string $filename
     * @param array $placeholders
     */
    public function __construct($filename, array $placeholders = [])
    {
        $this->filename = $filename;
        $this->placeholders = $placeholders;
    }

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
    }
}
