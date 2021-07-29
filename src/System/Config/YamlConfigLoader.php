<?php


namespace App\System\Config;


use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlConfigLoader extends  FileLoader
{
    /**
     * @param mixed $resource
     * @param string|null $type
     * @return mixed
     */
    public function load($resource, string $type = null)
    {
        return $configValues = Yaml::parse(file_get_contents($resource));
    }

    /**
     * @param mixed $resource
     * @param string|null $type
     * @return bool
     */
    public function supports($resource, string $type = null): bool
    {
        return is_string($resource) && 'yaml' === pathinfo(
                $resource,
                PATHINFO_EXTENSION
            );
    }


}