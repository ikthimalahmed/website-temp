<?php
namespace Modules\Base\Repository;

interface BaseRepositoryInterface {
    public function getAll($request);
    public function store($data);
    public function get($id);
    public function update($id, $data);
    public function destroy($id);
    
    public function moveImage($file);
    
    public function model($model);
    public function rules($rules);
    public function fields($fields);
    public function getErrors();
    public function getErrorMessage();
    public function with($params);
    public function where($data, $type="get");
    public function validateInput($data);
    public function getStoredObject();
    public function __log($type, $data, $syncState = "no", $syncID = "");
    
    
    public function startQuery();
    public function prepareQuery();
    
    public function paginate($state = true, $limit = 10);
}