<?php

namespace App\Transformers\Membership;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserProfileTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            "email" => $user->email,
            "full_name" => $user->userProfile->full_name ?? null,
            "nick_name" => $user->userProfile->nick_name ?? null,
            "phone" => $user->userProfile->phone ?? null
        ];
    }
}
