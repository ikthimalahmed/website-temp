<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MrbsBooking extends BaseModel
{

    public $paginateLimit = 20;
    public $restEnabled = true;
    public $timestamps = false;

    protected $table = 'mrbs_bookings';
    // protected $dates = ['start_datetime', 'end_datetime'];

    public $appends = ['formatted_start_date', 'formatted_end_date'];

    public function getFormattedStartDateAttribute($value)
    {
        return \Carbon\Carbon::parse($this->start_datetime)->format('d/m/Y H:i');
    }

    public function getFormattedEndDateAttribute($value)
    {
        return \Carbon\Carbon::parse($this->end_datetime)->format('d/m/Y H:i');
    }

    public $dataRules = [
        'room_id' => 'required',
        'start_datetime' => 'required',
        'end_datetime' => 'required',
        'description' => 'required',
    ];

    public function room()
    {
        return $this->belongsTo('\App\Models\MrbsRoom', 'room_id');
    }

    public function attendees()
    {
        return $this->belongsToMany('\App\Models\MrbsAttendee', 'bookings_user');
    }
}
