<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
           "id" => [
               "type" => "INT",
               "constraint" => 11,
               "auto_increment" => true,
               "unsigned" => true
           ],
           "user_id" => [
               "type" => "INT",
               "constraint" => 11,
                "unsigned"=> true,
           ],
           "rut" => [
               "type" => "INT",
               "constraint" => 20,
               "unsigned" => true,
           ],
           "name" => [
               "type" => "VARCHAR",
               "constraint" => 100,
           ],
           'address'   => ['type' => 'TEXT'],
           'email'     => [
                'type' => 'VARCHAR', 
                'constraint' => 255
            ],
           'phone' => [
            'type' => 'VARCHAR', 
            'constraint' => 20
             ],
            
        ]);
        $this->forge->addPrimaryKey("id");
        $this->forge->addForeignKey("user_id", "users", "id", "CASCADE", "CASCADE");
        $this->forge->createTable("customers");
    }

    public function down()
    {
        $this->forge->dropTable("customers");
    }
}
