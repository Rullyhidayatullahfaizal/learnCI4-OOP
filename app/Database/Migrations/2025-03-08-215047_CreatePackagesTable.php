<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePackagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true],
            'name'      => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'price'     => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'type'      => ['type' => 'ENUM', 'constraint' => ['Normal', 'Bagus', 'Luar Biasa', 'Penawaran Musiman']],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('packages');
    }

    public function down()
    {
        $this->forge->dropTable('packages');
    }
}
