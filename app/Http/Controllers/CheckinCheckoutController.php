<?php

namespace App\Http\Controllers;

use App\Models\CheckinCheckout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CheckinCheckoutController extends Controller
{
    public function checkIncheckOut()
    {
        return view('checkin-checkout', [
            'hash_qr_value' => Hash::make(date('Y-m-d')),
        ]);
    }

    public function checkIncheckoutHandler(Request $request)
    {
        if (now()->format('D') == 'Sat' || now()->format('D') == 'Sun') {
            return [
                'status' => 'fail',
                'message' => 'Today is Off Day!',
            ];
        }

        $user = User::where('pin_code', $request->pin_code)->first();

        if (!$user) {
            return [
                'status' => 'fail',
                'message' => 'Pin Code is Wrong!',
            ];
        }

        // firstOrCreate -> can allow two arrays first for condition, second for data

        $checkIn_checkOut_data = CheckinCheckout::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => now()->format('Y-m-d'),
            ]
        );

        if (!is_null($checkIn_checkOut_data->checkin_time) && !is_null($checkIn_checkOut_data->checkout_time)) {
            return [
                'status' => 'fail',
                'message' => 'Already Check In and Checkout for Today',
            ];
        }

        if (is_null($checkIn_checkOut_data->checkin_time)) {
            $checkIn_checkOut_data->checkin_time = now();
            $message = 'Successfully Check In at ' . now();
        } else {
            if (is_null($checkIn_checkOut_data->checkout_time)) {
                $checkIn_checkOut_data->checkout_time = now();
                $message = 'Successfully Checkout at ' . now();
            }
        }

        $checkIn_checkOut_data->update();

        return [
            'status' => 'success',
            'message' => $message,
        ];
    }
}
