<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
final class CountedServiceProviderMock implements ServiceProviderInterface
{
    /** @var int */
    private static $counter = 0;

    /**
     * @return int
     */
    public static function getCounter()
    {
        return self::$counter;
    }

    public static function resetCounter()
    {
        self::$counter = 0;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        self::$counter++;
    }
}
