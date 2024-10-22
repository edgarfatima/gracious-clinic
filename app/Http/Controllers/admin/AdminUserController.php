<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin.user', compact('users'));
    }

    public function populate()
    {
        $user = User::all();
        return response()->json($user);
    }

    public function fetch($id)
    {
        $data = User::find($id);
        return response()->json($data);
    }


    public function update(Request $request)
    {
        try {
            $data = User::findOrFail($request->id); // Ensure employee exists

            // Update employee attributes
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->email = $request->email;
            $data->number = $request->number;
            $data->address = $request->address;
            $data->status = $request->status;
            $data->role = $request->role;

            $data->save();

            // Return updated employee data as JSON response
            return response()->json($data);
        } catch (\Exception $e) {
            // Return an error response
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
