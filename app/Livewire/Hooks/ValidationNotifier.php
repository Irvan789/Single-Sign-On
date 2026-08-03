<?php

namespace App\Livewire\Hooks;

use Closure;
use Illuminate\Validation\ValidationException;
use Livewire\ComponentHook;
use Throwable;

class ValidationNotifier extends ComponentHook
{
    public function exception(Throwable $e, Closure $stopPropagation): void
    {
        if ($e instanceof ValidationException) {
            $this->component->notify('error', $e->validator->errors()->first());
        }
    }
}
