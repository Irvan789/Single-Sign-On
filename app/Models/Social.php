<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'user_id', 'provider', 'provider_id'])]
class Social extends Model
{
    use HasUuids;
}
