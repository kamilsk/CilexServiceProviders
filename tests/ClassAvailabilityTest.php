<?php

declare(strict_types = 1);

namespace OctoLab\Cilex;

use OctoLab\Common\Test\ClassAvailability;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ClassAvailabilityTest extends ClassAvailability
{
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
        static $excluded = [
            // no dependencies
            'Symfony\\Bridge\\Monolog\\Handler\\DebugHandler' => true,
            'Symfony\\Bridge\\Monolog\\Logger' => true,
            'Symfony\\Component\\EventDispatcher\\DependencyInjection\\RegisterListenersPass' => true,
            'Zend\\EventManager\\Filter\\FilterIterator' => true,
            'PackageVersions\\Installer' => true,
            // https://github.com/composer/composer/issues/5239
            'OctoLab\\Cilex\\Command\\extends' => true,
        ];
        return strpos($class, 'Cilex\\Provider\\Console\\Adapter') === 0
            || !empty($excluded[$class]);
    }
}
