<?php
namespace Modules\Logging;

use Illuminate\Support\Facades\DB;


class SyncTablesService {

    public static function syncTables(){
        $cmsTables = DB::select('SHOW TABLES');
        $logTables = DB::connection('log_mysql')->select('SHOW TABLES');
        
        $logTablesArray = [];

        foreach($logTables as $table)
        {
            // echo $table->Tables_in_lcms;
            array_push($logTablesArray,$table->Tables_in_logs_db);
        }

        foreach($cmsTables as $key => $table)
        {
            $cmsTableName = $table->Tables_in_lcms;
            if(!in_array($cmsTableName."_log",$logTablesArray)){
                
                DB::connection('log_mysql')->select(
                    "CREATE TABLE ".$cmsTableName."_log (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    user_id int(10) NOT NULL,
                    pk_id int(10) NOT NULL,
                    change_type VARCHAR(30) NOT NULL,
                    change_from JSON,
                    change_to JSON,
                    created_at TIMESTAMP)"
                );
                echo "Added ".$cmsTableName."_log \n";
            }
        }
    }
}