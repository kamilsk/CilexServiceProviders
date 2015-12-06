<?php

namespace Test\OctoLab\Cilex\Monolog;

use OctoLab\Cilex\Monolog\Processor\TimeExecutionProcessor;

/**
 * phpunit src/Tests/Monolog/TimeExecutionProcessorTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class TimeExecutionProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $processor = new TimeExecutionProcessor();
        $record = $processor(['extra' => []]);
        self::assertNotEmpty($record['extra']);
        self::assertArrayHasKey('time_execution', $record['extra']);
        self::assertRegExp('/\d+\.\d{3}/', $record['extra']['time_execution']);
    }
}
