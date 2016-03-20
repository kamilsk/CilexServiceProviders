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
            $command = $this->getPresetCommand();
            $app->command($command);
            $app->command(new HelloCommand('test'));
            $app->command(new FibonacciCommand('test'));
            $input = new ArgvInput([], $command->getDefinition());
            $output = new BufferedOutput();
            self::assertEquals(0, $command->runMenuItem('Hello, World', $input, $output));
            self::assertContains('Hello, World', $output->fetch());
            self::assertEquals(0, $command->runMenuItem('Fibonacci sequence', $input, $output));
            self::assertContains('Fibonacci sequence: 1, 1, 2, 3, 5, 8, 13, 21, 34, 55', $output->fetch());
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
            $command = $this->getPresetCommand();
            $app->command($command);
            $app->command(new HelloCommand('test'));
            $app->command(new FibonacciCommand('test'));
            $reflection = (new \ReflectionObject($command))->getMethod('execute');
            $reflection->setAccessible(true);
            $output = new BufferedOutput();
            $input = new ArgvInput([], $command->getDefinition());
            self::assertEquals(0, $reflection->invoke($command, $input, $output));
            $output->fetch();
            $input = new ArgvInput([$command->getName(), '--dump'], $command->getDefinition());
            self::assertEquals(0, $reflection->invoke($command, $input, $output));
            self::assertContains(
                "Total commands: 3\n - test:hello World\n\n - test:fibonacci --size=10\n\n - test:fibonacci --size=1\n",
                $output->fetch()
            );
        } else {
            self::assertFalse(false);
        }
    }

    /**
     * @return PresetCommand
     */
    private function getPresetCommand()
    {
        $command = new PresetCommand('test');
        $reflection = (new \ReflectionObject($command))->getProperty('dirtyHack');
        $reflection->setAccessible(true);
        $reflection->setValue($command, true);
        return $command;
    }
}
