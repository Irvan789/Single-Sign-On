<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function updateOrCreate(array $data, ?array $existing = []): User
    {
        return User::updateOrCreate(
            $existing,
            $data
        );
    }

    public function updateById(string $id, array $data): void
    {
        $user = User::findOrFail($id);

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    public function findAll(string $id, string $search): LengthAwarePaginator
    {
        return User::where('id', '<>', $id)
            ->with('socials')
            ->when($search, function ($query, $search) {
                $query->whereFullText('name', $search);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)->onEachSide(1);
    }

    public function findById(string $id): User
    {
        return User::findOrFail($id);
    }

    public function findByEmail(string $email): User
    {
        return User::where([
            'email' => $email,
        ])->firstOrFail();
    }

    public function delete(User $user): void
    {
        $user->oauthApps()->delete();

        $user->socials()->delete();

        $user->delete();
    }
}
