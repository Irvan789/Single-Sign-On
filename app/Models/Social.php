<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'user_id', 'provider', 'provider_id'])]
class Social extends Model
{
    use HasUuids;

    #[Scope]
    protected function getUserBySocialAccountId(Builder $query, string $provider, string $id): void
    {
        $query->where([
            'provider' => $provider,
            'provider_id' => $id
        ]);
    }

    #[Scope]
    protected function getUserBySocialAccountEmail(Builder $query, string $provider, string $email): void
    {
        $query->where([
            'provider' => $provider,
            'email' => $email
        ]);
    }

    #[Scope]
    protected function getUserBySocialUserId(Builder $query, string $provider, string $id): void
    {
        $query->where([
            'provider' => $provider,
            'user_id' => $id
        ]);
    }
}
