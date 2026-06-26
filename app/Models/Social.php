<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'email', 'user_id', 'provider', 'provider_id'])]
class Social extends Model
{
    use HasUuids;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    #[Scope]
    protected function whereSocialiteId(Builder $query, string $provider, string $id): Builder
    {
        return $query->where([
            'provider' => $provider,
            'provider_id' => $id
        ]);
    }

    #[Scope]
    protected function whereSocialiteEmail(Builder $query, string $provider, string $email): Builder
    {
        return $query->where([
            'provider' => $provider,
            'email' => $email
        ]);
    }

    #[Scope]
    protected function whereUserId(Builder $query, string $provider, string $id): Builder
    {
        return $query->where([
            'provider' => $provider,
            'user_id' => $id
        ]);
    }
}
