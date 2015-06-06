<?php

namespace OctoLab\Cilex\Tests\Doctrine;

use OctoLab\Cilex\Doctrine\Util\Parser;

/**
 * phpunit src/Tests/Doctrine/ParserTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider extractSqlDataProvider
     *
     * @param string $text
     * @param array $expected
     */
    public function extractSql($text, array $expected)
    {
        $parser = new Parser();
        self::assertEquals($expected, $parser->extractSql($text));
    }

    /**
     * @return array
     */
    public function extractSqlDataProvider()
    {
        return [
            [
                '-- комментарий
                INSERT INTO `a` (`b`, `c`)
                VALUES (1, 2);',
                ['INSERT INTO `a` (`b`, `c`) VALUES (1, 2)']
            ],
            [
                '# комментарий
                UPDATE
                `a`,
                (SELECT id FROM `b` WHERE `c`=1) `d`
                SET
                    `e`=1 # комментарий
                WHERE
                    `f`=2;
                /*
                комментарий
                */
                UPDATE
                `a`,
                (SELECT id FROM `b` WHERE `c`=1) `d`
                SET
                    `e`=1 -- комментарий
                WHERE
                    `f`=2;',
                [
                    'UPDATE `a`, (SELECT id FROM `b` WHERE `c`=1) `d` SET `e`=1 WHERE `f`=2',
                    'UPDATE `a`, (SELECT id FROM `b` WHERE `c`=1) `d` SET `e`=1 WHERE `f`=2',
                ]
            ],
        ];
    }
}
