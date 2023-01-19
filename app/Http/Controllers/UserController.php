<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //  calling index method for login route

    public function index(Request $request)
    {
        // call login method with front-end request parameter

        return $this->login($request);
    }


    // Login method for authentication

    public function login($request)
    {

         // Check request email is exist or not in Data Base

        $user = User::where('email', $request->email)->first();

                // if user does not exist or email does not match
        if (!$user) {
            return response([
                'message' => ['These credential do not match our records']
            ], 404);
        }

            // if requested Password does not match our record

        if (!Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credential do not match our records']
            ], 404);
        }
            // Generate barear tokken for api authentaction

        $token = $user->createToken('my-app-token')->plainTextToken;


        // this return login  users and authentaction tokken

        $response = [
            'user' => $user,
            'token' => $token
        ];


        // this return success response with 201 code
        return response($response, 201);
    }

    //  this function return all the users in our records

    public function getUsers()
    {

        // get all users records

        $users = User::all();
        $response = [
            'users' => $users,
        ];

        // this return success response

        return response($response, 201);
    }
}
