<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Provider;

use Cilex\Application;
use Cilex\Provider as Cilex;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @see \Cilex\Provider\DoctrineServiceProvider
 */
class DoctrineServiceProvider extends Cilex\DoctrineServiceProvider
{
    /** @var bool|string */
    private $helperConnection;

    /**
     * @param bool|string $helperConnection для установки
     * <code>\Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper</code>, например, для
     * <code>\Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand</code>
     */
    public function __construct($helperConnection = false)
    {
        $this->helperConnection = $helperConnection;
    }

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
        if ($this->helperConnection) {
            $dbs = $app->offsetGet('dbs');
            if (is_bool($this->helperConnection)) {
                $connection = $app->offsetGet('db');
            } else {
                $connection = $dbs[$this->helperConnection];
            }
            $app
                ->offsetGet('console')
                ->getHelperSet()
                ->set(new ConnectionHelper($connection), 'connection')
            ;
        }
    }
}
