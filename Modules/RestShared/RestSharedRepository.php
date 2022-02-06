<?php
namespace Modules\RestShared;

use Modules\Base\Repository\BaseRepository;
use Illuminate\Support\Str;

class RestSharedRepository  extends BaseRepository{

	public $topResModelRouteParam = 'resType';
	public $modelPrefix = '\App\Models\\';

    public function __construct($modelName = null) {
		if($modelName) {
			$this->model =\App::make($modelName);
		} else {
			$this->guessModel();
		}
		!$this->model->restEnabled ? abort(403, 'Unauthorized action'): null;
        $this->rules = [];
	}

	public function guessModel() {
		$modelFromRouteParams = $this->getFromRouteParams();
		if(!$modelFromRouteParams) {
			$this->getDirectlyFromPath();
		}
	}

	public function getFromRouteParams() {
		$modelAssigned = false;
		$routeParams = request()->route()->parameters;
		if(isset($routeParams[$this->topResModelRouteParam])) {
			
			$modelString =  $routeParams[$this->topResModelRouteParam];
			$modelName = $this->formatModelName($modelString);
			$this->model =\App::make($modelName);
			$modelAssigned = true;
		} 
		return $modelAssigned;
	}

	public function getDirectlyFromPath() {
		$modelAssigned = false;
		$currentUrl = explode("/", \Request::url());
		if(!isset($currentUrl[4])) {
			abort(404, 'path is shorter than expected, i.e. can not guess model from path');
		} else {
			$modelName = $this->formatModelName($currentUrl[4]);
			$this->model =\App::make($modelName);
			$modelAssigned = true;
		}
	}

	public function formatModelName($string) {
		$modelNameSnakeCase = str_replace('-', '_', $string);
		$modelNameSingular = Str::camel(Str::singular($modelNameSnakeCase, 1));
		$modelClassName = ucfirst($modelNameSingular);
		$modelName = $this->modelPrefix.$modelClassName;
		return $modelName;
	}
	
}