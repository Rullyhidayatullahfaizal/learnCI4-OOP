<?php

namespace App\Controllers;


use CodeIgniter\RESTful\ResourceController;


class CustomerController extends ResourceController
{
    protected $modelName = 'App\Models\CustomerModel';
    protected $format = "json";

    public function index()
    {
        $customers = $this->model->findAll();
        $res = [[
            "status" => 200,
            "message" => "Success To Get All Customers",
            "totalData" => count($customers),
            "data" =>  $customers
        ]];
        return $this->respond($res);
    }

    public function show($id = null)
    {
        $customer = $this->model-> find($id);
        $res = [
            "status" => 200,
            "message" => "Success To Get Customer",
            "data" =>  $customer
        ];
        if(!$customer){
            return $this->failNotFound("Customer Not Found");
        }
        return $this->respond($res);   
    }

            public function update($id = null)
            {
                $customer = $this->model-> find($id);
                if(!$customer){
                    return $this->failNotFound("Customer Not Found");
                }
                $data = $this->request->getJSON(true);
                // var_dump($data);exit;

                if (empty($data)) {
                    return $this->fail("No data provided for update.");
                }

                if(isset($data['status']) && session()->get('role') !== "agent" ){
                    return $this->failForbidden('customers cannont change staus');
                }

                $this->model->update($id, $data);
                return $this->respond([
                    "status" => 201,
                    "message" => "Success To Update Customer",
                ]); 
            }

        public function delete($id = null)
        {
            if(session()->get('role') !== "agent"){
                return $this->failForbidden("Only agent can delete customer");
            };

            $customer = $this->model->find($id);
            if(!$customer){
                return $this->failNotFound("Customer Not found");
            };

            $this->model->delete($id);
            return $this->respondDeleted(["message" => "Customer deleted successfully"]);
        }    
}
