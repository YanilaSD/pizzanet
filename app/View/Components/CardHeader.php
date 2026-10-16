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
    public $buttonTextSecondary;
    public $buttonLinkSecondary;

    public function __construct($title, $description = null, $buttonText = null, $buttonLink = null, $buttonTextSecondary = null, $buttonLinkSecondary = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->buttonText = $buttonText;
        $this->buttonLink = $buttonLink;
        $this->buttonTextSecondary = $buttonTextSecondary;
        $this->buttonLinkSecondary = $buttonLinkSecondary;
    }

    public function render(): View|Closure|string
    {
        return view('components.card-header');
    }
}
