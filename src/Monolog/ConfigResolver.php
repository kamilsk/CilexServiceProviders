<?php

namespace OctoLab\Cilex\Monolog;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigResolver
{
    /** @var \Pimple */
    private $handlers;
    /** @var \SplObjectStorage */
    private $processors;

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
     * <pre>[..., 'handlers' => [...], 'processors' => [...]]</pre>
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     *
     * @api
     */
    public function resolve(array $config)
    {
        if (isset($config['handlers'])) {
            $this->resolveHandlers($config['handlers']);
        }
        if (isset($config['processors'])) {
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
            /** @var HandlerInterface $instance */
            $instance = $reflection->newInstanceArgs($arguments);
            if (isset($handler['formatter'])) {
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
     * @param array $formatter
     * @param HandlerInterface $handler
     *
     * @throws \InvalidArgumentException
     */
    private function resolveFormatter(array $formatter, HandlerInterface $handler)
    {
        $class = $this->getClass('Formatter', 'Monolog\Formatter', $formatter);
        $reflection = new \ReflectionClass($class);
        $arguments = $this->getArguments($reflection, $formatter);
        /** @var FormatterInterface $instance */
        $instance = $reflection->newInstanceArgs($arguments);
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
