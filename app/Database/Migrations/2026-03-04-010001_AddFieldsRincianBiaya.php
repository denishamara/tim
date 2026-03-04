<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsRincianBiaya extends Migration
{
    public function up()
    {
        $this->forge->addColumn('rincian_biaya', [
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'perjalanan_id',
            ],
            'keterangan' => [
                'type'  => 'TEXT',
                'null'  => true,
                'after' => 'judul',
            ],
            'kendaraan_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
                'after'    => 'keterangan',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('rincian_biaya', ['judul', 'keterangan', 'kendaraan_id']);
    }
}
