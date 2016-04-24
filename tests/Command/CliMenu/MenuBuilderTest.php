<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\Command\CliMenu;

use OctoLab\Cilex\TestCase;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MenuBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function getItemCallback()
    {
        $builder = $this->getMenuBuilder();
        $builder->addItem('test', function () : string {
            return 'success';
        });
        self::assertEquals('success', call_user_func($builder->getItemCallback('test')));
        try {
            self::assertEmpty(call_user_func($builder->getItemCallback('unknown')));
            self::fail(sprintf('%s exception expected.', \InvalidArgumentException::class));
        } catch (\InvalidArgumentException $e) {
            self::assertContains('Callback for item "unknown" not found.', $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function getItemCallbacks()
    {
        $builder = $this->getMenuBuilder();
        self::assertCount(0, $builder->getItemCallbacks());
        $builder->addItem('test', function () : string {
            return 'success';
        });
        self::assertCount(1, $builder->getItemCallbacks());
    }
}
