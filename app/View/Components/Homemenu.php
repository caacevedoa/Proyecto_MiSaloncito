<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HomeMenu extends Component
{
    public $routes;
    public $id;

    public function __construct($routes = [], $id = null)
    {
        $this->routes = $routes;
        $this->id = $id;
    }

    public function render()
    {
        return view('components.homemenu');
    }
}
