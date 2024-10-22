<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAppointmentController extends Controller
{
    public function viewPending()
    {
        return view('staff.appointment-pending');
    }

    public function viewList()
    {
        return view('staff.appointment-list');
    }

    public function populatePendingAppointment()
    {
        $appointments = Appointment::where('status', 'pending')
            ->with('user') // Load user (patient) relationship
            ->get();

        // Transform appointments to include patient name
        $appointmentsWithPatientName = $appointments->map(function ($appointment) {
            $fullName = $appointment->user ? $appointment->user->first_name . " " . $appointment->user->last_name : null;

            return [
                'id' => $appointment->id,
                'name' => $fullName,
                'appointment_date' => $appointment->appointment_date, // Corrected 'appointment_data' to 'appointment_date'
                'status' => $appointment->status,
                'created_at' => $appointment->created_at,
                // Include other appointment attributes as needed
            ];
        });

        return response()->json($appointmentsWithPatientName);
    }

    public function populateAppointmentList()
    {
        // Get appointments except those with status 'pending', sorted by appointment_date
        $appointments = Appointment::where('status', '!=', 'pending')
            ->with('user') // Load user (patient) relationship
            ->orderBy('appointment_date', 'desc') // Sort by appointment_date in ascending order
            ->get();

        // Transform appointments to include patient name
        $appointmentsWithPatientName = $appointments->map(function ($appointment) {
            $fullName = $appointment->user ? $appointment->user->first_name . " " . $appointment->user->last_name : null;

            return [
                'id' => $appointment->id,
                'name' => $fullName,
                'appointment_date' => $appointment->appointment_date,
                'status' => $appointment->status,
                'created_at' => $appointment->created_at,
                // Include other appointment attributes as needed
            ];
        });

        return response()->json($appointmentsWithPatientName);
    }

    public function fetch($id)
    {
        $data = Appointment::find($id);
        return response()->json($data);
    }

    public function confirm(Request $request)
    {
        // Ensure the appointment exists
        $appointment = Appointment::findOrFail($request->id);

        // No need to validate the 'id' field since findOrFail() handles it
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
            'id.required' => 'ID does not exist',
        ]);

        // Check if validation passes
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return response()->json(false, 422);
        }

        $appointment->status = 'confirmed';
        $appointment->save();

        // Return success response with updated appointment data
        return response()->json(true, 200);
    }

    public function reject(Request $request)
    {
        // Ensure the appointment exists
        $appointment = Appointment::findOrFail($request->id);

        // No need to validate the 'id' field since findOrFail() handles it
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
            'id.required' => 'ID does not exist',
        ]);

        // Check if validation passes
        if ($validator->fails()) {
            // Return validation errors as JSON response
            return response()->json(false, 422);
        }


        $appointment->status = 'rejected';
        $appointment->save();

        // Return success response with updated appointment data
        return response()->json(true, 200);
    }
}
