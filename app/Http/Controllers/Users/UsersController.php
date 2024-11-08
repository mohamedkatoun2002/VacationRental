<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Booking\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //myBookings
    public function myBookings()
    {
        $bookings = Booking::select()->orderBy('id', 'desc')->where('user_id', Auth::user()->id)->get();

        return view('users.bookings', compact('bookings'));
    }
    //destroyMyBooking
    public function destroyMyBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->back()->with('success', 'Booking deleted successfully');
    }
}
