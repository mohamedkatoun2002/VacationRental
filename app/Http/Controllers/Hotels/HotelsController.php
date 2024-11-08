<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apartment\Apartment;
use App\Models\Booking\Booking;
use App\Models\Hotel\Hotel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use DateTime;

class HotelsController extends Controller
{
    public function rooms($id)
    {
        $getrooms = Apartment::select()->orderBy('id', 'desc')->take(6)
            ->where('hotel_id', $id)->get();
        return view('hotels.rooms', compact('getrooms'));
    }


    //roomDetails
    public function roomDetails($id)
    {
        $roomDetails = Apartment::find($id);
        return view('hotels.roomdetails', compact('roomDetails'));
    }

    //roomBooking
    public function roomBooking(Request $request, $id)
    {
        $room = Apartment::find($id);
        $hotel = Hotel::find($id);

        // Check if room and hotel exist
        if (!$room || !$hotel) {
            // echo "Room or Hotel not found.";
            return response()->json(['error' => 'Room or Hotel not found.'], 404);
            return;
        }

        // Ensure check-in and check-out dates are valid
        if (strtotime($request->check_in) > strtotime(date("Y-m-d")) && strtotime($request->check_out) > strtotime(date("Y-m-d"))) {
            if (strtotime($request->check_in) < strtotime($request->check_out)) {
                // Calculate the number of days for the booking
                $datetime1 = new DateTime($request->check_in);
                $datetime2 = new DateTime($request->check_out);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a'); // Number of days

                // Create booking record
                Booking::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'check_in' => $request->check_in,
                    'check_out' => $request->check_out,
                    'days' => $days,
                    'price' => $days * $room->price,
                    'user_id' => Auth::user()->id,
                    'room_name' => $room->name,
                    'hotel_name' => $hotel->name,
                ]);


                $totalPrice = $days * $room->price;
                $price = Session::put('price', $totalPrice);
                $getPrice = Session::get($price);

                return Redirect::route('hotel.pay');
            } else {
                // Error if check-in is not before check-out
                return Redirect::route('hotel.rooms.details', $room->id)->with(['error' => 'Invalid Check-Out Date should be greater than Check-In Date']);
            }
        } else {
            // Error if dates are not in the future
            return Redirect::route('hotel.rooms.details', $room->id)->with(['error_dates' => 'Choose dates in the future, Invalid Check-In Date or Check-Out Date']);
        }
    }


    //payWithPaypal
    public function payWithPaypal()
    {
        return view('hotels.pay');
    }

    //success
    public function success()
    {
        Session::forget('price');
        return view('hotels.success');
    }
}
