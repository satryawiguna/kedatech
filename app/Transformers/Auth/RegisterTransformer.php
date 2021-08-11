<?php

namespace App\Transformers\Auth;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class RegisterTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'email' => $user->email,
            'full_name' => $user->userProfile->full_name,
            'nick_name' => $user->userProfile->nick_name
        ];
    }
}
