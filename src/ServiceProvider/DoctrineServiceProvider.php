<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use OctoLab\Kilex\ServiceProvider\DoctrineServiceProvider as KilexDoctrineServiceProvider;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class DoctrineServiceProvider extends KilexDoctrineServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Application $app
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @api
     */
    public function register(Application $app)
    {
        $this->setup($app);
        $app
            ->offsetGet('console')
            ->getHelperSet()
            ->set(new ConnectionHelper($app->offsetGet('connection')), 'connection')
        ;
    }
}
