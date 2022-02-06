<?php
namespace Modules\RestShared;

use Modules\Base\Controller\BaseController;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class RestSharedController extends BaseController {

    public $user;

    public function __construct( \Modules\RestShared\RestSharedRepository $repository ) {
        $this->repository = $repository;
        $this->middleware(function ($request, $next)
        {
            \Auth::user() ? $this->user = \Auth::user()->id : null;
            return $next($request);
        });
    }

    public function rsDestroy($topRes, $id) {
        return parent::destroy($id);
    }

    public function rsUpdate($topRes, $id) {
        $request = request();
        return parent::update($request, $id);
    }

    public function rsDoAction($topRes, $id) {
        if(!$this->repository->doAction($topRes, $id))
        {
            return $this->response(405, "Validation Errors", $this->repository->getErrors(), 422 );
        }
        return $this->success('Updated Successfully.', $this->repository->getStoredObject());
    }

    public function rsShow($topRes, $id) {
        $request = request();
        return parent::show($id);
    }

    public function rsShowRules($topRes) {
        $request =  request();
        return parent::showRules();
    }

}
