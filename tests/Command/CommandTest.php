<?php

namespace OctoLab\Cilex\Command;

use Cilex\Application;
use OctoLab\Cilex\ServiceProvider\ConfigServiceProvider;
use OctoLab\Cilex\ServiceProvider\DoctrineServiceProvider;
use OctoLab\Cilex\ServiceProvider\MonologServiceProvider;
use OctoLab\Cilex\TestCase;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CommandTest extends TestCase
{
    /**
     * @test
     */
    public function commandNamespace()
    {
        $command = $this->getCommandMock('test');
        self::assertEquals('test', $command->getName());
        $command = $this->getCommandMock('test', 'mock');
        self::assertEquals('mock:test', $command->getName());
    }

    /**
     * @test
     */
    public function getContainer()
    {
        $app = new Application('Test');
        $command = $this->getCommandMock('test', 'mock');
        $app->command($command);
        self::assertInstanceOf(\Pimple::class, $command->getContainer());
    }

    /**
     * @test
     */
    public function getService()
    {
        $app = new Application('Test');
        $command = $this->getCommandMock('test', 'mock');
        $app->command($command);
        $app['service'] = function () {
            return 'service';
        };
        self::assertEquals('service', $command->getService('service'));
    }

    /**
     * @test
     */
    public function getConfig()
    {
        $app = new Application('Test');
        $expected = ['empty' => true];
        $app->register(new ConfigServiceProvider($this->getConfigPath()), ['config' => $expected]);
        $command = $this->getCommandMock('test', 'mock');
        $app->command($command);
        self::assertEquals($expected, $command->getConfig());
    }

    /**
     * @test
     */
    public function getConfigByPath()
    {
        $app = new Application('Test');
        $app->register(new ConfigServiceProvider($this->getConfigPath()));
        $command = $this->getCommandMock('test', 'mock');
        $app->command($command);
        self::assertEquals(E_ALL, $command->getConfig('component:constant'));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function getDbConnectionFail()
    {
        $app = new Application('Test');
        $command = $this->getCommandMock();
        $app->command($command);
        self::assertInstanceOf('\Doctrine\DBAL\Connection', $command->getDbConnection());
    }

    /**
     * @test
     * @dataProvider doctrineConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function getDbConnectionSuccess(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider());
        $command = $this->getCommandMock();
        $app->command($command);
        self::assertInstanceOf('\Doctrine\DBAL\Connection', $command->getDbConnection());
    }

    /**
     * @test
     * @dataProvider doctrineConfigProvider
     * @expectedException \InvalidArgumentException
     *
     * @param ConfigServiceProvider $config
     */
    public function getDbConnectionByAliasFail(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider());
        $command = $this->getCommandMock();
        $app->command($command);
        self::assertInstanceOf('\Doctrine\DBAL\Connection', $command->getDbConnection('undefined'));
    }

    /**
     * @test
     * @dataProvider doctrineConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function getDbConnectionByAliasSuccess(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider());
        $command = $this->getCommandMock();
        $app->command($command);
        self::assertInstanceOf('\Doctrine\DBAL\Connection', $command->getDbConnection('sqlite'));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function getLoggerFail()
    {
        $app = new Application('Test');
        $command = $this->getCommandMock();
        $app->command($command);
        self::assertInstanceOf('\Psr\Log\LoggerInterface', $command->getLogger());
    }

    /**
     * @test
     * @dataProvider monologConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function getLoggerSuccess(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new MonologServiceProvider());
        $command = $this->getCommandMock();
        $app->command($command);
        self::assertInstanceOf('\Psr\Log\LoggerInterface', $command->getLogger());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function getLoggerByIdFail()
    {
        $app = new Application('Test');
        $command = $this->getCommandMock();
        $app->command($command);
        self::assertInstanceOf('\Psr\Log\LoggerInterface', $command->getLogger('unknown'));
    }

    /**
     * @test
     * @dataProvider monologConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function getLoggerByIdSuccess(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new MonologServiceProvider());
        $command = $this->getCommandMock();
        $app->command($command);
        self::assertInstanceOf('\Psr\Log\LoggerInterface', $command->getLogger('app'));
    }

    /**
     * @param string $name
     * @param string|null $namespace
     *
     * @return Command
     */
    private function getCommandMock($name = 'test', $namespace = null)
    {
        /** @var Command $instance */
        $instance = (new \ReflectionClass(CommandMock::class))->newInstanceWithoutConstructor();
        $reflection = (new \ReflectionObject($instance));
        if (null !== $namespace) {
            $property = $reflection->getProperty('namespace');
            $property->setAccessible(true);
            $property->setValue($instance, $namespace);
        }
        $instance->setName($name);
        $reflection->getConstructor()->invoke($instance);
        return $instance;
    }
}
