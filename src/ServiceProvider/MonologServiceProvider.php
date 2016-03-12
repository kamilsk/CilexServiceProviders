<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use OctoLab\Common\Monolog\LoggerLocator;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MonologServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Application $app
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfRangeException
     *
     * @api
     */
    public function register(Application $app)
    {
        $config = $app->offsetGet('config');
        $app['loggers'] = $app::share(function () use ($app, $config) {
            return new LoggerLocator($config['monolog'], $app['console.name']);
        });
        $app['monolog'] = $app['logger'] = $app::share(function () use ($app) {
            return $app['loggers']->getDefaultChannel();
        });
    }
}
