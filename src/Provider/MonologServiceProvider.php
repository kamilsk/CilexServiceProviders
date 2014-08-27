<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Provider;

use Cilex\Application;
use Cilex\Provider as Cilex;
use Monolog\Logger;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @see \Cilex\Provider\MonologServiceProvider
 */
class MonologServiceProvider extends Cilex\MonologServiceProvider
{
    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        parent::register($app);
    }
}
