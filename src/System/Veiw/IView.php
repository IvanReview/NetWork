<?php


namespace App\System\Veiw;


interface IView
{
    public function make($path, $data);

}