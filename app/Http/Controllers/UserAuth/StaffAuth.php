<?php

namespace App\Http\Controllers\UserAuth;

use App\StaffUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffAuth extends Controller
{
    public function register(Request $request)
    {
        $user = new StaffUser;
        $user->full_name = $request->fullname;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->alias = $request->alias;
        $user->status = 1;
        $user->role_user_id = 1;
        $user->save();

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $accessToken
        ]);
    }

    public function login(Request $request)
    {
        $user = StaffUser::where("username", $request->credentials['user'])
        ->join('role_user', 'role_user.id', '=', 'staffuser.role_user_id')
        ->select('staffuser.*', 'role_user.slug as role')
        ->first();

        if(!isset($user)){
            return response()->json([
                'success' => false,
                'message' => 'Staff Not found'
            ]);
        }

        if (!Hash::check($request->credentials['pass'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password'
            ]);
        }

        $tokenResult = $user->createToken('authToken');
        $user->access_token = $tokenResult->accessToken;
        $user->token_type = 'Bearer';
        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'Login successfully'
        ]);
    }

    public function logout()
    {
        if (Auth::guard('staffuser')->user()) {
            $user = Auth::user()->token();
            $user->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to Logout'
            ]);
        }
    }
}
