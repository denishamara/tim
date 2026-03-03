<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApprovalLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'perjalanan_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'approved_by' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['direktur','admin','keuangan'],
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'catatan' => ['type' => 'TEXT', 'null' => true],
            'approved_at DATETIME DEFAULT CURRENT_TIMESTAMP'
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('perjalanan_id', 'perjalanan_dinas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('approval_logs');
    }

    public function down()
    {
        $this->forge->dropTable('approval_logs');
    }
}