<?php

namespace OctoLab\Cilex\Command;

use Cilex\Application;
use OctoLab\Cilex\ServiceProvider\ConfigServiceProvider;
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
     */
    public function getConfigSuccess()
    {
        if (defined('HHVM_VERSION') || (defined('PHP_VERSION') && version_compare(PHP_VERSION, '5.6', '>='))) {
            $app = new Application('Test');
            $command = new PresetCommand('test');
            $app->register(new ConfigServiceProvider($this->getConfigPath('cli-menu/config')));
            $app->command($command);
            $expected = [
                'title' => 'Test CLI Menu',
                'items' => [
                    ['text' => 'Hello, World', 'callable' => 'test:hello', 'options' => ['message' => 'World']],
                    ['text' => 'Fibonacci sequence', 'callable' => 'test:fibonacci', 'options' => ['size' => 10]],
                ],
            ];
            self::assertEquals($expected, $command->getConfig());
        } else {
            self::assertFalse(false);
        }
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function getConfigFail()
    {
        if (defined('HHVM_VERSION') || (defined('PHP_VERSION') && version_compare(PHP_VERSION, '5.6', '>='))) {
            $app = new Application('Test');
            $command = new PresetCommand('test');
            $app->command($command);
            $command->getConfig();
        } else {
            throw new \RuntimeException('Not supported version.');
        }
    }

    /**
     * @test
     */
    public function runMenuItem()
    {
        if (defined('HHVM_VERSION') || (defined('PHP_VERSION') && version_compare(PHP_VERSION, '5.6', '>='))) {
            $app = new Application('Test');
            $app->register(new ConfigServiceProvider($this->getConfigPath('cli-menu/config')));
            $command = new PresetCommand('test');
            $app->command($command);
            $app->command(new HelloCommand('test'));
            $app->command(new FibonacciCommand('test'));
            $input = new ArgvInput();
            $buffer = new BufferedOutput();
            $command->runMenuItem('Hello, World', $input, $buffer);
            self::assertContains('Hello, World', $buffer->fetch());
            $command->runMenuItem('Fibonacci sequence', $input, $buffer);
            self::assertContains('Fibonacci sequence: 1, 1, 2, 3, 5, 8, 13, 21, 34, 55', $buffer->fetch());
        } else {
            self::assertFalse(false);
        }
    }
}
