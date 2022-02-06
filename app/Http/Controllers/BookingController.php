<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Mail\MailNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\MrbsAttendee;
use App\Models\MrbsBooking;

class BookingController extends Controller
{
    public function getMyCalendar(Request $request)
    {
        $bookings = \App\Models\MrbsBooking::get(['id','start_datetime AS start', 'end_datetime AS end', 'description AS title']);
        return $bookings;
    }

    public function getData(Request $request)
    {
        // dddd($request->all);
        $bookings = \App\Models\MrbsBooking::with(['room', 'attendees'])->paginate(1);
        // dddd($request);
        return $bookings;
    }

    public function CreateBooking(Request $request)
    {
        $attendees = $request->attendee_id;

        $booked = \App\Models\MrbsBooking::where('room_id', $request->room_id)
                ->where('start_datetime', '<=', \Carbon\Carbon::parse($request->end_datetime)->format('Y-m-d H:i:s'))
                ->Where('end_datetime', '>=', \Carbon\Carbon::parse($request->start_datetime)->format('Y-m-d H:i:s'))
                ->count();      

        if($booked > 0){
            $notifications = json_encode([['type' => 'error', 'message' => 'Guest room is not available for this date time, please try other guest room.']]);
            return  response()->json('', 422)->header('x-notification', $notifications)->header('Access-Control-Allow-Origin', '*');
         }

        $booking = \App\Models\MrbsBooking::create([
            'room_id' => $request->room_id,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'description' => $request->description,
        ]);
        
        $bookingAttendee = $booking->attendees()->attach($attendees);

        $notifications = json_encode([['type' => 'success', 'message' => 'Guest room is booked']]);
        return  response()->json('', 200)->header('x-notification', $notifications)->header('Access-Control-Allow-Origin', '*');
    }

    public function edit($id)
    {
        $booking = \App\Models\MrbsBooking::find($id);
        return $booking;
    }

    public function update(Request $request, $id)
    {
        $attendees = $request->attendee_id;

        $booking = \App\Models\MrbsBooking::find($id);

        $booked = \App\Models\MrbsBooking::where('room_id', $request->room_id)
                ->where('start_datetime', '<=', \Carbon\Carbon::parse($request->end_datetime)->format('Y-m-d H:i:s'))
                ->Where('end_datetime', '>=', \Carbon\Carbon::parse($request->start_datetime)->format('Y-m-d H:i:s'))
                ->count();      

        if($booked > 0){
            $notifications = json_encode([['type' => 'error', 'message' => 'Guest room is not available for this date time, please try other guest room.']]);
            return  response()->json('', 422)->header('x-notification', $notifications)->header('Access-Control-Allow-Origin', '*');
         }

        $updated = $booking->fill([
            'room_id' => $request->room_id,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'description' => $request->description
        ])->save();

        $booking = \App\Models\MrbsBooking::find($id);

        $bookingAttendee = $booking->attendees()->sync($attendees);

        $notifications = json_encode([['type' => 'success', 'message' => 'Guest room booking is updated']]);
        return  response()->json('', 200)->header('x-notification', $notifications)->header('Access-Control-Allow-Origin', '*');
    }

    public function mailList($id)
    {
        $staffs = \App\Models\MrbsBooking::find($id)->attendees()->get(['name', 'mobile_no', 'email']); 
        return $staffs;
    }

    public function sendMail(Request $request) {

        $booking = \App\Models\MrbsBooking::find($request->mrbs_booking_id);
        $booking = $booking->load('room');

        if ($request->has('mrbs_attendee_id')) {
            $user = MrbsAttendee::findOrFail($request->mrbs_attendee_id);
        }

        Mail::to($user['email'])->send(new MailNotification($booking, $user));

        $notifications = json_encode([['type' => 'success', 'message' => 'Mail has been sent']]);
        return  response()->json('', 200)->header('x-notification', $notifications)->header('Access-Control-Allow-Origin', '*');
    }

    public function sendAll(Request $request, MrbsBooking $booking)
    {

        foreach($request->attendees as $attendee) {
            $ids = $attendee['pivot'];

            if(array_key_exists('mrbs_attendee_id', $ids)){
                $user = MrbsAttendee::findOrFail($attendee['pivot']['mrbs_attendee_id']);
                Mail::to($user->email)->send(new MailNotification($booking, $user));
            }
        } 
        
        $notification = json_encode([['type' => 'success', 'message' => 'Mails has been sent']]);
        return  response()->json('', 200)->header('x-notification', $notification)->header('Access-Control-Allow-Origin', '*');
    }
}