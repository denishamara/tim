<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoneAddressToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'email',
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'phone',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['phone', 'address']);
    }
}
