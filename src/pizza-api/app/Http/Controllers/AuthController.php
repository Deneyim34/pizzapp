<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where("email",$request->username)->first();

        if(!$user)
        {
            return response()->json('Your credentials are incorrect. Please try again.',401);
        }

        $http = new \GuzzleHttp\Client(array(
            'cookies' => true
        ));


        try{
            $response = $http->post('http://api.pizzapp.test/oauth/token',[
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    'client_secret' => env("PASSPORT_SECRET"),
                    'username' => $request->username,
                    'password' => $request->password,
                    'scope' => ($user->role == 1 ? "customer" : "admin")
                ]
            ]);

            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e)
        {
            if($e->getCode() == 400)
            {
                return response()->json('E-Mail and / or password is incorrect.', $e->getCode());
            }
            else if($e->getCode() == 401)
            {
                return response()->json('Your credentials are incorrect. Please try again.', $e->getCode());
            }

            return response()->json('Something went wrong on the server.', $e->getCode());

        }
    }

    public function Register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required',
            'district_id' => 'required',
            'city_id' => 'required',
            'country_id' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $data = $request->all();

        $data["password"] = Hash::make($data["password"]);
        if(isset($data["role"]))
        {
            unset($data["role"]);
        }

        return response()->json(User::create($data),201);
    }

    public function Logout()
    {
        auth()->user()->tokens->each(function($token,$key){
            $token->delete();
        });

        return response()->json("ok",200);
    }

}
