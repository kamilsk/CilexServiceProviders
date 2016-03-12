<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\Provider as Cilex;
use OctoLab\Common\Monolog\LoggerLocator;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MonologServiceProvider extends Cilex\MonologServiceProvider
{
    /**
     * @param Application $app
     *
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @throws \OutOfRangeException
     *
     * @api
     */
    public function register(Application $app)
    {
        parent::register($app);
        if (empty($app['monolog.name'])) {
            $app['monolog.name'] = $app['console.name'];
        }
        if (!empty($app['config']['monolog'])) {
            $app['loggers'] = $app::share(function () use ($app) {
                return new LoggerLocator($app['config']['monolog'], $app['monolog.name']);
            });
            $app['monolog'] = $app::share(function () use ($app) {
                return $app['loggers']->getDefaultChannel();
            });
            $app['monolog.handlers'] = $app::share(function () use ($app) {
                return $app['loggers']->getHandlers();
            });
        }
        $app['logger'] = $app::share(function () use ($app) {
            return $app['monolog'];
        });
    }
}
