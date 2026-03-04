<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenPerjalananModel extends Model
{
    protected $table            = 'dokumen_perjalanan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'perjalanan_id', 'nama_file', 'path_file', 'uploaded_by'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getByPerjalanan($perjalananId)
    {
        return $this->where('perjalanan_id', $perjalananId)->findAll();
    }
}
