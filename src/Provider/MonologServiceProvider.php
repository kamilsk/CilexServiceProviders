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
     * @param Application $app
     */
    public function register(Application $app)
    {
        parent::register($app);
        if (!empty($app['config']['monolog']['handlers'])) {
            $handlers = $app['config']['monolog']['handlers'];
            $app['monolog.factory'] = $app->protect(function (array $config) use ($app) {
                if (isset($config['type'])) {
                    $level = Logger::DEBUG;
                    $levels = Logger::getLevels();
                    switch ($config['type']) {
                        case 'stream':
                            if (isset($config['path'])) {
                                if (isset($config['level'], $levels[strtoupper($config['level'])])) {
                                    $level = $levels[strtoupper($config['level'])];
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
                    }
                }
                throw new \InvalidArgumentException('Invalid config were transferred.');
            });
            $app['monolog.configure'] = $app->protect(function (Logger $log) use ($app, $handlers) {
                foreach ($handlers as $handler) {
                    $log->pushHandler($app['monolog.factory']($handler));
                }
            });
        }
    }
}
