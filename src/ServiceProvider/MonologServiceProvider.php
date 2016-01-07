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
    /** @var null|string */
    private $consoleHandler;

    /**
     * @param bool|string $initConsoleHandler to initialize {@link \Symfony\Bridge\Monolog\Handler\ConsoleHandler},
     * if all dependencies resolved
     *
     * @api
     */
    public function __construct($initConsoleHandler = 'console')
    {
        if (!empty($initConsoleHandler)
            && class_exists('Symfony\Bridge\Monolog\Handler\ConsoleHandler')
            && interface_exists('Symfony\Component\EventDispatcher\EventSubscriberInterface')
        ) {
            $this->consoleHandler = is_string($initConsoleHandler) ? (string) $initConsoleHandler : 'console';
        }
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
                $config = $app['config']['monolog'];
                if ($this->consoleHandler !== null) {
                    $config['handlers'][$this->consoleHandler] = [
                        'class' => ConsoleHandler::class,
                    ];
                }
                $resolver = new ConfigResolver();
                $resolver->resolve($config, $app['monolog.name']);
                if ($this->consoleHandler !== null) {
                    $resolver->getDefaultChannel()->pushHandler($resolver->getHandlers()[$this->consoleHandler]);
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
