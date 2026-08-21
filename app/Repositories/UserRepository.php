<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function updateOrCreateByEmail(array $data, string $email): User
    {
        return User::updateOrCreate(
            ['email' => $email],
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

    public function findByEmail(string $email): ?User
    {
        return User::firstWhere([
            'email' => $email,
        ]);
    }

    public function delete(User $user): void
    {
        $user->oauthApps()->delete();

        $user->socials()->delete();

        $user->delete();
    }
}
