<?php

namespace App\Models;

use CodeIgniter\Model;

class PerjalananPesertaModel extends Model
{
    protected $table            = 'perjalanan_peserta';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'perjalanan_id', 'user_id', 'jabatan', 'keterangan'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all participants for a perjalanan with user details
     */
    public function getByPerjalanan($perjalananId)
    {
        return $this->select('perjalanan_peserta.*, users.name, users.email, users.phone, users.address')
                    ->join('users', 'users.id = perjalanan_peserta.user_id')
                    ->where('perjalanan_peserta.perjalanan_id', $perjalananId)
                    ->orderBy('perjalanan_peserta.created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Add participant to perjalanan
     */
    public function addPeserta($perjalananId, $userId, $jabatan = null, $keterangan = null)
    {
        // Check if participant already exists
        $existing = $this->where('perjalanan_id', $perjalananId)
                         ->where('user_id', $userId)
                         ->first();
        
        if ($existing) {
            return false; // Already exists
        }

        return $this->insert([
            'perjalanan_id' => $perjalananId,
            'user_id'       => $userId,
            'jabatan'       => $jabatan,
            'keterangan'    => $keterangan,
        ]);
    }

    /**
     * Remove participant from perjalanan
     */
    public function removePeserta($perjalananId, $userId)
    {
        return $this->where('perjalanan_id', $perjalananId)
                    ->where('user_id', $userId)
                    ->delete();
    }

    /**
     * Update all participants for a perjalanan (replace all)
     */
    public function updatePeserta($perjalananId, array $pesertaData)
    {
        // Delete existing participants
        $this->where('perjalanan_id', $perjalananId)->delete();

        // Insert new participants
        if (!empty($pesertaData)) {
            foreach ($pesertaData as $peserta) {
                $this->insert([
                    'perjalanan_id' => $perjalananId,
                    'user_id'       => $peserta['user_id'],
                    'jabatan'       => $peserta['jabatan'] ?? null,
                    'keterangan'    => $peserta['keterangan'] ?? null,
                ]);
            }
        }

        return true;
    }

    /**
     * Get count of participants for a perjalanan
     */
    public function countPeserta($perjalananId)
    {
        return $this->where('perjalanan_id', $perjalananId)->countAllResults();
    }

    /**
     * Check if user is participant in perjalanan
     */
    public function isPeserta($perjalananId, $userId)
    {
        return $this->where('perjalanan_id', $perjalananId)
                    ->where('user_id', $userId)
                    ->first() !== null;
    }
}
