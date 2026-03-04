<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKendaraan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_kendaraan' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'nomor_polisi' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'aktif' => [
                'type'    => 'TINYINT',
                'default' => 1,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('kendaraan');
    }

    public function down()
    {
        $this->forge->dropTable('kendaraan');
    }
}
