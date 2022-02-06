<?php
// base repository
namespace Modules\Base\Repository;

use Intervention\Image\Facades\Image;
use App\Infrastructure\Services\Utils\ArrayToDot;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

class BaseRepository implements BaseRepositoryInterface
{

    protected $errorMessage = null;
    protected $model;
    protected $withParams = [];
    public $paginate = false;
    protected $limit = 1500;
    protected $where = [
        'get' => []
    ];
    protected $orWhere = [
        'get' => []
    ];
    protected $currentQuery = null;
    protected $rules = [];
    protected $rulesEdit = [];
    protected $validator;
    protected $storedObject;
    protected $fields= ['*'];
    public $orderByParams = [];
    protected $linked = [];
    protected $path = null;
    public $imageConfig = [];
    public $imageSizes = [
        [
            'width' => 280,
            'height' => 190,
            'prefix' => "th"
        ],
        [
            'width' => 320,
            'height' => 280,
            'prefix' => "sm"
        ],
        [
            'width' => 480,
            'height' => 320,
            'prefix' => "md"
        ],
        [
            'width' => null,
            'height' => 480,
            'prefix' => "lg"
        ],
        [
            'width' => null,
            'height' => 720,
            'prefix' => "lghd"
        ],
        [
            'width' => null,
            'height' => 1080,
            'prefix' => "hd"
        ]
    ];

    protected $logApiTypes = [
        'person_views',
        'caseMaster',
        'entries'
    ];

    public $imageCropSizes = [
        "width" => 0,
        "height" => 0
    ];
    public $imageCrop = false;

    public function model($model = null)
    {

        // TODO: check if model exists before returing
        // TODO: check model instance type is EloquentModel before setting
        if($model === null) {
            return $this->model;
        } else {

            $this->model = $model;
            return $this;

        }
    }

    public function primaryKeyName() {
        return $this->model->getKeyName() ?? 'id';
    }

    public function rules($rules)
    {
        $this->rules  = $rules;
        return $this;
    }

