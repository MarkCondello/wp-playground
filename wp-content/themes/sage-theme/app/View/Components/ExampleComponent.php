<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;

class ExampleComponent extends Component
{
    /**
     * The alert type.
     *
     * @var string
     */
    public $title;

    public function __construct($title) {
      $this->title = $title;
  }
    
    public function render()
    {
        return $this->view('components.example');
    }
}
