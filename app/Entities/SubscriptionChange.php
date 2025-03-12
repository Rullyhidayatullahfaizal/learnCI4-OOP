<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SubscriptionChange extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id' => 'int',
        'customer_id' => 'int',
        'old_package_id' => 'string',
        'new_package_id' => 'string',
        'status' => 'string',
    ];

    // public function getStatus($status)
    // {
    //     $this->attributes['status'] = strtolower($status);
    //     return $this;
          
    // }

    public function setStatus($status){
        $this->attributes['status'] = strtoupper($status);
        return $this;
    }
}
