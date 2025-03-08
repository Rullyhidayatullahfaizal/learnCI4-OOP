<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubscriptionChangesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'unsigned' => true],
            'customer_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'old_package_id'=> ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'new_package_id'=> ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'requested_at'  => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('customer_id', 'customers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('old_package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('new_package_id', 'packages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subscription_changes');
    }

    public function down()
    {
        $this->forge->dropTable('subscription_changes');
    }
}
