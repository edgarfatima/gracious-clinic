<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;

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
            'number' => 'required|unique:users|min:11|max:11',
            'street_address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'country' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'accepted' => 'accepted',
        ], [
            'number.unique' => 'Phone number already existed.',
            'email.unique' => 'Email already existed.',
            'accepted.accepted' => 'You must accept our terms and conditions.',
            'confirm_password.same' => 'The password confirmation does not match.',
        ]);

        if ($validator->passes()) {

            $user = new User();

            $user->first_name = Str::title($request->input('first_name'));
            $user->last_name = Str::title($request->input('last_name'));
            $user->number = $request->input('number');
            $user->street_address = $request->input('street_address');
            $user->city = $request->input('city');
            $user->province = $request->input('province');
            $user->country = $request->input('country');
            $user->email = strtolower($request->input('email'));
            $user->password = bcrypt($request->input('password'));
            $user->save();

            return redirect()->back()->with('success', 'Registration successful. Please log in.');
        } else {
            return redirect()->route('user.register')->withErrors($validator)->withInput();
        }
    }
}
