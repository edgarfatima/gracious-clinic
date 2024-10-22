<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminEmployeeController extends Controller
{
    public function index()
    {
        return view('admin.employee');
    }

    public function populate(Request $request)
    {
        $query = Employee::query();

        if (isset($request->keyword)) {
            $query->where('employees', $request->keyword);
        }

        $employees = $query->paginate(10);

        return response()->json($employees->items());
    }
    public function fetch($id)
    {
        $data = Employee::find($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|unique:employees',
            'number' => 'required|unique:employees|min:11|max:11',
            'role' => ['required', 'string', 'in:admin,doctor,staff'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'password' => 'required',
            'confirm_password' => 'required|same:password',

        ], [
            'email.unique' => 'Email already exists.',
            'number.unique' => 'Phone number already taken.',
            'confirm_password.same' => 'The password confirmation does not match.',
        ]);

        if ($validator->passes()) {
            // Create a new employee record
            $employee = new Employee();
            $employee->first_name = Str::title($request->input('first_name'));
            $employee->last_name = Str::title($request->input('last_name'));
            $employee->email = strtolower($request->input('email'));
            $employee->number = $request->input('number');
            $employee->role = $request->input('role');
            $employee->status = $request->input('status');
            $employee->password = bcrypt($request->input('password'));

            $employee->save();

            return response()->json(['message' => 'Employee added successfully', 'employee' => $employee]);
        } else {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    }

    public function update(Request $request)
    {
        // Ensure employee exists
        $data = Employee::findOrFail($request->id);

        // Validation rules with custom error messages
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                Rule::unique('employees')->ignore($data->id),
            ],
            'number' => [
                'required',
                Rule::unique('employees')->ignore($data->id),
            ],
            'first_name' => 'required',
            'last_name' => 'required',
            'status' => 'required',
            'role' => 'required',
        ], [
            'email.unique' => 'Email already exists',
            'number.unique' => 'Phone number already exists.',
        ]);

        // Check if validation passes
        if ($validator->passes()) {
            // Update employee attributes
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->email = $request->email;
            $data->number = $request->number;
            $data->status = $request->status;
            $data->role = $request->role;

            $data->save();

            // Return updated employee data as JSON response
            return response()->json($data);
        } else {
            // Return validation errors as JSON
            return response()->json(['errors' => $validator->errors()], 422);
        }
    }
}
