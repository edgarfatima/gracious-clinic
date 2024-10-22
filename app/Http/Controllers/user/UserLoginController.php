<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserLoginController extends Controller
{
    public function create()
    {
        return view('user.login');
    }

    public function authenticate(Request $request)
    {
        // Validate the form inputs
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            // If the email exists, try to authenticate the user
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // Successful authentication, redirect to user dashboard
                return redirect()->route('user.dashboard');
            } else {
                // Password is incorrect
                return redirect()->route('user.login')
                    ->withErrors(['error' => 'Incorrect email or password.'])
                    ->withInput();
            }
        } else {
            // Validation failed, redirect back with validation errors
            return redirect()->route('user.login')
                ->withErrors($validator)
                ->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login');
    }

    public function authenticateMobile(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Log the attempted login details
        $validatedData = $validator->validated();
        Log::info('Login attempt for email: ' . $validatedData['email']);

        // Attempt to authenticate the user
        if (Auth::attempt($validatedData)) {
            // Get the authenticated user instance
            $user = Auth::user();

            // Log successful login
            Log::info('User logged in successfully: ' . $user->email);

            // Generate a token for the user using Sanctum
            $token = rand(100000, 999999);

            // Return a success response with the token
            return response()->json([
                'token' => $token,
            ], 200);
        } else {
            // Log failed login attempt
            Log::warning('Failed login attempt for email: ' . $validatedData['email']);

            // If authentication fails
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }
    }
}
