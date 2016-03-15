<?php

namespace OctoLab\Cilex\Command;

use Cilex\Application;
use Doctrine\DBAL\Connection;
use OctoLab\Cilex\ServiceProvider;
use OctoLab\Cilex\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CommandTest extends TestCase
{
    /**
     * @test
     */
    public function construct()
    {
        $command = new CommandMock();
        self::assertEquals('mock', $command->getName());
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function getContainer(Application $app)
    {
        $app->command($command = new CommandMock());
        self::assertInstanceOf(\Pimple::class, $command->getContainer());
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function getService(Application $app)
    {
        $app->command($command = new CommandMock());
        $app['service'] = function () {
            return 'instance';
        };
        self::assertEquals('instance', $command->getService('service'));
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function getConfig(Application $app)
    {
        $app->register($this->getConfigServiceProvider('config', 'yml'));
        $app->command($command = new CommandMock());
        self::assertEquals(E_ALL, $command->getConfig('app:constant'));
        self::assertEquals($command->getConfig('app:constant'), $command->getConfig()['app:constant']);
        self::assertEquals('fail', $command->getConfig('unknown', 'fail'));
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function getDbConnection(Application $app)
    {
        $app->register($this->getConfigServiceProviderForDoctrine());
        $app->register(new ServiceProvider\DoctrineServiceProvider());
        $app->command($command = new CommandMock());
        self::assertInstanceOf(Connection::class, $command->getDbConnection());
        self::assertInstanceOf(Connection::class, $command->getDbConnection('sqlite'));
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function getLogger(Application $app)
    {
        $app->register($this->getConfigServiceProviderForMonolog());
        $app->register(new ServiceProvider\MonologServiceProvider());
        $app->command($command = new CommandMock());
        self::assertInstanceOf(LoggerInterface::class, $command->getLogger());
        self::assertInstanceOf(LoggerInterface::class, $command->getLogger('debug'));
    }

    /**
     * @test
     */
    public function setNameTest()
    {
        $command = new CommandMock('test');
        self::assertEquals('test:mock', $command->getName());
        $command->setName('success');
        self::assertEquals('test:success', $command->getName());
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function setUpMonologBridge(Application $app)
    {
        $app->register($this->getConfigServiceProviderForMonolog());
        $app->register(new ServiceProvider\MonologServiceProvider());
        $app->command($command = new CommandMock());
        $output = new BufferedOutput();
        $command->setUpMonologBridge($output);
        $command->getLogger()->error('test');
        self::assertNotEmpty($output->fetch());
    }
}
