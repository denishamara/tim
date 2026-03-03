<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRincianBiaya extends Migration
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
            'uraian' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'qty' => ['type' => 'INT'],
            'satuan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'harga' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'total' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('perjalanan_id', 'perjalanan_dinas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('rincian_biaya');
    }

    public function down()
    {
        $this->forge->dropTable('rincian_biaya');
    }
}