<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePerjalananPeserta extends Migration
{
    public function up()
    {
        $table = $this->db->query("SHOW TABLES LIKE 'perjalanan_peserta'")->getFirstRow();
        if ($table) {
            return;
        }

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
            'user_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'jabatan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Jabatan peserta saat perjalanan',
            ],
            'keterangan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Keterangan tambahan untuk peserta',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('perjalanan_id', 'perjalanan_dinas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        
        // Unique constraint to prevent duplicate participants
        $this->forge->addUniqueKey(['perjalanan_id', 'user_id']);
        
        $this->forge->createTable('perjalanan_peserta');
    }

    public function down()
    {
        $this->forge->dropTable('perjalanan_peserta', true);
    }
}
