<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Monolog\Processor;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class TimeExecutionProcessor
{
    /** @var float */
    private $started;

    public function __construct()
    {
        $this->started = microtime(true);
    }

    /**
     * @param array $record
     *
     * @return array
     *
     * @api
     */
    public function __invoke(array $record)
    {
        $timestamp = microtime(true);
        $record['extra'] = array_merge(
            $record['extra'],
            [
                'time_execution' => sprintf('%01.3f', $timestamp - $this->started),
            ]
        );
        return $record;
    }
}
