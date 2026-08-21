<?php

namespace App\Repositories;

use App\Models\Social;

class SocialRepository
{
    public function updateOrCreate(array $data, ?string $id = null, ?string $email = null): Social
    {
        return Social::updateOrCreate(
            $id ? ['provider_id' => $id] : ['email' => $email],
            $data
        );
    }

    public function findByProviderId(string $id): ?Social
    {
        return Social::firstWhere('provider_id', $id);
    }

    public function findByProviderNameAndUserId(string $provider, string $id): ?Social
    {
        return Social::firstWhere([
            'provider' => $provider,
            'user_id' => $id,
        ]);
    }

    public function findByProviderNameAndEmail(string $provider, string $email): ?Social
    {
        return Social::firstWhere([
            'provider' => $provider,
            'email' => $email,
        ]);
    }
}
