<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KendaraanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_kendaraan' => 'Mitsubishi Mirage',
                'nomor_polisi'   => 'B 1744 EYC',
                'jenis'          => 'Kendaraan Roda 4',
                'keterangan'     => 'Kendaraan operasional kantor',
                'aktif'          => 1,
            ],
            [
                'nama_kendaraan' => 'Toyota Avanza',
                'nomor_polisi'   => 'B 2201 XYZ',
                'jenis'          => 'Kendaraan Roda 4',
                'keterangan'     => 'Kendaraan operasional kantor',
                'aktif'          => 1,
            ],
            [
                'nama_kendaraan' => 'Honda Vario',
                'nomor_polisi'   => 'B 5510 ABC',
                'jenis'          => 'Kendaraan Roda 2',
                'keterangan'     => 'Kendaraan operasional kantor',
                'aktif'          => 1,
            ],
        ];

        $this->db->table('kendaraan')->insertBatch($data);
    }
}
