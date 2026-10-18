<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataTable extends Component
{
    public $title;
    public $description;

    public function __construct($title, $description = null)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function render(): View|Closure|string
    {
        return view('components.data-table');
    }
}
