<?php
namespace Modules\Logging;


use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class LoggingService {

    private $logTableName;
    public $saveTries = 0;

    private $user_id;
    private $pk_id;
    private $change_type;
    private $change_from;
    private $change_to;
    private $created_at;
    private $original;
    private $attributes;
    protected $currentModel;

    function __construct($model){

        $this->currentModel = $model;
        $this->saveTries = 0;
        $cmsTableName = $model->getTable();
        $this->logTableName = $cmsTableName."_log";
        $this->user_id =  \Auth::user() ?  \Auth::user()->id : 0;
        $this->pk_id = $model->getKey();
        $this->attributes = $model->getAttributes();
        $this->original = $model->getOriginal();
        $this->model = $model;
        
    }

    public function logChange(){
        $this->change_type = 'update';
        $to = $this->currentModel->isDirty() ? $this->currentModel->getDirty() : [];
        $from = array_intersect_key($this->original, $to ); 
        $this->change_from = json_encode($from);
        $this->change_to = json_encode($to);
        // dd($this);
        $this->saveLog();
    }

    public function logCreation(){
        $this->change_type = 'create';
        $this->change_from = json_encode([]);
        $this->change_to = json_encode($this->attributes);
        $this->saveLog();
    }

    public function logDelete(){
        $this->change_type = 'delete';
        $this->change_from = json_encode($this->attributes);
        $this->change_to = json_encode([]);
        $this->saveLog();
    }

    public function createTableIfNotExists() {
        $tableExists = \Schema::connection('log_mysql')->hasTable($this->logTableName);
        if(!$tableExists) {
            DB::connection('log_mysql')->select(
                "CREATE TABLE ".$this->logTableName." (
                id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id int(10) UNSIGNED NOT NULL,
                user_name VARCHAR(30) NOT NULL,
                user_ip VARCHAR(30) NOT NULL,
                pk_id int(10)  UNSIGNED NOT NULL,
                change_type VARCHAR(30) NOT NULL,
                change_from JSON,
                change_to JSON,
                created_at TIMESTAMP)"
            );
        }

    }



    private function saveLog(){
       
        try {
            if(user()){
                $username = user()->username;
            }else {
                $username =  request()->has('user_name') ?  request()->user_name:'api_user';
            }
         


            $test = DB::connection('log_mysql')->table($this->logTableName)->insert([
                'user_id' => $this->user_id,
                'user_name' => $username, //user() ? user()->username : request()->has('user_name') ? request()->user_name :'api_user',
                'user_ip' => request()->ip(),
                'pk_id' => $this->pk_id,
                'change_type' => $this->change_type,
                'change_from' => $this->change_from,
                'change_to' => $this->change_to,
                'created_at' => Carbon::now()->toDateTimeString()
            ]);

        } catch (\Illuminate\Database\QueryException $e) {

            //exception code 23000 - SQLSTATE[23000]: Integrity constraint violation:
            //exception code 42S02 - Base table or view not found
            if($e->getCode() == '42S02') {
                $this->createTableIfNotExists();
                if($this->saveTries < 1 ) {
                    ++$this->saveTries;
                    $this->saveLog();

                }

            } else {
                abort(500, $e->getMessage());
            }
            

        } 
        

    }
}