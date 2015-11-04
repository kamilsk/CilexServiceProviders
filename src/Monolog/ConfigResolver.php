<?php

namespace OctoLab\Cilex\Monolog;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigResolver
{
    /**
     * @deprecated BC will be removed in v2.0
     *
     * @var \Pimple
     */
    private $app;
    /** @var \Pimple */
    private $handlers;
    /** @var \SplObjectStorage */
    private $processors;

    /**
     * @param \Pimple $app
     */
    public function __construct(\Pimple $app)
    {
        $this->app = $app;
    }

    /**
     * @return \Pimple
     *
     * @api
     */
    public function getHandlers()
    {
        if (null === $this->handlers) {
            $this->handlers = new \Pimple();
        }
        return $this->handlers;
    }

    /**
     * @return \SplObjectStorage
     *
     * @api
     */
    public function getProcessors()
    {
        if (null === $this->processors) {
            $this->processors = new \SplObjectStorage();
        }
        return $this->processors;
    }

    /**
     * @param array $config
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function resolve(array $config)
    {
        if (array_key_exists('handlers', $config)) {
            $this->resolveHandlers($config['handlers']);
        }
        if (array_key_exists('processors', $config)) {
            $this->resolveProcessors($config['processors']);
        }
        return $this;
    }

    /**
     * @param array $config
     *
     * @throws \InvalidArgumentException
     */
    private function resolveHandlers(array $config)
    {
        foreach ($config as $key => $handler) {
            $class = $this->getClass('Handler', 'Monolog\Handler', $handler);
            $reflection = new \ReflectionClass($class);
            $arguments = $this->getArguments($reflection, $handler);
            if (empty($arguments) && !strcasecmp($class, 'Monolog\Handler\StreamHandler')) {
                // deprecated BC will be removed in v2.0
                if (empty($handler['path'])) {
                    throw new \InvalidArgumentException('Invalid configuration for handler: path is required.');
                }
                $default = [
                    'level' => isset($this->app['monolog.level']) ? $this->app['monolog.level'] : Logger::DEBUG,
                    'bubble' => true,
                    'permission' => null,
                ];
                $arguments = array_merge($default, $handler);
                $arguments['stream'] = $arguments['path'];
                $arguments['filePermission'] = $arguments['permission'];
                unset($arguments['path'], $arguments['permission']);
                $arguments = $this->resolveArguments($arguments, $reflection);
            }
            /** @var HandlerInterface $instance */
            $instance = $reflection->newInstanceArgs($arguments);
            if (array_key_exists('formatter', $handler)) {
                $this->resolveFormatter($handler['formatter'], $instance);
            }
            $this->getHandlers()->offsetSet($key, $instance);
        }
    }

    /**
     * @param array $config
     *
     * @throws \InvalidArgumentException
     */
    private function resolveProcessors(array $config)
    {
        foreach ($config as $processor) {
            $class = $this->getClass('Processor', 'Monolog\Processor', $processor);
            $reflection = new \ReflectionClass($class);
            $arguments = $this->getArguments($reflection, $processor);
            $this->getProcessors()->attach($reflection->newInstanceArgs($arguments));
        }
    }

    /**
     * @param array|string $formatter string will be removed in v2.0
     * @param HandlerInterface $handler
     *
     * @throws \InvalidArgumentException
     */
    private function resolveFormatter($formatter, HandlerInterface $handler)
    {
        if (is_string($formatter)) {
            // deprecated BC will be removed in v2.0
            $instance = $this->app->offsetGet($formatter);
        } else {
            $class = $this->getClass('Formatter', 'Monolog\Formatter', $formatter);
            $reflection = new \ReflectionClass($class);
            $arguments = $this->getArguments($reflection, $formatter);
            /** @var FormatterInterface $instance */
            $instance = $reflection->newInstanceArgs($arguments);
        }
        $handler->setFormatter($instance);
    }

    /**
     * @param string $component
     * @param string $namespace
     * @param array $config
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private function getClass($component, $namespace, array $config)
    {
        if (isset($config['type'])) {
            $class = $this->resolveClass($config['type'], $namespace, $component);
        } elseif (isset($config['class'])) {
            $class = $config['class'];
        } else {
            throw new \InvalidArgumentException(sprintf('%s\'s config requires either the type or class.', $component));
        }
        return $class;
    }

    /**
     * @param \ReflectionClass $reflection
     * @param array $config
     *
     * @return array
     */
    private function getArguments(\ReflectionClass $reflection, array $config)
    {
        $arguments = [];
        if (isset($config['arguments'])) {
            $arguments = $this->resolveArguments($config['arguments'], $reflection);
        }
        return $arguments;
    }

    /**
     * @param string $type
     * @param string $ns
     * @param string $postfix
     *
     * @return string
     */
    private function resolveClass($type, $ns, $postfix = null)
    {
        $parts = explode(' ', ucwords(str_replace('_', ' ', $type)));
        $class = implode('', $parts);
        return $ns . '\\' . $class . $postfix;
    }

    /**
     * @param array $arguments
     * @param \ReflectionClass $reflection
     *
     * @return array
     */
    private function resolveArguments(array $arguments, \ReflectionClass $reflection)
    {
        if (is_int(key($arguments))) {
            return $arguments;
        } else {
            $params = [];
            foreach ($reflection->getConstructor()->getParameters() as $param) {
                try {
                    $params[$param->getName()] = $param->getDefaultValue();
                } catch (\Exception $e) {
                    $params[$param->getName()] = null;
                }
            }
            return array_merge($params, array_intersect_key($arguments, $params));
        }
    }
}