    public function fields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function getErrors()
    {
        return (isset($this->validator)?$this->validator->messages():[]);
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function with($params)
    {
        $this->withParams = $params;
        return $this;
    }

    public function paginate($state = true, $limit = 10)
    {
        $this->paginate = $state;
        $this->limit = $limit;
        return $this;
    }


    public function where($data, $type = "get")
    {
        if (array_key_exists($type, $this->where)) {
            $this->where[$type][] = $data;
        } else {
            $this->where[$type] = [$data];
        }
        return $this;
    }

    public function applyHas()
    {

        if (request()->get('has')) {
            $fields = request()->get('has');
            $fieldsArray = explode(",", $fields);
            foreach ($fieldsArray as $value){
                
                $this->currentQuery= $this->currentQuery->has($value);
            }
        }

        return $this;
    }

    public function applyWhereDate()
    {
        if (request()->get('date')) {
            $fields = request()->get('date');
            $fieldsArray = explode(",", $fields);
           
            foreach ($fieldsArray as $key => $value){
                $keyValues = explode(":", $value); 
                $this->currentQuery= $this->currentQuery->whereDate( $keyValues[0], $keyValues[1]);
            }
        }

        return $this;
    }

    public function applyWhereBetween()
    {

        if (request()->get('between')) {
            $fields = request()->get('between');

            foreach ($fields as $key => $value){
                $fieldsArray = explode(",", $value);
                $this->currentQuery= $this->currentQuery->whereBetween( $key, $fieldsArray);
            }
        }

        return $this;
    }


    //&in[feild]=value1,value2
    public function applyWhereIn()
    {

        if (request()->get('in')) {
            $fields = request()->get('in');

            foreach ($fields as $key => $value){
                $fieldsArray = explode(",", $value);
                $this->currentQuery= $this->currentQuery->whereIn( $key, $fieldsArray);
            }

        }

        return $this;
    }

     //&notIn[feild]=value1,value2
     public function applyWhereNotIn()
     {
         if (request()->get('notIn')) {
             $fields = request()->get('notIn');
             foreach ($fields as $key => $value){
                 $fieldsArray = explode(",", $value);
                 $this->currentQuery= $this->currentQuery->whereNotIn( $key, $fieldsArray);
             }
         }
         return $this;
     }

    //&yearMonth[feild]=year,month
    public function applyYearMonth()
    {
        if (request()->get('yearMonth')) {
            $fieldsArray = request()->get('yearMonth');
            foreach ($fieldsArray as $key => $value){
                $keyValues = explode(",", $value); 
                if($keyValues[0]){
                    $this->currentQuery = $this->currentQuery->whereYear( $key, $keyValues[0]);
                }
                if($keyValues[1] && $keyValues[0] > 0){
                    $this->currentQuery = $this->currentQuery->whereMonth( $key, $keyValues[1]);
                }
            }
        }  
        return $this;
    }

       

    //&null=feildName
    public function applyWhereNull()
    {
        //fix// make it accept multiple columns
        if (request()->get('null')) {
            $field = request()->get('null');
            $this->currentQuery= $this->currentQuery->whereNull($field);
        }

        return $this;
    }

    public function applyWhereNotNull()
    {
        //fix// make it accept multiple columns
        if (request()->get('notNull')) {
            $field = request()->get('notNull');
            $this->currentQuery= $this->currentQuery->whereNotNull($field);
        }

        return $this;
    }


    public function orWhere($data, $type = "get")
    {
        if (array_key_exists($type, $this->where)) {
            $this->orWhere[$type][] = $data;
        } else {
            $this->orWhere[$type] = [$data];
        }
        return $this;
    }

    public function startQuery()
    {
         $this->currentQuery = $this->model->select($this->fields)
            ->with($this->withParams);
            return $this;
    }


    public function prepareQuery()
    {
        $tableName = $this->model->getTable();
        if ($this->currentQuery == null) {
            $this->startQuery();
        }

        foreach ($this->orderByParams as $each) {
            $this->currentQuery
                    ->orderBy($each[0], $each[1]);
        }
//        ->orderBy($this->orderByParams[0], $this->orderByParams[1])
        foreach ($this->where['get'] as $key => $value) {
            $this->currentQuery->where($value[0], $value[1], $value[2]);
        }

        foreach ($this->orWhere['get'] as $key => $value) {
            $this->currentQuery->orWhere($value[0], $value[1], $value[2]);
        }

        $this->applyWhereBetween();
        $this->applyWhereIn();
        $this->applyWhereNotIn();
        $this->applyWhereNull();
        $this->applyWhereNotNull();
        $this->applyHas();
        $this->applyWhereDate();
        $this->applyYearMonth();
        
        return $this->currentQuery;
    }

    public function destroy($id)
    {
        $obj = $this->model->find($id);
        if (empty($obj)) {
            return false;
        }
        $obj->delete();
        return true;
    }


    public function get($id)
    {
        $this->setDefaultWith();
        return $this->startQuery()->prepareQuery()->where($this->primaryKeyName(), "=", $id)->first();
    }

    public function setDefaultWith($requestData = []) {
        if(empty($requestData)) {
            $requestData = request()->all();
        }

        if (isset($requestData['with'])) {
                $with = explode(',', $requestData['with']);
                $this->with($with);
        } elseif (isset($this->model->defaultWiths)) {
            $this->with($this->model->defaultWiths);
        }
    }

    public function getLimit() {
        return request()->get("limit", ($this->model->paginateLimit ?? $this->limit));
    }

    public function getAll($request)
    {
        $requestData = $request->all();

        $this->setDefaultWith($requestData);

        $this->startQuery();
        $data = [];
        $limit = $this->getLimit();

        if(empty(request()->get('sort')) && !isset($this->model->orderByParams)) {
            $primaryKey = $this->primaryKeyName();
            $this->orderByParams = [[$primaryKey, "DESC"]];
        }

        if($this->model->orderByParams && $this->model->orderByParams != false ){
             $this->orderByParams = $this->model->orderByParams;
        }


        if (!empty($requestData)) {
            if (isset($requestData['search'])) {
                $fields = $requestData['search'];
                $fieldsArray = explode(",", $fields);
                foreach ($fieldsArray as $eachField) {
                    $fieldDataArray = explode(":", $eachField);
                    $nestedLoop = explode(".", $fieldDataArray[0]);

                    if (count($nestedLoop)> 1) {
                        $tempKey = $nestedLoop[count($nestedLoop)-1];
                        unset($nestedLoop[count($nestedLoop)-1]);
                        $tempWithString = implode(".", $nestedLoop);
                        $tempValue = $fieldDataArray[1];

                        $this->currentQuery->whereHas($tempWithString, function ($q) use ($tempKey, $tempValue) {
                            // $q->where($tempKey, "LIKE", '%'.$tempValue.'%');
                            $q->where($tempKey, "LIKE", $tempValue);
                        });
                    } else {
                        if (count($fieldDataArray) >= 2) {

                            if(count($fieldDataArray) == 3 && $fieldDataArray[0] == 'OR') {

                                if(preg_match('/^\*/', $fieldDataArray[2]) || preg_match('/\*$/', $fieldDataArray[2])) {
                                    $value = str_replace('*', '%', $fieldDataArray[2]);
                                    $this->orWhere([$fieldDataArray[1], "LIKE", $value]);
                                    // dd($value);
                                } else {
                                     $this->orWhere([$fieldDataArray[0], "=", $fieldDataArray[1]]);

                                }

                            } else {

                                if(preg_match('/^\*/', $fieldDataArray[1]) || preg_match('/\*$/', $fieldDataArray[1])) {
                                    $value = str_replace('*', '%', $fieldDataArray[1]);
                                    $this->where([$fieldDataArray[0], "LIKE", $value]);
                                    // dd($value);
                                } else {
                                     $this->where([$fieldDataArray[0], "=", $fieldDataArray[1]]);

                                }

                            }
                        }
                    }
                }
            }


            if (isset($requestData['sort'])) {
                $sort = $requestData['sort'];
                $sortArray = explode(",", $sort);
                if (!empty($sortArray)) {
                    $this->orderByParams = [];
                    foreach ($sortArray as $eachSort) {
                        $sortTypeArray = explode(":", $eachSort);
                        $type = "ASC";
                        $field =$sortTypeArray[0];
                        if (count($sortTypeArray) > 1) {
                            $type = $sortTypeArray[1];
                        }
                        $this->orderByParams[] = [$field, $type];
                    }
                }
            }


        }
        if(isset($this->model->simplePaginate)) {
            $data = $this->prepareQuery()->paginate($limit);
        } else {
            $data = $this->prepareQuery()->paginate($limit);
        }
        
        return $data;
    }

 

    public function mapData($request){

        $requestParams = request()->route()->parameters;

        $data = [
            'type' => \Route::currentRouteName() ?? 'no_route_name',
            'request_params' => json_encode($requestParams),
            'query_string' =>  json_encode($request->all()),
            'user_name' => user()->username,
            'ip_address' => $request->server('REMOTE_ADDR'),
            'client' => $request->header('User-Agent'),
            'request_url' => $request->fullUrl()
        ];

        return $data;
    }

    public function validateInput($data)
    {
        $rules = $this->getRules();
        $this->validator = \Validator::make($data, $rules);
        if ($this->validator->fails()) {
            return false;
        }
        return true;
    }

    public function getRules() {
        if(!empty($this->model->dataRules)) {
            return $this->model->dataRules;
        } else {
            return $this->rules;
        }
    }

    public function store($data)
    {
//        htmlspecialchars
        $tableName = $this->model->getTable();

        if (key_exists("password", $data)) {
            $data['password'] = \Hash::make($data['password']);
        }

       
        foreach ($this->linked as $key => $value) {
            if (key_exists($key, $data)) {
                unset($this->rules[$key."_id"]);
                $this->rules = ArrayToDot::merge($this->rules, $value['model']::$rules, $key);
            }
        }

        if (!$this->validateInput($data)) {
            return false;
        }

        foreach ($this->linked as $key => $value) {
            $tempRepository = new $value['repo'](new $value['model']);
            $tempRepository->store($data[$key]);
            unset($data[$key]);
            $data[$key."_id"] = $tempRepository->getStoredObject()->id;
        }

        foreach ($data as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $data[$key] = json_encode($value);
            }
        }

        if (array_key_exists("user_id", $this->rules) && !isset($data['user_id'])) {
            $data['user_id'] = \Auth::check() ? \Auth::id():1;
        }

        if (\Schema::hasColumn($tableName, 'created_by') && \Auth::check()) {
            $user = user();
            $data['created_by']  = $user->username ?? $user->id;
        }


        if (\Schema::hasColumn($tableName, 'key')) {
            $data['key']  = uniqid().md5(str_random(5));
        }

        $this->storedObject = new $this->model($data);
        $this->storedObject->save();
        return true;
    }

