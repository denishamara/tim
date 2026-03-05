<?php

namespace App\Models;

use CodeIgniter\Model;

class ApprovalLogModel extends Model
{
    protected $table            = 'approval_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'perjalanan_id', 'approved_by', 'role', 'status', 'catatan', 'approved_at'
    ];
    protected $useTimestamps = false;

    public function getLogsByPerjalanan($perjalananId)
    {
        $builder = $this->db->table('approval_logs al');
        $builder->select('al.*, u.name as approver_name');
        $builder->join('users u', 'u.id = al.approved_by', 'left');
        $builder->where('al.perjalanan_id', $perjalananId);
        $builder->orderBy('al.approved_at', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function getLastRejectionCatatanMap(array $perjalananIds)
    {
        if (empty($perjalananIds)) return [];
        $results = $this->db->table('approval_logs')
            ->select('perjalanan_id, catatan, approved_at')
            ->whereIn('perjalanan_id', $perjalananIds)
            ->whereIn('status', ['rejected_1', 'rejected_2'])
            ->orderBy('approved_at', 'DESC')
            ->get()->getResultArray();

        $map = [];
        foreach ($results as $r) {
            if (! isset($map[$r['perjalanan_id']])) {
                $map[$r['perjalanan_id']] = $r['catatan'];
            }
        }
        return $map;
    }
}
