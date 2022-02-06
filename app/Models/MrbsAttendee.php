<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MrbsAttendee extends BaseModel
{
    public $restEnabled = true;  // true; Enables restshared functionaility on this model; POST, PUT, GET
    public $paginateLimit = 20;
    protected $table = 'mrbs_attendees';
    public $timestamps = false;

    public $dataRules = [
        'name' => 'required',
    ];
}
