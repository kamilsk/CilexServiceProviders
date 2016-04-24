<?php

declare(strict_types = 1);

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
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function getContainer(Application $app)
    {
        $app->command($command = $this->getCommand());
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
        $app->command($command = $this->getCommand());
        $app['id'] = function () : string {
            return 'instance';
        };
        self::assertEquals('instance', $command->getService('id'));
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function getConfig(Application $app)
    {
        $app->register($this->getConfigServiceProvider());
        $app->command($command = $this->getCommand());
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
        $app->command($command = $this->getCommand());
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
        $app->command($command = $this->getCommand());
        self::assertInstanceOf(LoggerInterface::class, $command->getLogger());
        self::assertInstanceOf(LoggerInterface::class, $command->getLogger('debug'));
    }

    /**
     * @test
     */
    public function setNameTest()
    {
        $command = $this->getCommand('test');
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
        $app->command($command = $this->getCommand());
        $output = new BufferedOutput();
        $command->setUpMonologBridge($output);
        $command->getLogger('debug')->error('test');
        self::assertContains('test', $output->fetch());
    }

    /**
     * @param string $name
     *
     * @return Command
     */
    private function getCommand(string $name = null): Command
    {
        return new class($name) extends Command
        {
            protected function configure()
            {
                $this->setName('mock');
            }
        };
    }
}
