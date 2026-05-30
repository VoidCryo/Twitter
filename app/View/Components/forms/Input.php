<?php

namespace App\View\Components\forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public function __construct(
        public string $name,
        public string $label = '',
        public string $type = 'text',
        public string $placeholder = '',
        public bool $required = false,
        public string $icon = '',
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.forms.input');
    }
}
