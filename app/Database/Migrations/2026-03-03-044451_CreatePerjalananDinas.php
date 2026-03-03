<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePerjalananDinas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nomor_surat' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'tujuan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'kota_tujuan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'tanggal_berangkat' => ['type' => 'DATE'],
            'tanggal_pulang' => ['type' => 'DATE'],
            'keperluan' => ['type' => 'TEXT'],
            'total_pengajuan' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => [
                    'draft',
                    'approved_1',
                    'rejected_1',
                    'processed_admin',
                    'approved_2',
                    'rejected_2',
                    'sent_finance',
                    'completed'
                ],
                'default' => 'draft',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('perjalanan_dinas');
    }

    public function down()
    {
        $this->forge->dropTable('perjalanan_dinas');
    }
}