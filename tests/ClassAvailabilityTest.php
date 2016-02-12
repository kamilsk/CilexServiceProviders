<?php

namespace Test\OctoLab\Cilex;

/**
 * phpunit tests/ClassAvailabilityTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ClassAvailabilityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function classmap()
    {
        foreach ($this->getClasses() as $class) {
            self::assertTrue(class_exists($class) || interface_exists($class) || trait_exists($class));
        }
    }

    /**
     * @return string[]
     */
    private function getClasses()
    {
        $classes = [];
        $excluded = [
            // parent class or interface not found
            '\Symfony\Bridge\Monolog\Handler\DebugHandler' => true,
            '\Symfony\Bridge\Monolog\Logger' => true,
            '\Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass' => true,
            '\Zend\EventManager\Filter\FilterIterator' => true,
        ];
        foreach (require dirname(__DIR__) . '/vendor/composer/autoload_classmap.php' as $class => $path) {
            if (empty($excluded['\\' . $class])
                // parent class or interface not found
                && strpos($class, 'Cilex\Provider\Console\Adapter') === false
                // php 5.6+
                && strpos($class, 'PhpSchool\CliMenu') === false
            ) {
                $classes[] = $class;
            }
        }
        return $classes;
    }
}
