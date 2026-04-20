<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardHeader extends Component
{
    public $title;
    public $description;
    public $buttonText;
    public $buttonLink;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $description = null, $buttonText = null, $buttonLink = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->buttonText = $buttonText;
        $this->buttonLink = $buttonLink;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card-header');
    }
}
