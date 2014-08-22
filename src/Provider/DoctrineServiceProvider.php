<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Provider;

use Cilex\Application;
use Cilex\Provider as Cilex;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @see \Cilex\Provider\DoctrineServiceProvider
 */
class DoctrineServiceProvider extends Cilex\DoctrineServiceProvider
{
    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        parent::register($app);
        if (isset($app['config']['doctrine']['dbal']['connections'])) {
            $connections = $app['config']['doctrine']['dbal']['connections'];
            if (isset($app['config']['doctrine']['dbal']['default_connection'])) {
                $default = $app['config']['doctrine']['dbal']['default_connection'];
                if (isset($connections[$default])) {
                    $app['dbs.default'] = $default;
                }
            }
            $app['dbs.options'] = $connections;
        }
    }
}
