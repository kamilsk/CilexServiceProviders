<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\Provider as Cilex;
use OctoLab\Common\Monolog\Util\ConfigResolver;
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
     * @throws \DomainException
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
        if (!empty($app['config']['monolog'])) {
            $app['monolog.resolver'] = $app::share(function () use ($app) {
                $resolver = new ConfigResolver();
                $resolver->resolve($app['config']['monolog'], $app['monolog.name']);
                if ($this->initConsoleHandler) {
                    $consoleHandler = new ConsoleHandler();
                    $resolver->addHandler('console', $consoleHandler);
                    $resolver->getDefaultChannel()->pushHandler($consoleHandler);
                }
                return $resolver;
            });
            $app['monolog'] = $app::share(function () use ($app) {
                return $app['monolog.resolver']->getDefaultChannel();
            });
        }
        $app['logger'] = $app::share(function () use ($app) {
            return $app['monolog'];
        });
    }
}
