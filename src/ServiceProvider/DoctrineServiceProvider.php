<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use OctoLab\Common\Doctrine\Util\ConfigResolver;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class DoctrineServiceProvider implements ServiceProviderInterface
{
    /**
     * @quality:method [B]
     *
     * @param Application $app
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @api
     */
    public function register(Application $app)
    {
        if (!$app->offsetExists('config') || !isset($app->offsetGet('config')['doctrine'])) {
            return;
        }
        $config = $app->offsetGet('config');
        ConfigResolver::resolve($config['doctrine:dbal']);
        $app['connections'] = $app::share(function () use ($config) {
            $connections = new \Pimple();
            foreach ($config['doctrine:dbal:connections'] as $id => $params) {
                $connections->offsetSet(
                    $id,
                    DriverManager::getConnection($params, new Configuration(), new EventManager())
                );
            }
            return $connections;
        });
        $app['connection'] = $app::share(function (Application $app) use ($config) {
            $ids = $app['connections']->keys();
            $default = $config['doctrine:dbal:default_connection'] ?: current($ids);
            return $app['connections'][$default];
        });
        $app
            ->offsetGet('console')
            ->getHelperSet()
            ->set(new ConnectionHelper($app->offsetGet('connection')), 'connection')
        ;
    }
}
