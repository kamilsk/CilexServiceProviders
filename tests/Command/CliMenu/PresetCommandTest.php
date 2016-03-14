<?php

namespace OctoLab\Cilex\Command\CliMenu;

use Cilex\Application;
use OctoLab\Cilex\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class PresetCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function runMenuItem(Application $app)
    {
        if ($this->isValidEnvironment()) {
            $app->register($this->getConfigServiceProviderForCliMenu());
            $command = new PresetCommand('test');
            $app->command($command);
            $app->command(new HelloCommand('test'));
            $app->command(new FibonacciCommand('test'));
            $input = new ArgvInput([], $command->getDefinition());
            $buffer = new BufferedOutput();
            self::assertEquals(0, $command->runMenuItem('Hello, World', $input, $buffer));
            self::assertContains('Hello, World', $buffer->fetch());
            self::assertEquals(0, $command->runMenuItem('Fibonacci sequence', $input, $buffer));
            self::assertContains('Fibonacci sequence: 1, 1, 2, 3, 5, 8, 13, 21, 34, 55', $buffer->fetch());
        } else {
            self::assertFalse(false);
        }
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function execute(Application $app)
    {
        if ($this->isValidEnvironment()) {
            $app->register($this->getConfigServiceProviderForCliMenu());
            $command = new PresetCommand('test');
            $app->command($command);
            $app->command(new HelloCommand('test'));
            $app->command(new FibonacciCommand('test'));
            $reflection = (new \ReflectionObject($command))->getMethod('execute');
            $reflection->setAccessible(true);
            $buffer = new BufferedOutput();
            $input = new ArgvInput([], $command->getDefinition());
            self::assertEquals(0, $reflection->invoke($command, $input, $buffer));
            $buffer->fetch();
            $input = new ArgvInput([$command->getName(), '--dump'], $command->getDefinition());
            self::assertEquals(0, $reflection->invoke($command, $input, $buffer));
            self::assertContains(
                "Total commands: 2\n - test:hello World\n - test:fibonacci --size=10",
                $buffer->fetch()
            );
        } else {
            self::assertFalse(false);
        }
    }

    /**
     * @return bool
     */
    private function isValidEnvironment()
    {
        return defined('HHVM_VERSION') || (defined('PHP_VERSION') && version_compare(PHP_VERSION, '5.6', '>='));
    }
}
