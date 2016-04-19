<?php

declare(strict_types = 1);

namespace OctoLab\Cilex;

use Cilex\Application as CilexApplication;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function register()
    {
        $app = new CilexApplication('test');
        self::assertEquals(0, ServiceProvider\CountedServiceProviderMock::getCounter());
        $app->register(new ServiceProvider\CountedServiceProviderMock());
        $app->register(new ServiceProvider\CountedServiceProviderMock());
        $app->register(new ServiceProvider\CountedServiceProviderMock());
        self::assertEquals(3, ServiceProvider\CountedServiceProviderMock::getCounter());
        ServiceProvider\CountedServiceProviderMock::resetCounter();
        self::assertEquals(0, ServiceProvider\CountedServiceProviderMock::getCounter());
        $app = new Application('test');
        $app->register(new ServiceProvider\CountedServiceProviderMock());
        $app->register(new ServiceProvider\CountedServiceProviderMock());
        $app->register(new ServiceProvider\CountedServiceProviderMock());
        self::assertEquals(1, ServiceProvider\CountedServiceProviderMock::getCounter());
        ServiceProvider\CountedServiceProviderMock::resetCounter();
    }
}
