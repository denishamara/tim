<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJenisBiaya extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'satuan_default' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Kali',
            ],
            'harga_default' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'butuh_kendaraan' => [
                'type'    => 'TINYINT',
                'default' => 0,
                'comment' => '1 = wajib pilih kendaraan',
            ],
            'aktif' => [
                'type'    => 'TINYINT',
                'default' => 1,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('jenis_biaya');

        // Seed default data
        $this->db->table('jenis_biaya')->insertBatch([
            ['nama' => 'BBM PP',            'satuan_default' => 'PP',    'harga_default' => 175000, 'keterangan' => 'Biaya bahan bakar pergi-pulang',        'butuh_kendaraan' => 1, 'aktif' => 1],
            ['nama' => 'BBM Di Lokasi',     'satuan_default' => 'Hari',  'harga_default' => 100000, 'keterangan' => 'Biaya bahan bakar operasional di lokasi', 'butuh_kendaraan' => 1, 'aktif' => 1],
            ['nama' => 'Uang Makan',        'satuan_default' => 'Hari',  'harga_default' => 75000,  'keterangan' => 'Uang makan harian',                       'butuh_kendaraan' => 0, 'aktif' => 1],
            ['nama' => 'Biaya Penginapan',  'satuan_default' => 'Malam', 'harga_default' => 0,      'keterangan' => 'Biaya hotel / penginapan',                'butuh_kendaraan' => 0, 'aktif' => 1],
            ['nama' => 'Biaya Transportasi','satuan_default' => 'Kali',  'harga_default' => 0,      'keterangan' => 'Biaya tol, tiket, dll',                   'butuh_kendaraan' => 0, 'aktif' => 1],
            ['nama' => 'Parkir',            'satuan_default' => 'Kali',  'harga_default' => 20000,  'keterangan' => 'Biaya parkir kendaraan',                  'butuh_kendaraan' => 1, 'aktif' => 1],
            ['nama' => 'Lainnya',           'satuan_default' => 'Kali',  'harga_default' => 0,      'keterangan' => 'Biaya lain-lain',                         'butuh_kendaraan' => 0, 'aktif' => 1],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('jenis_biaya');
    }
}
