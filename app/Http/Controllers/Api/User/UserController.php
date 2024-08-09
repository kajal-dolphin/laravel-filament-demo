<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::get();
            return uniResponse(true, 'Users retrieved successfully', $users, 200);
        } catch (Exception $e) {
            $errorData['message'] = 'Internal Server Error';
            $errorData['hint'] = $e->getMessage();
            logError($e, 500);
            return uniResponse(false, $errorData, '', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $userData = $request->validated();
            $userData['password'] = Hash::make($userData['password']);
            $user = User::create($userData);
            return uniResponse(true, 'User created successfully', $user, 201);
        } catch (Exception $e) {
            $errorData['message'] = 'Internal Server Error';
            $errorData['hint'] = $e->getMessage();
            logError($e, 500);
            return uniResponse(false, $errorData, '', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if (!$user) {
                return uniResponse(false, 'User not found', '', 404);
            }
            return uniResponse(true, 'User retrieved successfully', $user, 200);
        } catch (Exception $e) {
            $errorData['message'] = 'Internal Server Error';
            $errorData['hint'] = $e->getMessage();
            logError($e, 500);
            return uniResponse(false, $errorData, '', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request,$id)
    {
        try {
            $user = User::where('id', $id)->first();
            if (!$user) {
                return uniResponse(false, 'User not found', '', 404);
            }
            $userData = $request->validated();
            $userData['password'] = Hash::make($userData['password']);
            $user->update($userData);
            return uniResponse(true, 'User updated successfully', $user, 200);
        } catch (Exception $e) {
            $errorData['message'] = 'Internal Server Error';
            $errorData['hint'] = $e->getMessage();
            logError($e, 500);
            return uniResponse(false, $errorData, '', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::where('id', $id)->first();
            if (!$user) {
                return uniResponse(false, 'User not found', '', 404);
            }
            $user->delete();
            return uniResponse(true, 'User deleted successfully', '', 200);
        } catch (Exception $e) {
            $errorData['message'] = 'Internal Server Error';
            $errorData['hint'] = $e->getMessage();
            logError($e, 500);
            return uniResponse(false, $errorData, '', 500);
        }
    }
}
