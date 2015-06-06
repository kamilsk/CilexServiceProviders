<?php

namespace OctoLab\Cilex\Tests\Doctrine;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class PartialMigration extends TestMigration
{
    /** @var array */
    private $downgrade = ['ISSUE-8/downgrade.sql'];
}
