<?php

namespace Test\OctoLab\Cilex\Config;

use OctoLab\Cilex\Config\Util\ArrayHelper;

/**
 * phpunit src/Tests/Config/ArrayHelperTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ArrayHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider arrayDataProvider
     *
     * @param array $a
     * @param array $b
     * @param array $expected
     */
    public function merge(array $a, array $b, array $expected)
    {
        self::assertEquals(ArrayHelper::merge($a, $b), $expected);
    }

    /**
     * @return array[]
     */
    public function arrayDataProvider()
    {
        return [
            [
                [1, 2],
                [3, 4, 5],
                [1, 2, 3, 4, 5],
            ],
            [
                ['a' => 'g', 'c' => 'd'],
                ['a' => 'b', 'e' => 'f'],
                ['a' => 'b', 'c' => 'd', 'e' => 'f'],
            ],
            [
                ['a' => [1, 'b' => 'd', 2]],
                ['a' => [3, 4, 'b' => 'c', 5]],
                ['a' => [1, 'b' => 'c', 2, 3, 4, 5]],
            ],
        ];
    }
}