    public function getStoredObject()
    {
        return $this->storedObject;
    }

    public function update($id, $data)
    {
        $tableName = $this->model->getTable();
        if (key_exists("password", $data)) {
            $data['password'] = \Hash::make($data['password']);
        }

        $this->storedObject = $this->model->find($id);
        $data = $this->filterUpdateColumns($data);

        if (\Schema::hasColumn($tableName, 'updated_by') && \Auth::check()) {
            $user = user();
            $data['updated_by']  = $user->username ?? $user->id;
        }

        $this->storedObject->fill($data)->save();
    
        $this->storedObject = $this->get($this->storedObject->id);
        return true;
    }

    protected function filterUpdateColumns($data) {

        $updateColumns = $this->model->getUpdateColumns();
        // filter columns only if we get a non empty list of columns    
        if(!empty($updateColumns)) {
            return collect($data)->only($updateColumns)->all();
        } else {
            return $data;
        }
    }

    public function moveImage($file)
    {
        $filename = $file->getClientOriginalName();
        $mime = $file->getMimeType();

        $type = $file->getClientOriginalExtension();
        $fileName = preg_replace('/[^a-z0-9]/i', '_', $filename)."_".substr(md5(date("YmdHis")), 0, 10).".".$type;
        $filePath = storage_path().DIRECTORY_SEPARATOR."files";
        $file->move($filePath, $fileName);
        Image::make($filePath.DIRECTORY_SEPARATOR.$fileName
                )->resize(320, null)->save($filePath.DIRECTORY_SEPARATOR.'thumbnails'.DIRECTORY_SEPARATOR.$fileName);

         return  [
            'key' => md5(date("YmdHis")),
            'file' => $fileName,
            'path' => 'files',
            'mime' => $mime
         ];
    }


    public function __log($type, $data, $syncState = "no", $syncID = "")
    {
        return null;
    }

    public function setDefaultPath($path = null)
    {
        if (is_null($path)) {
            $this->path = storage_path().DIRECTORY_SEPARATOR.'app';
        } else {
            $this->path = $path;
        }
    }

    public function getValidationRules()
    {
        return $this->model->dataRules ?: [] ;
    }

    public function doAction($id, $topRes) {
        $action = request()->all();
        $data = $action['data'];
        $keyName = $this->primaryKeyName();
        $id = $data[$keyName];
        unset($data[$keyName]);
        return $this->update($id, $data);
        
    }





}
