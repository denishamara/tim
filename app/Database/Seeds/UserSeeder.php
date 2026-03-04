<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name'     => 'Admin Sistem',
                'email'    => 'admin@jaldin.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Direktur Utama',
                'email'    => 'direktur@jaldin.com',
                'password' => password_hash('direktur123', PASSWORD_DEFAULT),
                'role'     => 'direktur',
            ],
            [
                'name'     => 'Staff Keuangan',
                'email'    => 'keuangan@jaldin.com',
                'password' => password_hash('keuangan123', PASSWORD_DEFAULT),
                'role'     => 'keuangan',
            ],
            [
                'name'     => 'M. Nizar Zulmi Syaifullah',
                'email'    => 'nizar@jaldin.com',
                'password' => password_hash('pegawai123', PASSWORD_DEFAULT),
                'role'     => 'pegawai',
            ],
            [
                'name'     => 'Muhammad Mahfud Sahal',
                'email'    => 'mahfud@jaldin.com',
                'password' => password_hash('pegawai123', PASSWORD_DEFAULT),
                'role'     => 'pegawai',
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
