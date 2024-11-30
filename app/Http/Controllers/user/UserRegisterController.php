<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\City;

class UserRegisterController extends Controller
{
    public function create()
    {
        return view('user.register');
    }

    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:18',
            'number' => 'required|unique:users|min:10|max:11',
            'username' => [
                'required',
                'unique:users',
                'alpha_num',
                'min:5',
                'max:20',
            ],
            'street_address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'country' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
            'confirm_password' => 'required|same:password',
            'accepted' => 'accepted',
        ], [
            'age.min' => 'You must be at least 18 years old to register.',
            'number.unique' => 'Phone number already exists.',
            'username.unique' => 'Username already exists.',
            'accepted.accepted' => 'You must accept our terms and conditions.',
            'confirm_password.same' => 'The password confirmation does not match.',
            'username.alpha_num' => 'Username must only contain letters and numbers.',
            'username.min' => 'Username must be at least 5 characters long.',
            'username.max' => 'Username cannot be longer than 20 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit, one special character, and be at least 8 characters long.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = new User();
        $number = $request->input('number');
        $user->first_name = Str::title($request->input('first_name'));
        $user->last_name = Str::title($request->input('last_name'));
        $user->age = $request->input('age');
        $user->number = $number;
        $user->street_address = $request->input('street_address');
        $user->province = $request->input('province');
        $user->city = $request->input('city');
        $user->country = $request->input('country');
        $user->username = $request->input('username');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful!',
            "number" => $number
        ]);
    }

    public function populateCities($provinceId)
    {
        $cities = City::where('province_id', $provinceId)->get(['id', 'name']);

        return response()->json($cities);
    }
}
