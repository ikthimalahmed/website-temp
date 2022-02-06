<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\Logging\LoggingService;
// use Modules\Acl\AclService;
/**
 * App\Models\BaseModel
 *
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    protected $guarded = [];
    protected $dataRules = [];

    // Overide asDateTime method to allow conversion of dates form javascript in ISO format and set timezone

    protected static function boot()
    {


        
        static::created(function ($model) {
           
            // $logger = new LoggingService($model);
            // $logger->logCreation();
        });

        static::updated(function ($model) {
           
            // $logger = new LoggingService($model);
            // $logger->logChange();
        });

        static::deleted(function ($model) {
           
            // $logger = new LoggingService($model);
            // $logger->logDelete();
        });


        parent::boot();
    }


    public function getColumns() {
        $minutes = 5;
        $connection = $this->getConnectionName();
     
        return \Cache::remember('columns-'.$this->getTable(), $minutes, function () use ($connection) {
            $columnList = \Schema::connection($connection)->getColumnListing($this->getTable());
            return $columnList;
        });
    }

    public function getCreateColumns() {
        return $this->getColumns();
    }
    
    public function getUpdateColumns() {
        return $this->getColumns();
    }

    protected function asDateTime($value)
    {
        $timeZone = config('app.timezone')?:'Indian/Maldives';
        $lastChar = substr($value, -1, 1);
        if($lastChar == 'Z') {
            return Carbon::createFromFormat('Y-m-d\TH:i:s.uO', $value)->setTimezone('Indian/Maldives');
        }
        return parent::asDateTime($value);
    }

    

}
