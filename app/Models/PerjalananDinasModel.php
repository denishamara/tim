<?php

namespace App\Models;

use CodeIgniter\Model;

class PerjalananDinasModel extends Model
{
    protected $table            = 'perjalanan_dinas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'nomor_surat', 'user_id', 'tujuan', 'kota_tujuan',
        'tanggal_berangkat', 'jam_berangkat', 'tanggal_pulang', 'jam_pulang',
        'keperluan', 'catatan', 'total_pengajuan', 'status'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWithUser($id = null)
    {
        $builder = $this->db->table('perjalanan_dinas pd');
        $builder->select('pd.*, u.name as pegawai_name, u.email as pegawai_email');
        $builder->join('users u', 'u.id = pd.user_id');
        if ($id) {
            $builder->where('pd.id', $id);
            return $builder->get()->getRowArray();
        }
        return $builder->orderBy('pd.created_at', 'DESC')->get()->getResultArray();
    }

    public function getByStatusWithUser(array $statuses)
    {
        $builder = $this->db->table('perjalanan_dinas pd');
        $builder->select('pd.*, u.name as pegawai_name, u.email as pegawai_email');
        $builder->join('users u', 'u.id = pd.user_id');
        $builder->whereIn('pd.status', $statuses);
        $builder->orderBy('pd.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function generateNomorSurat()
    {
        $year  = date('Y');
        $month = date('m');
        $count = $this->where('YEAR(created_at)', $year)
                      ->where('MONTH(created_at)', $month)
                      ->countAllResults() + 1;
        return sprintf('SPD/%s/%s/%04d', $year, $month, $count);
    }
}
