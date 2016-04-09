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
     * @throws \OutOfRangeException
     *
     * @api
     */
    public function register(Application $app)
    {
        if (!$app->offsetExists('config') || !isset($app->offsetGet('config')['monolog'])) {
            return;
        }
        $config = $app->offsetGet('config');
        $app['loggers'] = $app::share(function (Application $app) use ($config) {
            return new LoggerLocator($config['monolog'], $app['console.name']);
        });
        $app['logger'] = $app::share(function (Application $app) {
            return $app['loggers']->getDefaultChannel();
        });
        $app['monolog.bridge'] = $app::share(function (Application $app) {
            return function (OutputInterface $output) use ($app) {
                if (class_exists('Symfony\Bridge\Monolog\Handler\ConsoleHandler')
                    && interface_exists('Symfony\Component\EventDispatcher\EventSubscriberInterface')) {
                    $consoleHandler = new ConsoleHandler($output);
                    /** @var \Monolog\Logger $logger */
                    foreach ($app['loggers'] as $logger) {
                        $logger->pushHandler($consoleHandler);
                    }
                }
            };
        });
    }
}
