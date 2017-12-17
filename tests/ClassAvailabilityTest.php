<?php

declare(strict_types = 1);

namespace OctoLab\Cilex;

use OctoLab\Common\Test\ClassAvailability;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ClassAvailabilityTest extends ClassAvailability
{
    const EXCLUDED = [
        // no dependencies
        'Symfony\\Bridge\\Monolog\\Handler\\DebugHandler' => true,
        'Symfony\\Bridge\\Monolog\\Logger' => true,
        'Symfony\\Component\\EventDispatcher\\DependencyInjection\\RegisterListenersPass' => true,
        'Zend\\EventManager\\Filter\\FilterIterator' => true,
        'PackageVersions\\Installer' => true,
        // https://github.com/composer/composer/issues/5239
        'OctoLab\\Cilex\\extends' => true,
        'OctoLab\\Cilex\\Command\\extends' => true,
        'OctoLab\\Cilex\\Command\\CliMenu\\extends' => true,
        'OctoLab\\Cilex\\Command\\Doctrine\\extends' => true,
    ];
    const GROUP_EXCLUDED = [
        // no dependencies
        'Cilex\\Provider\\Console\\Adapter' => true,
        'OctoLab\\Common\\Asset' => true,
        'OctoLab\\Common\\Composer' => true,
        'OctoLab\\Common\\Doctrine\\Migration' => true,
        'OctoLab\\Common\\Twig' => true,
        'Symfony\\Component\\HttpKernel' => true,
    ];

    /**
     * {@inheritdoc}
     */
    protected function getClasses(): \Generator
    {
        foreach (require dirname(__DIR__) . '/vendor/composer/autoload_classmap.php' as $class => $path) {
            $signal = yield $class;
            if (SIGSTOP === $signal) {
                return;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function isFiltered(string $class): bool
    {
        foreach (self::GROUP_EXCLUDED as $group => $isOn) {
            if ($isOn && strpos($class, $group) === 0) {
                return true;
            }
        }
        return array_key_exists($class, self::EXCLUDED) && self::EXCLUDED[$class];
    }
}
