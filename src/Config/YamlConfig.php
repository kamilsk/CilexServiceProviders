<?php

namespace OctoLab\Cilex\Config;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class YamlConfig
{
    /** @var array */
    private $config = [];
    /** @var YamlFileLoader */
    private $fileLoader;

    /**
     * @param YamlFileLoader $fileLoader
     *
     * @api
     */
    public function __construct(YamlFileLoader $fileLoader)
    {
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

    /**
     * @param array $placeholders
     *
     * @return $this
     *
     * @api
     */
    public function replace(array $placeholders)
    {
        if (isset($this->config['parameters'])) {
            $this->transform($this->config['parameters'], $placeholders);
            $placeholders = array_merge($this->config['parameters'], $placeholders);
        }
        $this->transform($this->config, $placeholders);
        unset($this->config['parameters'], $this->config['imports']);
        return $this;
    }

    /**
     * @return array
     *
     * @api
     */
    public function toArray()
    {
        return $this->config;
    }

    /**
     * @param array $base
     *
     * @return array
     */
    private function merge(array $base)
    {
        $mixtures = array_slice(func_get_args(), 1);
        foreach ($mixtures as $mixture) {
            foreach ($mixture as $key => $value) {
                if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
                    $base[$key] = $this->merge($base[$key], $value);
                } else {
                    $base[$key] = $value;
                }
            }
        }
        return $base;
    }

    /**
     * @param array $array
     * @param array $placeholders
     */
    private function transform(array &$array, array $placeholders)
    {
        $wrap = function (&$value) {
            $value = sprintf('/%s/', (string) $value);
        };
        array_walk_recursive($array, function (&$param) use ($wrap, $placeholders) {
            if (strpos($param, 'const(') === 0) {
                if (preg_match('/^const\((.*)\)$/', $param, $matches) && defined($matches[1])) {
                    $param = constant($matches[1]);
                }
            } elseif (preg_match('/^%([^%]+)%$/', $param, $matches)) {
                $placeholder = $matches[1];
                if (isset($placeholders[$placeholder])) {
                    $param = $placeholders[$placeholder];
                }
            } elseif (preg_match_all('/%([^%]+)%/', $param, $matches)) {
                array_walk($matches[0], $wrap);
                $pattern = $matches[0];
                $replacement = array_intersect_key($placeholders, array_flip($matches[1]));
                $param = preg_replace($pattern, $replacement, (string) $param);
            }
        });
    }
}
