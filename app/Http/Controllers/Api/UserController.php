<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Transformers\Membership\UserProfileTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpKernel\Profiler\Profile;

class UserController extends Controller
{
    //View all customer
    public function actionCustomerAll()
    {
        //Permitted to staff only
        if (!Auth::user()->can('view', [User::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ]);
        }

        $users = User::where('user_type_id', 1)
            ->orderBy('id', 'DESC')
            ->get();

        return fractal($users, new UserProfileTransformer())->toArray();
    }

    //Delete customer
    public function actionCustomerDestroy(int $id)
    {
        if (!Auth::user()->can('delete', [User::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ]);
        }

        User::find($id)->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Customer was delete'
        ]);
    }

    //Delete customers
    public function actionCustomersDestroy(Request $request)
    {
        if (!Auth::user()->can('delete', [User::class])) {
            return response()->json([
                'code_status' => 403,
                'messages' => 'Forbidden access'
            ]);
        }

        $userIds = $request->input('ids');

        User::whereIn('id', explode(',', $userIds))->delete();

        return response()->json([
            'code_status' => 200,
            'messages' => 'Customers was delete'
        ]);
    }

    //Update user profile
    public function actionProfileUpdate(Request $request)
    {
        $userId = Auth::user()->id;

        if ($request->input('new_password')) {
            $validatedUser = Validator::make($request->all(), [
                'new_password' => ['confirmed', Password::min(8)]
            ]);

            if ($validatedUser->fails()) {
                $messages = $validatedUser->messages();

                return response()->json([
                    'code_status' => 500,
                    'messages' => $messages->all()
                ]);
            }
        }

        $user = User::find($userId);

        $user->password = ($request->input('new_password')) ?
            bcrypt($request->input('new_password')) :
            $user->password;

        $user->save();

        $profile = UserProfile::updateOrCreate([
            'user_id' => $userId
        ], [
            'full_name' => $request->input('full_name'),
            'nick_name' => $request->input('nick_name'),
            'phone' => $request->input('phone')
        ]);

        $profile->save();

        return fractal($user, new UserProfileTransformer())->toArray();
    }
}
