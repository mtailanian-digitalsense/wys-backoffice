<?php

namespace App\Services;

use App\Models\User;
use App\Models\LinkedSocialAccount;
use Laravel\Socialite\Two\User as ProviderUser;

class SocialAccountsService
{
    /**
     * Find or create user instance by provider user instance and provider name.
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     *
     * @return User
     */
    public function findOrCreate(ProviderUser $providerUser, string $provider): User
    {
        $linkedSocialAccount = LinkedSocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if ($linkedSocialAccount) {
            return $linkedSocialAccount->user;
        } else {
            $user = null;

            if ($email = $providerUser->getEmail()) {
                $user = User::where('email', $email)->first();
            }

            if (!$user) {
                $first_name = null;
                $last_name = null;
                switch ($provider) {
                    case LinkedSocialAccount::SERVICE_FACEBOOK:
                        $first_name = $providerUser->user['first_name'];
                        $last_name = $providerUser->user['last_name'];
                        break;
                    case LinkedSocialAccount::SERVICE_GOOGLE:
                        $first_name = $providerUser->user['given_name'];
                        $last_name = $providerUser->user['family_name'];
                        break;
                    case LinkedSocialAccount::SERVICE_LINKEDIN:
                        $first_name = $providerUser->first_name;
                        $last_name = $providerUser->last_name;
                        break;

                    default :
                }
                $user = User::create([
                    'name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $providerUser->getEmail(),
                ]);
            }

            $user->linkedSocialAccounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);

            return $user;
        }
    }
}
