<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Provider;

use Cilex\Application;
use Cilex\ServiceProviderInterface;
use OctoLab\Cilex\Config\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 *
 * @see \Cilex\Provider\ConfigServiceProvider
 * @see \Igorw\Silex\ConfigServiceProvider
 * @todo roadmap:
 * @todo - imports
 * @todo - parameters
 * @todo - placeholders
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /** @var string */
    protected $filename;
    /** @var array */
    protected $placeholders;

    /**
     * @param string $filename
     * @param array $placeholders
     */
    public function __construct($filename, array $placeholders = [])
    {
        $this->filename = $filename;
        $this->placeholders = $placeholders;
    }

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $that = $this;
        $app['config'] = $app->share(function () use ($app, $that) {
            if (!is_file($that->filename) or !is_readable($that->filename)) {
                throw new \InvalidArgumentException(sprintf('File "%s" is not readable.', $that->filename));
            }
            $info = pathinfo($that->filename);
            switch (strtolower($info['extension'])) {
                case 'yml':
                    $fileLoader = new YamlFileLoader(new FileLocator());
                    $fileLoader->load($that->filename);
                    break;
                default:
                    throw new \RuntimeException(sprintf('Extension "%s" is not supported.', $info['extension']));
            }
            return $fileLoader->getContent();
        });
    }
}
