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
                    $level = Logger::DEBUG;
                    switch ($config['type']) {
                        case 'stream':
                            if (isset($config['path'])) {
                                if (isset($config['level'])) {
                                    $level = static::translateLevel($config['level']);
                                }
                                $handler = new StreamHandler(
                                    $config['path'],
                                    $level,
                                    isset($config['bubble']) ? $config['bubble'] : true,
                                    isset($config['permission']) ? $config['permission'] : null
                                );
                                if (isset($config['formatter'])) {
                                    $handler->setFormatter($app->offsetGet($config['formatter']));
                                }
                                return $handler;
                            }
                            break;
                        default:
                            throw new \DomainException(sprintf('Handler type %s is not supported.', $config['type']));
                    }
                }
                throw new \InvalidArgumentException(
                    sprintf('Invalid configuration %s for handler.', json_encode($config))
                );
            });
            $app['monolog.handlers'] = $app->share(function () use ($app, $handlers) {
                $registry = new \Pimple();
                foreach ($handlers as $name => $handler) {
                    $registry[$name] = $app['monolog.factory']($handler);
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
