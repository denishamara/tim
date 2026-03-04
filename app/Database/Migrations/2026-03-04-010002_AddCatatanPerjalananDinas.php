<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCatatanPerjalananDinas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('perjalanan_dinas', [
            'jam_berangkat' => [
                'type'    => 'VARCHAR',
                'constraint' => 10,
                'null'    => true,
                'after'   => 'tanggal_berangkat',
            ],
            'jam_pulang' => [
                'type'    => 'VARCHAR',
                'constraint' => 10,
                'null'    => true,
                'after'   => 'tanggal_pulang',
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'keperluan',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('perjalanan_dinas', ['jam_berangkat', 'jam_pulang', 'catatan']);
    }
}
