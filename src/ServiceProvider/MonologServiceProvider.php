<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use OctoLab\Common\Monolog\LoggerLocator;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MonologServiceProvider implements ServiceProviderInterface
{
    /**
     * @quality:method [B]
     *
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
        if (!isset($config['monolog'])) {
            return;
        }
        $app['loggers'] = $app::share(function () use ($app, $config) {
            return new LoggerLocator($config['monolog'], $app['console.name']);
        });
        $app['logger'] = $app::share(function () use ($app) {
            return $app['loggers']->getDefaultChannel();
        });
        $app['monolog.bridge'] = $app::share(function () use ($app) {
            return function (OutputInterface $output) use ($app) {
                if (class_exists('Symfony\Bridge\Monolog\Handler\ConsoleHandler')
                    && interface_exists('Symfony\Component\EventDispatcher\EventSubscriberInterface')) {
                    $consoleHandler = new ConsoleHandler($output);
                    foreach ($app['loggers'] as $logger) {
                        $logger->pushHandler($consoleHandler);
                    }
                }
            };
        });
    }
}
