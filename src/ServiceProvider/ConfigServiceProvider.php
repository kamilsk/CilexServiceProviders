<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use OctoLab\Cilex\Command\Config\DumpCommand;
use OctoLab\Common\Config;
use Symfony\Component\Config\FileLocator;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /** @var string */
    private $filename;
    /** @var array */
    private $placeholders;

    /**
     * @param string $filename
     * @param array $placeholders
     *
     * @api
     */
    public function __construct($filename, array $placeholders = [])
    {
        $this->filename = $filename;
        $this->placeholders = $placeholders;
    }

    /**
     * @param Application $app
     *
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     * @throws \Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException
     * @throws \DomainException
     * @throws \LogicException
     *
     * @api
     */
    public function register(Application $app)
    {
        $app['config'] = $app::share(function () {
            switch (strtolower(pathinfo($this->filename, PATHINFO_EXTENSION))) {
                case 'yml':
                    $loader = new Config\Loader\FileLoader(new FileLocator(), new Config\Loader\Parser\YamlParser());
                    $config = (new Config\FileConfig($loader))->load($this->filename, $this->placeholders);
                    break;
                case 'php':
                    $config = (new Config\SimpleConfig(require $this->filename, $this->placeholders));
                    break;
                case 'json':
                    $loader = new Config\Loader\FileLoader(new FileLocator(), new Config\Loader\Parser\JsonParser());
                    $config = (new Config\FileConfig($loader))->load($this->filename, $this->placeholders);
                    break;
                default:
                    throw new \DomainException(sprintf('File "%s" is not supported.', $this->filename));
            }
            return $config;
        });
        $app->command(new DumpCommand('config'));
    }
}
