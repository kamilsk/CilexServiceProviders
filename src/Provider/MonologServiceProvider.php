<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Provider;

use Cilex\Application;
use Cilex\Provider as Cilex;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @see \Cilex\Provider\MonologServiceProvider
 */
class MonologServiceProvider extends Cilex\MonologServiceProvider
{
    /**
     * @param string $name
     *
     * @return int
     *
     * @see \Silex\Provider\MonologServiceProvider::translateLevel
     */
    public static function translateLevel($name)
    {
        if (is_int($name)) {
            return $name;
        }
        $levels = Logger::getLevels();
        $upper = strtoupper($name);
        if (!isset($levels[$upper])) {
            throw new \InvalidArgumentException(
                sprintf('Provided logging level "%s" does not exist. Must be a valid monolog logging level.', $name)
            );
        }
        return $levels[$upper];
    }

    /** @var bool */
    private $initConsoleHandler;

    /**
     * @param bool $initConsoleHandler для инициализации <code>\Symfony\Bridge\Monolog\Handler\ConsoleHandler</code>,
     * в случае если все зависимости разрешены
     */
    public function __construct($initConsoleHandler = true)
    {
        $this->initConsoleHandler = $initConsoleHandler
            && class_exists('\Symfony\Bridge\Monolog\Handler\ConsoleHandler', false)
            && interface_exists('\Symfony\Component\EventDispatcher\EventSubscriberInterface', false);
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        parent::register($app);
        if (empty($app['monolog.name'])) {
            if (empty($app['config']['monolog']['name'])) {
                $app['monolog.name'] = $app['console.name'];
            } else {
                $app['monolog.name'] = $app['config']['monolog']['name'];
            }
        }
        if (!empty($app['config']['monolog']['handlers'])) {
            $handlers = $app['config']['monolog']['handlers'];
            $app['monolog.factory'] = $app->protect(function (array $config) use ($app) {
                if (isset($config['type'])) {
                    switch ($config['type']) {
                        case 'stream':
                            if (isset($config['path'])) {
                                $default = [
                                    'level' => $app['monolog.level'],
                                    'bubble' => true,
                                    'permission' => null,
                                ];
                                $config = array_merge($default, $config);
                                $handler = new StreamHandler(
                                    $config['path'],
                                    static::translateLevel($config['level']),
                                    $config['bubble'],
                                    $config['permission']
                                );
                                if (isset($config['formatter'])) {
                                    $handler->setFormatter($app->offsetGet($config['formatter']));
                                }
                                return $handler;
                            }
                            throw new \InvalidArgumentException('Invalid configuration for handler: path is required.');
                        default:
                            throw new \DomainException(sprintf('Handler type %s is not supported.', $config['type']));
                    }
                }
                throw new \InvalidArgumentException('Invalid configuration for handler: type is required.');
            });
            $app['monolog.handlers'] = $app->share(function () use ($app, $handlers) {
                $registry = new \Pimple();
                foreach ($handlers as $name => $handler) {
                    $registry[$name] = $app['monolog.factory']($handler);
                }
                if ($this->initConsoleHandler) {
                    $registry['console'] = new ConsoleHandler();
                }
                return $registry;
            });
            $app['monolog.configure'] = $app->protect(function (Logger $logger) use ($app) {
                $handlers = $app->offsetGet('monolog.handlers');
                foreach ($handlers->keys() as $handler) {
                    $logger->pushHandler($handlers->offsetGet($handler));
                }
            });
        }
    }
}
