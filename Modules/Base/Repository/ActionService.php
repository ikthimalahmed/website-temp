<?php
// base repository
namespace Modules\Base\Repository;

class ActionService
{

    public function doAction($model, $actions) {
        $actionDef = [
            'res_type' => 'dp_schedule',
            'action_type' => 'update_status',
            'rules' => [
                'status' => ['required']
            ],
            'allowed_mutations' => [
                'status' => [
                    ['from' => 'open', 'to'=> '*']
                ]
            ] 
            
        ];


        return $model;
    }

}