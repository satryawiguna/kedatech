<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Transformers\Auth\RegisterTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    //Register user as staff / customer
    public function actionRegister(Request $request)
    {
        //Permitted to staff only
        if (!Auth::user()->can('create', [User::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ]);
        }

        $validatedRegister = Validator::make($request->all(), [
            'user_type_id' => 'required|integer',
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
            'user_type_id' => $request->input('user_type_id'),
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

    //Login user
    public function actionLogin(Request $request)
    {
        $validatedLogin = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validatedLogin->fails()) {
            $messages = $validatedLogin->messages();

            return response()->json([
                'code_status' => 500,
                'messages' => $messages->all()
            ]);
        }

        if (!Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            return response()->json([
                'code_status' => 500,
                'messages' => 'Credential doesn\'t match'
            ]);
        }

        $user = Auth::user();
        $responseLogin = [
            'token' => $user->createToken('key:' . $user->id)->accessToken,
            'email' => $user->email,
            'full_name' => $user->userProfile->full_name ?? null,
            'nick_name' => $user->userProfile->nick_name  ?? null,
            'phone' => $user->userProfile->phone  ?? null
        ];

        return response()->json($responseLogin);
    }

    //Logout user logged
    public function actionLogout(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if (!$user)
            return response()->json([
                'code_status' => 500,
                'messages' => 'Logout not success'
            ]);

        $oAuthAccessTokens = $user->oAuthAccessTokens();
        $oAuthAccessTokens->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Logout success'
        ]);
    }
}
