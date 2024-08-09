<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return uniResponse(false, 'Unauthorized', '', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return uniResponse(true,'User Login successfully',
            [
                'token' => $token,
                'user' => $user,
            ],
            200
        );
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $registerUserData = $request->validated();
            $registerUserData['password'] = Hash::make($registerUserData['password']);
            $user = User::create($registerUserData);

            DB::commit();
            return uniResponse(true,'User Registered successfully',
                [
                    'user' => $user,
                ],
                200,
            );
        } catch (Exception $e) {
            DB::rollback();
            $errorData['message'] = 'Internal Server Error';
            $errorData['hint'] = $e->getMessage();
            logError($e, 500);
            return uniResponse(false, $errorData, '', 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                $currentAccessToken = $user->currentAccessToken();
                if ($currentAccessToken) {
                    $currentAccessToken->delete();
                    return uniResponse(true, "User Logged out successfully", null, 200);
                }
            } else {
                return uniResponse(false, "User not authenticated", null, 401);
            }
        } catch (\Exception $e) {
            logError($e, 500);
            $errorData['message'] = 'Internal Server Error';
            $errorData['hint'] = $e->getMessage();
            return uniResponse(false, $errorData, '', 500);
        }
    }
}
