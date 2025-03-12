<?php

namespace App\Controllers;

use App\Models\SubscriptionChangeModel;
use App\Entities\SubscriptionChange;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;


class SubscriptionChangeController extends ResourceController
{
    protected $model;
    public function __construct()
    {
        $this->model = new SubscriptionChangeModel;    
    }

    public function index()
    {
        $data = $this->model->findAll();
        return $this->respond($data);
    }

    public function show($id = null)
    {

        $data = $this->model->find($id);
        if(!$data){
            return $this->failNotFound("subscription change not found");
        };
        $res = [
            "status" => 200,
            "message" => "Success To Get Subscription Change",
            "data" =>  $data
        ];

        return $this->respond($res);
    }

    public function create()
    {
        $rules = [
            'customer_id' => 'required|integer',
            'old_package_id' => 'required|string',
            'new_package_id' => 'required|string',
            'status' => 'required|in_list[Normal,Good,Exelent,Seasonal,Offer]',
        ];

        if(!$this->validate($rules)){
            return $this->failValidationErrors($this->validator->getErrors());
        }

        if(isset($data['status']) && session()->get('role') !== "customer" ){
            return $this->failForbidden('Agents cannont change staus');
        }
        
        // entity
        $entity = new SubscriptionChange();
        $entity->customer_id = $this->request->getVar('customer_id');
        $entity->old_package_id = $this->request->getVar('old_package_id');
        $entity->new_package_id = $this->request->getVar('new_package_id');
        $entity->status = $this->request->getVar('status');

        $this->model->save($entity);
        $res = [
            "status" => 201,
            "message" => "Success To Create Subscription Change",
            "data" =>  $entity
        ];
        return $this->respond($res);
    }

    public function update($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) {
            return $this->failNotFound("subscription change not found");
        }

        $entity = new SubscriptionChange();
        $entity->id = $id;
        $entity->customer_id = $this->request->getVar('customer_id');
        $entity->old_package_id = $this->request->getVar('old_package_id');
        $entity->new_package_id = $this->request->getVar('new_package_id');
        $entity->status = $this->request->getVar('status');

        $this->model->save($entity);
        $res = [
            'status' => 201,
            'message' => "Success To Update Subscription Change",
            'data' => $entity
        ];
        return $this->respond($res);
    }

    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) {
            return $this->failNotFound("subscription change not found");
        }
        $this->model->delete($id);
        return $this->respondDeleted(["message" => "subscription change deleted successfully"]);
    }

}
