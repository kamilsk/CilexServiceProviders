<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\Provider as Cilex;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use OctoLab\Common\Doctrine\Util\ConfigResolver;

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
     * @param bool|string $helperConnection to setup
     * {@link \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper}, in particular for
     * {@link \Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand}
     *
     * @api
     */
    public function __construct($helperConnection = false)
    {
        $this->helperConnection = $helperConnection;
    }

    /**
     * @param Application $app
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \InvalidArgumentException
     *
     * @api
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
        if (isset($app['config']['doctrine']['dbal']['types'])) {
            ConfigResolver::resolve($app['config']['doctrine']['dbal']);
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
