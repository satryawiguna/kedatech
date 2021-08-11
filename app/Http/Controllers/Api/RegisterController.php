<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Transformers\Auth\RegisterTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    //Register user as customer only
    public function actionRegister(Request $request)
    {
        $validatedRegister = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:255|email',
            'password' => ['required', 'confirmed', Password::min(8)]
        ]);

        if ($validatedRegister->fails()) {
            $messages = $validatedRegister->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ]);
        }

        $user = new User([
            'user_type_id' => 1,
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        $user->save();

        $user->userProfile()->save(new UserProfile([
            'full_name' => $request->input('full_name') ?? null,
            'nick_name' => $request->input('nick_name') ?? null,
            'phone' => $request->input('phone') ?? null
        ]));

        return fractal($user, new RegisterTransformer())->toArray();
    }
}
