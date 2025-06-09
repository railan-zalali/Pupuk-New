<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ReportExportButtons extends Component
{
    public $route;
    public $parameters;

    public function __construct($route, $parameters = [])
    {
        $this->route = $route;
        $this->parameters = $parameters;
    }

    public function render()
    {
        return view('components.report-export-buttons');
    }
}
