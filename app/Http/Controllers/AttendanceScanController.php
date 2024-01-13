<?php

namespace App\Http\Controllers;

use App\Models\CheckinCheckout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AttendanceScanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('attendance_scan');
    }

    public function store(Request $request)
    {
        if (!Hash::check(date('Y-m-d'), $request->hash_qr_value)) {
            return [
                'status' => 'fail',
                'message' => 'QR Code is Invalid!',
            ];
        }

        $user = auth()->user();

        $attendance = CheckinCheckout::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => now()->format('Y-m-d'),
            ]
        );

        if (!is_null($attendance->checkin_time) && !is_null($attendance->checkout_time)) {
            return [
                'status' => 'fail',
                'message' => 'Already Check In and Checkout for Today',
            ];
        }

        if (is_null($attendance->checkin_time)) {
            $attendance->checkin_time = now();
            $message = 'Successfully Checkin at ' . now();
        } else {
            if (is_null($attendance->checkout_time)) {
                $attendance->checkout_time = now();
                $message = 'Successfully Checkout at ' . now();
            }
        }

        $attendance->update();

        return [
            'status' => 'success',
            'message' => $message,
        ];
    }
}
