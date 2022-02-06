<?php
namespace Modules\Base\Controller;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BaseController extends \App\Http\Controllers\Controller{

    /**
     *
     * @var \App\Repositories\Repository\BaseRepository
     */
    public $repository;


    public function create() {

    }

    public function destroy($id) {
        if($this->repository->destroy($id))
        {
            return $this->success("Succcessfully Deleted", ['id' => $id]);
        }
        return $this->error("Unable to find the required record.");
    }


    public function index(Request $request) {
        // $this->repository->paginate = $request->get("paginate", $this->repository->paginate);
        // $paginationState = $this->repository->paginate;

        $data = $this->repository->getAll($request);

        if(empty($data))
        {
            return $this->success("No Data Found", [], 'info');
        }

        $isPaginated = $data->lastPage() > 1;

        if($isPaginated)
        {
            $currentPage = $data->currentPage();
            $lastPage = $data->lastPage();
            $firstItem = $data->firstItem();
            $lastItem = $data->lastItem();
            $totalItems = $data->total();

            $headers = [
                'Content-Range' => " items $firstItem-$lastItem/$totalItems",
                'x-total-pages' => $lastPage,
                'x-total-items' => $totalItems,
                'x-current-page' => $currentPage,
            ];
            // dd($data->items());
            $message = "Data Returned (Page $currentPage of $lastPage)";
            return $this->success($message, $data->items(), 'info', 206, $headers);
        }


        return $this->success("Data Returned", $data->items(), 'info');
    }

    public function show($id) {
        $data = $this->repository->get($id);

        if(empty($data) || empty($data->toArray()))
        {

            return $this->error("No Record Found.",[],404);
        }
        // return $this->success("Record Retrieved.", $data);
        return $data;
    }

    public function store(Request $request) {
        $data = $request->all();
        $result =  $this->_store($data);

        // var_dump( $this->repository->getErrors());
        // die();
        return $result;


    }

    public function beforeStore($data) {
        return $data;
    }

    public function afterStore($storedData) {
        return $storedData;
    } 


    public function _store($data, $returnType = 'response') {

        $data = $this->beforeStore($data);

        if(!$this->repository->store($data))
        {
            $result = [
                'error' => $this->repository->getErrors()->toArray(),
                'statusCode' => 422,
                'msg' => 'Validation Errors',
            ];


        } else {
        
            $storedObject =  $this->repository->getStoredObject();

            $this->afterStore($storedObject);

            $result = [
                'data' => $storedObject,
                'statusCode' => 200,
                'msg' => 'Saved Successfully.',
            ];


        }




        if($returnType == 'response') {
            return $this->storeResponse($result);
        } else {


            return $result;

        }


    }

    public function storeResponse($result) {
      

        if(array_key_exists('error',$result)) {

            return $this->error($result['msg'], $result['error'], $result['statusCode']);

        } else {
            return $this->success($result['msg'], $result['data']);
        }

    }

    public function update(Request $request,$id) {
        $data = $request->all();
        return $this->_update($id, $data);
    }

    public function beforeUpdate($data) {
        return $data;
    }

    public function _update($id, $data) {
        $data = $this->beforeUpdate($data);

        if(!$this->repository->update($id, $data))
        {
            return $this->response(405, "Validation Errors", $this->repository->getErrors(), 422 );
        }
        return $this->success('Updated Successfully.', $this->repository->getStoredObject());

    }

    public function showRules() {
        $rules = $this->repository->getValidationRules();

        if(!$rules)
        {
            // public function response($statusCode, $message, $data = [], $httpCode = 200) {
            return response()->json([]);
        }
        return response()->json( $rules);
    }

    public function error($message, $data=[], $httpCode = 400, $headers = []) {
        // ddd($message);
        $notifications = json_encode([['type' => 'error', 'message' => $message, 'errors' => $data]]);

        $resp =  response()->json(['type' => 'error', 'message' => $message, 'errors' => $data], $httpCode)->header('x-notification', $notifications)->header('Access-Control-Allow-Origin', '*');
        foreach($headers as $headerKey => $headerVal) {
            $resp->header($headerKey, $headerVal);
        }
        return $resp;

    }

    public function response($statusCode, $message, $data = [], $httpCode = 200) {

        return response(['code' => $statusCode, 'message' => $message, 'data'=>$data], $httpCode);
    }

    public function success($message, $data, $type = 'success', $httpCode = 200, $headers = []) {
        $notifications = json_encode([['type' => $type, 'message' => $message]]);
        // foreach($data as $d) {
        //     json_encode($d);
        // }
        // $d = json_encode($data);
        // dd(json_last_error_msg());
        $resp =  response()->json($data, $httpCode)->header('x-notification', $notifications)->header('Access-Control-Allow-Origin', '*');
        foreach($headers as $headerKey => $headerVal) {
            $resp->header($headerKey, $headerVal);
        }
        return $resp;
    }

}
