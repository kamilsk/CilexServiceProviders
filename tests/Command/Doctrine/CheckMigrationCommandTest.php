<?php

namespace OctoLab\Cilex\Command\Doctrine;

use Cilex\Application;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Helper\ConfigurationHelper;
use OctoLab\Cilex\ServiceProvider\DoctrineServiceProvider;
use OctoLab\Cilex\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CheckMigrationCommandTest extends TestCase
{
    private $content = <<<EOF
Upgrade by migration %s
1. CREATE TABLE test ( id INT, title VARCHAR(8) NOT NULL, PRIMARY KEY (id) )
Downgrade by migration %s
1. CREATE TABLE test ( id INT, title VARCHAR(8) NOT NULL, PRIMARY KEY (id) )
2. DROP TABLE test CASCADE
EOF;

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function execute(Application $app)
    {
        $app->register($this->getConfigServiceProviderForDoctrine());
        $app->register(new DoctrineServiceProvider());
        $command = new CheckMigrationCommand('test');
        $app->command($command);

        $configuration = new Configuration($app['connection']);
        $reflection = new \ReflectionObject($configuration);
        $property = $reflection->getProperty('migrationsDirectory');
        $property->setAccessible(true);
        $property->setValue($configuration, __DIR__);
        $property = $reflection->getProperty('migrationsNamespace');
        $property->setAccessible(true);
        $property->setValue($configuration, __NAMESPACE__);
        $command->getHelperSet()->set(new ConfigurationHelper(null, $configuration), 'configuration');

        $reflection = (new \ReflectionObject($command))->getMethod('execute');
        $reflection->setAccessible(true);
        $output = new BufferedOutput();
        $input = new ArgvInput(
            [
                $command->getName(),
                __DIR__ . '/migrations/2/ISSUE-29/upgrade.sql',
            ],
            $command->getDefinition()
        );
        $reflection->invoke($command, $input, $output);
        self::assertContains(
            '1. CREATE TABLE test ( id INT, title VARCHAR(8) NOT NULL, PRIMARY KEY (id) )',
            $output->fetch()
        );
        $input = new ArgvInput(
            [
                $command->getName(),
                __DIR__ . '/migrations/2/ISSUE-29/empty.sql',
            ],
            $command->getDefinition()
        );
        $reflection->invoke($command, $input, $output);
        self::assertContains('is empty', $output->fetch());
        $input = new ArgvInput(
            [
                $command->getName(),
                '20160320120000',
            ],
            $command->getDefinition()
        );
        $reflection->invoke($command, $input, $output);
        self::assertContains(sprintf($this->content, '20160320120000', '20160320120000'), $output->fetch());
        $input = new ArgvInput(
            [
                $command->getName(),
                Version20160320120000::class,
            ],
            $command->getDefinition()
        );
        $reflection->invoke($command, $input, $output);
        self::assertContains(
            sprintf($this->content, Version20160320120000::class, Version20160320120000::class),
            $output->fetch()
        );
        try {
            $input = new ArgvInput(
                [
                    $command->getName(),
                    'Unknown\\MigrationClass',
                ],
                $command->getDefinition()
            );
            $reflection->invoke($command, $input, $output);
            self::fail(sprintf('%s exception expected.', \InvalidArgumentException::class));
        } catch (\InvalidArgumentException $e) {
            self::assertTrue(true);
        }
        try {
            $input = new ArgvInput(
                [
                    $command->getName(),
                    Version20160320000000::class,
                ],
                $command->getDefinition()
            );
            $reflection->invoke($command, $input, $output);
            self::fail(sprintf('%s exception expected.', \RuntimeException::class));
        } catch (\RuntimeException $e) {
            self::assertTrue(true);
        }
    }
}
