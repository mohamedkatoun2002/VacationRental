<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Admin;
use App\Models\Hotel\Hotel;
use App\Models\Apartment\Apartment;
use App\Models\Booking\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;

class AdminsController extends Controller
{
    //viewLogin
    public function viewLogin()
    {
        return view('admins.login');
    }

    //checkLogin
    public function checkLogin(Request $request)
    {
        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {

            return redirect()->route('admins.dashboard');
        }
        return redirect()->back()->with(['error' => 'error logging in']);
    }

    //index
    public function index()
    {
        $adminsCount = Admin::select()->count();
        $hotelsCount = Hotel::select()->count();
        $roomsCount = Apartment::select()->count();
        return view('admins.index', compact('adminsCount', 'hotelsCount', 'roomsCount'));
    }

    //allAdmins
    public function allAdmins()
    {
        $admins = Admin::select()->orderBy('id', 'desc')->get();
        return view('admins.alladmins', compact('admins'));
    }

    //createAdmins
    public function createAdmins(Request $request)
    {
        return view('admins.createadmins');
    }

    //storeAdmins
    public function storeAdmins(Request $request)
    {

        $storeAdmins = Admin::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);
        if ($storeAdmins) {
            return Redirect::route('admins.all')->with(['success' => 'admin created successfully']);
        }
    }


    //allHotels
    public function allHotels()
    {
        $hotels = Hotel::select()->orderBy('id', 'desc')->get();
        return view('admins.allhotels', compact('hotels'));
    }

    //createHotels
    public function createHotels(Request $request)
    {
        return view('admins.createhotels');
    }

    //storeHotels
    public function storeHotels(Request $request)
    {
        Request()->validate([
            'name' => 'required|max:255',
            'image' => 'required | image | max: 2048',
            'description' => 'required',
            'location' => 'required | max: 255',
        ]);


        $destinationPath = 'assets/images/';
        $myimage = $request->image->getClientOriginalName();
        $request->image->move(public_path($destinationPath), $myimage);


        $storeHotels = Hotel::create([
            "name" => $request->name,
            "image" => $myimage,
            "description" => $request->description,
            "location" => $request->location,

        ]);
        if ($storeHotels) {
            return Redirect::route('hotels.all')->with(['success' => 'Hotel created successfully']);
        }
    }

    //editHotels
    public function editHotels($id)
    {
        $hotels = Hotel::find($id);
        return view('admins.edithotels', compact('hotels'));
    }


    //updateHotels
    public function updateHotels(Request $request, $id)
    {

        Request()->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'location' => 'required | max: 255',
        ]);

        // $updateHotels = Hotel::where('id', $id)->update([
        //     "name" => $request->name,
        //     "description" => $request->description,
        //     "location" => $request->location,
        // ]);

        $hotels = Hotel::find($id);
        $hotels->update($request->all());

        if ($hotels) {
            return Redirect::route('hotels.all')->with(['update' => 'Hotel updated successfully']);
        }
    }

    //deleteHotels
    public function deleteHotels($id)
    {
        $deleteHotels = Hotel::find($id);

        if (File::exists(public_path('assets/images/' . $deleteHotels->image))) {
            File::delete(public_path('assets/images/' . $deleteHotels->image));
        } else {
            //dd('File does not exists.');
        }

        $deleteHotels->delete();
        if ($deleteHotels) {
            return Redirect::route('hotels.all')->with(['delete' => 'Hotel deleted successfully']);
        }
    }

    //allRooms
    public function allRooms()
    {
        $rooms = Apartment::select()->orderBy('id', 'desc')->get();
        return view('admins.allrooms', compact('rooms'));
    }

    //createRooms
    public function createRooms(Request $request)
    {
        $hotels = Hotel::all();
        return view('admins.createrooms', compact('hotels'));
    }


    //storeRooms
    public function storeRooms(Request $request)
    {
        Request()->validate([
            'name' => 'required|max:255',
            'image' => 'required | image | max: 2048',
            'max_persons' => 'required',
            'size' => 'required',
            'view' => 'required',
            'num_beds' => 'required',
            'price' => 'required',
            'hotel_id' => 'required',
        ]);

        $destinationPath = 'assets/images/';
        $myimage = $request->image->getClientOriginalName();
        $request->image->move(public_path($destinationPath), $myimage);

        $storeRooms = Apartment::create([
            "name" => $request->name,
            "image" => $myimage,
            "max_persons" => $request->max_persons,
            "size" => $request->size,
            "view" => $request->view,
            "num_beds" => $request->num_beds,
            "price" => $request->price,
            "hotel_id" => $request->hotel_id,
        ]);
        if ($storeRooms) {
            return Redirect::route('rooms.all')->with(['success' => 'Room created successfully']);
        }
    }

    //deleteRooms
    public function deleteRooms($id)
    {
        $deleteRooms = Apartment::find($id);
        if (File::exists(public_path('assets/images/' . $deleteRooms->image))) {
            File::delete(public_path('assets/images/' . $deleteRooms->image));
        } else {
            //dd('File does not exists.');
        }
        $deleteRooms->delete();
        if ($deleteRooms) {
            return Redirect::route('rooms.all')->with(['delete' => 'Room deleted successfully']);
        }
    }


    //allBookings
    public function allBookings()
    {
        $bookings = Booking::select()->orderBy('id', 'desc')->get();
        return view('admins.allbookings', compact('bookings'));
    }

    //editBookings
    public function editStatus($id)
    {
        $bookings = Booking::find($id);
        return view('admins.editstatus', compact('bookings'));
    }

    //updateStatus
    public function updateStatus(Request $request, $id)
    {


        $bookings = Booking::find($id);
        $bookings->update($request->all());
        if ($bookings) {
            return Redirect::route('bookings.all')->with(['update' => 'Status updated successfully']);
        }
    }

    //deleteBookings
    public function deleteBookings($id)
    {
        $deleteBookings = Booking::find($id);
        $deleteBookings->delete();
        if ($deleteBookings) {
            return Redirect::route('bookings.all')->with(['delete' => 'Booking deleted successfully']);
        }
    }
}
