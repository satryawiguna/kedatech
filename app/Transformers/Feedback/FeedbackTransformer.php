<?php

namespace App\Transformers\Feedback;

use App\Models\Feedback;
use League\Fractal\TransformerAbstract;

class FeedbackTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Feedback $feedback)
    {
        return [
            "user" => [
                "email" => $feedback->user->email,
                "type" => $feedback->user->userType->name,
                "full_name" => $feedback->user->userProfile->full_name ?? null,
                "nick_name" => $feedback->user->userProfile->nick_name ?? null
            ],
            "report" => $feedback->report,
            "status" => $feedback->status,
            "created_at" => $feedback->created_at->diffforhumans()
        ];
    }
}
