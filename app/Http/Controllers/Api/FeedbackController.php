<?php

namespace App\Http\Controllers\Api;

use App\Events\Broadcasting\FeedbackSendEvent;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Transformers\Feedback\FeedbackTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function actionFeedbackAll()
    {
        if (!Auth::user()->can('view', [Feedback::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ]);
        }

        $feedbacks = Feedback::all();

        return fractal($feedbacks, new FeedbackTransformer())
            ->toArray();
    }

    //Send feedback to staff for customer only
    public function actionFeedbackSend(Request $request)
    {
        if (!Auth::user()->can('send', [Feedback::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ]);
        }

        $userId = Auth::user()->id;

        $validatedFeedbackSend = Validator::make($request->all(), [
            'report' => 'required'
        ]);

        if ($validatedFeedbackSend->fails()) {
            $messages = $validatedFeedbackSend->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ]);
        }

        $feedback = new Feedback([
            "user_id" => $userId,
            "report" => $request->input('report')
        ]);
        $feedback->save();

        $result = fractal($feedback, new FeedbackTransformer())
            ->toArray();

        broadcast(new FeedbackSendEvent($result))->toOthers();

        return $result;
    }
}
