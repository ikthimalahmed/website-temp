<?php
namespace Modules\Base\Controller;



class ResponseController extends \App\Http\Controllers\Controller{
       
    public function error($message) {
        return $this->response(404, $message, [], 404);
    }

    public function response($statusCode, $message, $data = [], $httpCode = 200) {
        
        return response(['code' => $statusCode, 'message' => $message, 'data'=>$data], $httpCode);
    }

    public function success($message, $data) {
        return $this->response(200, $message, $data, 200);
    }

}