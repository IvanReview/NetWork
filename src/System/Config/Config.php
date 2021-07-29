<?php


namespace App\System\Config;


use Symfony\Component\Config\FileLocator;

class Config implements IConfig
{
    private array $config = [];
    private $loader;
    private $locator;

    public function __construct($dir)
    {
        $directories = [
            ROOT . '/' . $dir,
        ];

        $this->setLocator($directories);
        $this->setLoader();

    }

    /**
     * @param $file
     */
    public function addConfig($file)
    {
        $configValues = $this->loader->load($this->locator->locate($file));
        if ($configValues) {
            foreach ($configValues as $nameParam => $value){
                $this->config[$nameParam] = $value;
            }
        }
    }

    /**
     * Получить параметр из конфига
     *
     * @param $keyValue
     */
    public function get($keyValue)
    {
        list($key, $value) = explode('.', $keyValue);
        if ($key && isset($this->config[$key])){
            if ($value && isset($this->config[$key][$value])){
                    return  $this->config[$key][$value];
            } else{
                return  $this->config[$key];
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     *
     */
    public function setLoader()
    {
        $this->loader = new YamlConfigLoader($this->locator);
    }

    /**
     * @param $dir
     */
    public function setLocator($dir)
    {
         $this->locator = new FileLocator($dir);
    }

}