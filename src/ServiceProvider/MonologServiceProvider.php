<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\Provider as Cilex;
use Monolog\Logger;
use OctoLab\Cilex\Monolog\ConfigResolver;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MonologServiceProvider extends Cilex\MonologServiceProvider
{
    /** @var bool */
    private $initConsoleHandler;

    /**
     * @param bool $initConsoleHandler to initialize {@link \Symfony\Bridge\Monolog\Handler\ConsoleHandler},
     * if all dependencies resolved
     *
     * @api
     */
    public function __construct($initConsoleHandler = true)
    {
        $this->initConsoleHandler = $initConsoleHandler
            && class_exists('\Symfony\Bridge\Monolog\Handler\ConsoleHandler')
            && interface_exists('\Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    /**
     * @param Application $app
     *
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function register(Application $app)
    {
        parent::register($app);
        if (!empty($app['config']['monolog']['name'])) {
            $app['monolog.name'] = $app['config']['monolog']['name'];
        } elseif (empty($app['monolog.name'])) {
            $app['monolog.name'] = $app['console.name'];
        }
        $app['logger'] = $app->share(function () use ($app) {
            return $app['monolog'];
        });
        if (!empty($app['config']['monolog'])) {
            $app['monolog.configure'] = $app->protect(function (Logger $logger) use ($app) {
                $resolver = new ConfigResolver($app);
                $resolver->resolve($app['config']['monolog']);
                if ($this->initConsoleHandler) {
                    $resolver->getHandlers()->offsetSet('console', new ConsoleHandler());
                }
                foreach ($resolver->getHandlers()->keys() as $id) {
                    $logger->pushHandler($resolver->getHandlers()->offsetGet($id));
                }
                foreach ($resolver->getProcessors() as $processor) {
                    $logger->pushProcessor($processor);
                }
                $app->offsetSet('monolog.handlers', $resolver->getHandlers());
            });
        }
    }
}
