<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MrbsRoom extends BaseModel
{
    public $restEnabled = true;
    public $paginateLimit = 20;
    protected $table = 'mrbs_rooms';
    public $timestamps = false;

    public $dataRules = [
        'name' => 'required',
    ];
}
