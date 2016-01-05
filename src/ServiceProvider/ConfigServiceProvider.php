<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use OctoLab\Common\Config\Loader\YamlFileLoader;
use OctoLab\Common\Config\Parser\SymfonyYamlParser;
use OctoLab\Common\Config\SimpleConfig;
use OctoLab\Common\Config\YamlConfig;
use Symfony\Component\Config\FileLocator;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @see \Cilex\Provider\ConfigServiceProvider
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
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     * @throws \Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException
     * @throws \DomainException
     * @throws \RuntimeException
     *
     * @api
     */
    public function register(Application $app)
    {
        $app['config.raw'] = $app::share(function () {
            switch (strtolower(pathinfo($this->filename, PATHINFO_EXTENSION))) {
                case 'yml':
                    $config = (new YamlConfig(new YamlFileLoader(new FileLocator(), new SymfonyYamlParser())))
                        ->load($this->filename)
                        ->replace($this->placeholders)
                    ;
                    break;
                case 'php':
                    $config = (new SimpleConfig(include $this->filename))
                        ->replace($this->placeholders)
                    ;
                    break;
                case 'json':
                    $config = (new SimpleConfig(json_decode(file_get_contents($this->filename), true)))
                        ->replace($this->placeholders)
                    ;
                    break;
                default:
                    throw new \DomainException(sprintf('File "%s" is not supported.', $this->filename));
            }
            return $config;
        });
        $app['config'] = $app::share(function () use ($app) {
            return $app['config.raw']->toArray();
        });
    }
}
