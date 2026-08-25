<?php

namespace App\Services;

use App\Livewire\Actions\Logout;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function profile(User $user): User
    {
        return $user->load('socials')
            ->setRelation('socials', $user->socials->keyBy('provider'));
    }

    public function findAll(User $user, string $search): LengthAwarePaginator
    {
        return $this->userRepository->findAll($user->id, $search);
    }

    public function findById(string $id): User
    {
        $user = $this->userRepository->findById($id)->load('socials');

        return $user->setRelation('socials', $user->socials->keyBy('provider'));
    }

    public function findByEmail(string $email): User
    {
        $user = $this->userRepository->findByEmail($email)->load('socials');

        return $user->setRelation('socials', $user->socials->keyBy('provider'));
    }

    public function updateById(string $id, array $data): void
    {
        $this->userRepository->updateById($id, $data);
    }

    public function delete(User $user, Logout $logout): void
    {
        /** @var User $user */
        $user = tap($user, $logout(...));
        $this->userRepository->delete($user);
    }

    public function deleteByUser(User $user): void
    {
        $this->userRepository->delete($user);
    }
}
