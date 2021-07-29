<?php

namespace App\System;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

/**
 * 
 */
class App 
{

	private $request;
	public $router;
	public $routes;
	private $requestContext;

	private $controller;
	private $arguments;

	//содержит разные объекты в числе ОРМ
	private array $container;


	private static $instance = null;

	private $db;

	private function __construct()
	{
		$this->setRequest();
		$this->setRequestContext();
		$this->setRouter();
		$this->routes = $this->router->getRouteCollection();
	}

    /**
     *
     */
    public function setRequest()
	{
		$this->request = Request::createFromGlobals();
	}

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Создание роутера и загрузка в него маршрутов
     */
    public function setRouter()
    {

        $fileLocator = new FileLocator(array(__DIR__));
        $loader = new YamlFileLoader($fileLocator);

        $this->router = new Router(
            $loader,
            ROOT . '/config/routes.yaml',
            array('cache_dir' => __DIR__ . '/storage/cache'),
        );

    }

    /**
     *
     */
    public function setRequestContext()
    {
        $this->requestContext = new RequestContext();
        $this->requestContext->fromRequest($this->request);
    }

    public function getRequestContext()
    {
        return $this->requestContext;
    }


    /**
     * @return App|null
     */
    public static function getInstance(): ?App
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }

    /**
     * @return array|callable|false|mixed|object|string|\Symfony\Component\HttpKernel\Controller\string
     */
    public function getController()
    {
        return (new ControllerResolver())->getController($this->request);
    }



    public function getArguments($controller)
    {
        return (new ArgumentResolver())->getArguments($this->request, $controller);
    }


    /**
     * Запуск маршрутизатора
     */
    public function run()
	{
		$matcher = new UrlMatcher($this->routes, $this->requestContext);
        try {
            $this->request->attributes->add($matcher->match($this->request->getPathInfo()));
            $this->controller = $this->getController();
            $this->arguments = $this->getArguments($this->controller);

            $response = call_user_func_array($this->controller, $this->arguments);

        } catch (\Exception $e){
            exit($e);
        }

        $response->send();
	}

	public function add($key, $object): bool
    {
        $this->container[$key] = $object;
        return true;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getDataFromContainer($key)
    {
        if (isset($this->container[$key])){
            return $this->container[$key];
        }
        return  null;
    }
}