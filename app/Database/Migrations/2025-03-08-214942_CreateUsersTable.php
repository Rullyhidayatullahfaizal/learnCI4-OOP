<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()    
    {
        $this->forge->addField([
            'id' => [
                "type" => "INT",
                "constraint" => 11,
                "auto_increment" => true,
                "unsigned" => true,
            ],
            'email' => [
                "type" => "VARCHAR",
                "constraint" => 255,
                "unique" => true,
            ],
            'username' => [
                "type" => "VARCHAR",
                "constraint" => 50,
                "unique" => true,
            ],
            'password' => [
                "type" => "VARCHAR",
                "constraint" => 255,
            ],
            "status" => [
                "type" => "ENUM",
                "constraint" => ["active", "inactive"],
                "default" => "active",
            ],
            'created_at' => [
                "type" => "DATETIME",
                "null" => true,
            ],
            'updated_at' => [
                "type" => "DATETIME",
                "null" => true,
            ]   

        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
