<?php

namespace App\Transformers\Message;

use App\Models\Message;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Message $message)
    {
        return [
            "user" => [
                "email" => $message->user->email,
                "type" => $message->user->userType->name,
                "full_name" => $message->user->userProfile->full_name ?? null,
                "nick_name" => $message->user->userProfile->nick_name ?? null
                ],
            "message" => $message->messages,
            "created_at" => $message->created_at->diffforhumans()
        ];
    }
}
