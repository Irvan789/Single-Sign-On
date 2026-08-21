<?php

namespace App\Livewire\Forms;

use App\Concerns\PassportValidationRules;
use Laravel\Passport\Client;
use Livewire\Form;

class PassportClientForm extends Form
{
    use PassportValidationRules;

    public string $name = '';

    public array $callbacks = [];

    public function set(?Client $client)
    {
        $this->name = $client->name;

        $this->callbacks = $client->redirect_uris;
    }

    public function data(): array
    {
        $this->validate($this->passportRules());

        return $this->all();
    }
}
