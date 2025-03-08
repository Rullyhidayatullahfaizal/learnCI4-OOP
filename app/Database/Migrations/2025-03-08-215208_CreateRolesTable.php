<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

use function PHPSTORM_META\type;

class CreateRolesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint"=> 11,
                "auto_increment" => true,
                "unsigned" => true
            ],
            "role_name" => [
                'type' => "ENUM",
                'constraint' => ["agent", "customer"],
                "default"=>"customer",
            ]
        ]);
        $this->forge->addPrimaryKey("id");table: 
        $this->forge->createTable("roles");    

    }

    public function down()
    {
        $this->forge->dropTable("roles");
    }
}
