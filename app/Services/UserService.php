<?php

namespace App\Services;

use App\Livewire\Actions\Logout;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function __construct(
        protected ?User $user,
        protected UserRepository $userRepository
    ) {
        $this->user = Auth::user();
    }

    public function profile(): User
    {
        return $this->user->load('socials')
            ->setRelation('socials', $this->user->socials->keyBy('provider'));
    }

    public function updateOrCreateByEmail(array $data, string $email): User
    {
        return $this->userRepository->updateOrCreateByEmail($data, $email);
    }

    public function findAll(string $search): LengthAwarePaginator
    {
        return $this->userRepository->findAll($this->user->id, $search);
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

    public function delete(Logout $logout): void
    {
        /** @var User $user */
        $user = tap($this->user, $logout(...));
        $this->userRepository->delete($user);
    }

    public function deleteByUser(User $user): void
    {
        $this->userRepository->delete($user);
    }
}
