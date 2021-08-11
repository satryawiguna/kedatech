<?php

namespace App\Http\Controllers\Api;

use App\Events\Broadcasting\ChatSendEvent;
use App\Http\Controllers\Controller;
use App\Transformers\Message\MessageTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    //View all chat history (staff)
    public function actionChatAll()
    {
        if (!Auth::user()->can('view', [Message::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ]);
        }

        $messages = Message::whereIn('channel', ['staff', 'customer'])
            ->get();

        return fractal()
            ->collection($messages)
            ->transformWith(new MessageTransformer())
            ->toArray();
    }

    //View own chat history (staff / customer)
    public function actionChatMe()
    {
        $userId = Auth::user()->id;

        if (Auth::user()->user_type_id != 2) {
            $init = Message::where('user_id', '=', $userId)->first();

            $messages = Message::where([
                ['created_at', '>=', $init->created_at],
                ['channel', '=', 'customer']
            ])
                ->get();
        } else {
            $init = Message::where('user_id', '=', $userId)->first();

            $messages = Message::where([
                ['created_at', '>=', $init->created_at]
            ])
                ->whereIn('channel', ['staff', 'customer'])
                ->get();
        }

        return fractal()
            ->collection($messages)
            ->transformWith(new MessageTransformer())
            ->toArray();
    }

    //Send message to other customer / staff
    public function actionChatSend(Request $request)
    {
        $channel = $request->input('channel');
        $userId = Auth::user()->id;

        if (!Auth::user()->can('send', [Message::class, $channel])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ]);
        }

        $validatedCustomerSend = Validator::make($request->all(), [
            'channel' => 'required',
            'messages' => 'required'
        ]);

        if ($validatedCustomerSend->fails()) {
            $messages = $validatedCustomerSend->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ]);
        }

        $message = new Message([
            "user_id" => $userId,
            "channel" => $channel,
            "messages" => $request->input('messages')
        ]);
        $message->save();

        $result = fractal($message, new MessageTransformer())
            ->toArray();

        broadcast(new ChatSendEvent($result, $channel))->toOthers();

        return $result;
    }
}
