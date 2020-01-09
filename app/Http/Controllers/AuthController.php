<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterUserRequest $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->contract_start_date = $request->input('contract_start_date');
        $user->contract_end_date = $request->input('contract_end_date');
        $user->type = $request->input('type');
        $user->verified = $request->input('verified');
        $user->save();

        $access_token = $user->createToken('Laravel Password Grant Client')->accessToken;

        $response = [
            'token' => $access_token,
            'user' => $user
        ];

        return $this->out($response);
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (! auth()->attempt($credentials)) {
            return $this->outWithErrors(['error' => 'Unauthorized'], 401, 'Unauthorized');
        }

        $user = User::where('email', $request->input('email'))->first();
        $access_token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return $this->out(['token' => $access_token], 200);
    }

    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        if(auth()->check()) {
            auth()->user()->token()->revoke();
            return $this->out(['success' =>'Loged out successfully!'],200);
        } else {
            return $this->outWithErrors(['error' =>'Something went wrong!'], 500);
        }
    }

    /**
     * Get logged user api
     *
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        return $this->out(auth()->user());
    }
}