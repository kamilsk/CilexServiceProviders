<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\ForestManager\Tests\Helper;

use OctoLab\Cilex\Monolog\Util\Dumper;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class DumperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dumpToStringProvider
     *
     * @param mixed $value
     * @param string $expected
     */
    public function dumpToString($value, $expected)
    {
        self::assertEquals($expected, Dumper::dumpToString($value));
    }

    /**
     * @return array
     */
    public function dumpToStringProvider()
    {
        $object = new \stdClass();
        $object->property = 'value';
        $object->another = 'value';
        return [
            [null, ''],
            [true, '1'],
            [1, '1'],
            [1.1, '1.1'],
            ['a', 'a'],
            [(array) $object, 'Array([property] => value,[another] => value)'],
            [$object, 'stdClass Object([property] => value,[another] => value)'],
            [new Dumper(), sprintf('%s Object()', Dumper::class)],
        ];
    }
}
