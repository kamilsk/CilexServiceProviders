<?php

namespace OctoLab\Cilex\Config;

use OctoLab\Cilex\Config\Loader\YamlFileLoader;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class YamlConfig extends SimpleConfig
{
    /** @var YamlFileLoader */
    private $fileLoader;

    /**
     * @param YamlFileLoader $fileLoader
     *
     * @api
     */
    public function __construct(YamlFileLoader $fileLoader)
    {
        parent::__construct();
        $this->fileLoader = $fileLoader;
    }

    /**
     * @param string $resource
     * @param bool $check
     *
     * @return $this
     *
     * @api
     */
    public function load($resource, $check = false)
    {
        if ($check && !$this->fileLoader->supports($resource)) {
            throw new \DomainException(sprintf('File "%s" is not supported.', $resource));
        }
        $this->fileLoader->load($resource);
        foreach (array_reverse($this->fileLoader->getContent()) as $data) {
            $this->config = $this->merge($this->config, $data);
        }
        return $this;
    }
}
