<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJenisBiayaIdToRincianBiaya extends Migration
{
    public function up()
    {
        $this->forge->addColumn('rincian_biaya', [
            'jenis_biaya_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
                'after'    => 'perjalanan_id',
            ],
        ]);

        $this->forge->addForeignKey('jenis_biaya_id', 'jenis_biaya', 'id', 'SET NULL', 'SET NULL', 'rincian_biaya');
        $this->forge->processIndexes('rincian_biaya');
    }

    public function down()
    {
        $this->forge->dropForeignKey('rincian_biaya', 'rincian_biaya_jenis_biaya_id_foreign');
        $this->forge->dropColumn('rincian_biaya', 'jenis_biaya_id');
    }
}
