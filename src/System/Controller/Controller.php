<?php


namespace App\System\Controller;


use App\System\Veiw\View;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller implements IController
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    /**
     * @param $path
     * @param array $data
     * @return Response
     */
    public function render($path, $data = []): Response
    {
        return new Response($this->view->make($path, $data));
    }
}