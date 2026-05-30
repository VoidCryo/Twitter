<?php

namespace App\View\Components\forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public string $type = 'button',
        public string $variant = 'primary',
        public bool $loading = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.forms.button');
    }
}
