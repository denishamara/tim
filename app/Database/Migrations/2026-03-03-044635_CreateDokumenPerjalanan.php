<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDokumenPerjalanan extends Migration
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
            'nama_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'path_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'uploaded_by' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP'
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('perjalanan_id', 'perjalanan_dinas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('uploaded_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dokumen_perjalanan');
    }

    public function down()
    {
        $this->forge->dropTable('dokumen_perjalanan');
    }
}