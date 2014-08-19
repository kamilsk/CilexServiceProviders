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
        $file = $this->filename;
        $placeholders = $this->placeholders;
        $app['config'] = $app->share(function () use ($app, $file, $placeholders) {
            $loader = new YamlFileLoader(new FileLocator());
            switch (true) {
                case $loader->supports($file):
                    $loader->load($file);
                    break;
                default:
                    throw new \RuntimeException(sprintf('File "%s" is not supported.', $file));
            }
            $content = $loader->getContent();
            if (isset($content['parameters'])) {
                $placeholders = array_merge($content['parameters'], $placeholders);
                unset($content['parameters']);
                array_walk_recursive($content, function (&$param) use ($placeholders) {
                    if (preg_match('/^%(.+)%$/', $param, $matches)) {
                        $placeholder = $matches[1];
                        if (isset($placeholders[$placeholder])) {
                            $param = $placeholders[$placeholder];
                        }
                    }
                });
            }
            return $content;
        });
    }
}
