<?php

namespace App\Controllers;

use App\Libraries\NotifikasiEmailService;
use App\Models\PerjalananDinasModel;
use App\Models\RincianBiayaModel;
use App\Models\DokumenPerjalananModel;
use App\Models\ApprovalLogModel;
use App\Models\JenisBiayaModel;
use App\Models\PerjalananPesertaModel;

class Keuangan extends BaseController
{
    protected $perjalananModel;
    protected $rincianModel;
    protected $dokumenModel;
    protected $logModel;
    protected $db;
    protected $jenisBiayaModel;
    protected $pesertaModel;
    protected NotifikasiEmailService $notifEmail;

    public function __construct()
    {
        $this->perjalananModel  = new PerjalananDinasModel();
        $this->rincianModel     = new RincianBiayaModel();
        $this->dokumenModel     = new DokumenPerjalananModel();
        $this->logModel         = new ApprovalLogModel();
        $this->db               = \Config\Database::connect();
        $this->jenisBiayaModel  = new JenisBiayaModel();
        $this->pesertaModel     = new PerjalananPesertaModel();
        $this->notifEmail       = new NotifikasiEmailService();
    }

    public function index()
    {
        $data['pending']   = $this->perjalananModel->getByStatusWithUser(['approved_2']);
        $data['completed'] = $this->perjalananModel->getByStatusWithUser(['sent_finance', 'completed']);
        $data['title']     = 'Dashboard Keuangan';
        return view('keuangan/index', $data);
    }

    public function show($id)
    {
        $perjalanan = $this->perjalananModel->getWithUser($id);
        if (! $perjalanan) {
            return redirect()->to('/keuangan')->with('error', 'Data tidak ditemukan.');
        }

        $data['perjalanan'] = $perjalanan;
        $data['dokumen']    = $this->dokumenModel->getByPerjalanan($id);
        $data['rincian']    = $this->rincianModel->getByPerjalanan($id);
        $data['logs']       = $this->logModel->getLogsByPerjalanan($id);
        $data['peserta']    = $this->pesertaModel->getByPerjalanan($id);
        $data['title']      = 'Detail Perjalanan - Keuangan';
        return view('keuangan/show', $data);
    }

    public function complete($id)
    {
        $perjalanan = $this->perjalananModel->find($id);
        if (! $perjalanan || ! in_array($perjalanan['status'], ['approved_2'])) {
            return redirect()->to('/keuangan')->with('error', 'Status tidak valid.');
        }

        $catatan = $this->request->getPost('catatan') ?? 'Dana operasional telah dicatat oleh bagian keuangan.';

        $this->perjalananModel->update($id, ['status' => 'completed']);

        $this->logModel->insert([
            'perjalanan_id' => $id,
            'approved_by'   => session()->get('user_id'),
            'role'          => 'keuangan',
            'status'        => 'completed',
            'catatan'       => $catatan,
            'approved_at'   => date('Y-m-d H:i:s'),
        ]);

        $updatedPerjalanan = $this->perjalananModel->find($id);
        if ($updatedPerjalanan) {
            $this->notifEmail->kirimStatusPemohon($updatedPerjalanan, 'Selesai (Keuangan)', $catatan ?: null);
        }

        return redirect()->to('/keuangan')->with('success', 'Perjalanan telah diselesaikan dan dicatat di sistem keuangan.');
    }

    /**
     * Financial Report Page with filters
     */
    public function laporan()
    {
        // Get filter parameters from request
        $startDate   = $this->request->getGet('start_date')    ?? date('Y-m-01');
        $endDate     = $this->request->getGet('end_date')      ?? date('Y-m-d');
        $status      = $this->request->getGet('status')        ?? 'all';
        $userId      = $this->request->getGet('user_id')       ?? null;
        $jenisBiayaId = $this->request->getGet('jenis_biaya_id') ?? null;

        // Build query with filters
        $builder = $this->db->table('perjalanan_dinas pd');
        $builder->select('pd.*, u.name as pegawai_name, u.email as pegawai_email');
        $builder->join('users u', 'u.id = pd.user_id');
        $builder->where('pd.tanggal_berangkat >=', $startDate);
        $builder->where('pd.tanggal_berangkat <=', $endDate);

        if ($status !== 'all') {
            $builder->where('pd.status', $status);
        }

        if ($userId) {
            $builder->where('pd.user_id', $userId);
        }

        // When filtering by jenis biaya, only include perjalanan that have at least one matching rincian
        if ($jenisBiayaId) {
            $builder->whereIn('pd.id', function ($subQuery) use ($jenisBiayaId) {
                $subQuery->select('perjalanan_id')
                         ->from('rincian_biaya')
                         ->where('jenis_biaya_id', $jenisBiayaId);
            });
        }

        $builder->orderBy('pd.tanggal_berangkat', 'DESC');
        $perjalanan = $builder->get()->getResultArray();

        // Calculate statistics
        $totalBiaya      = 0;
        $biayaPerStatus  = [];
        $biayaPerJenis   = [];

        foreach ($perjalanan as &$p) {
            $rincian  = $this->rincianModel->getByPerjalanan($p['id']);
            $p['rincian'] = $rincian;

            $subtotal = 0;
            foreach ($rincian as $r) {
                // Apply jenis biaya filter to cost aggregation
                if ($jenisBiayaId && $r['jenis_biaya_id'] != $jenisBiayaId) {
                    continue;
                }

                $subtotal += $r['total'];

                $jenisNama = $r['nama_jenis_biaya'] ?? ($r['judul'] ?? 'Lainnya');
                $biayaPerJenis[$jenisNama] = ($biayaPerJenis[$jenisNama] ?? 0) + $r['total'];
            }

            $p['total_biaya'] = $subtotal;
            $totalBiaya      += $subtotal;

            // Group by status
            $biayaPerStatus[$p['status']]['count'] = ($biayaPerStatus[$p['status']]['count'] ?? 0) + 1;
            $biayaPerStatus[$p['status']]['total'] = ($biayaPerStatus[$p['status']]['total'] ?? 0) + $subtotal;
        }
        unset($p);

        // Get all users and jenis biaya for filter dropdowns
        $userModel = new \App\Models\UserModel();
        $users         = $userModel->findAll();
        $jenisBiayaList = $this->jenisBiayaModel->orderBy('nama', 'ASC')->findAll();

        arsort($biayaPerJenis);

        $totalPerjalanan = count($perjalanan);

        $data = [
            'title'           => 'Laporan Keuangan',
            'perjalanan'      => $perjalanan,
            'totalPerjalanan' => $totalPerjalanan,
            'totalBiaya'      => $totalBiaya,
            'avgBiaya'        => $totalPerjalanan > 0 ? $totalBiaya / $totalPerjalanan : 0,
            'biayaPerStatus'  => $biayaPerStatus,
            'biayaPerJenis'   => $biayaPerJenis,
            'users'           => $users,
            'jenisBiayaList'  => $jenisBiayaList,
            'filters'         => [
                'start_date'    => $startDate,
                'end_date'      => $endDate,
                'status'        => $status,
                'user_id'       => $userId,
                'jenis_biaya_id'=> $jenisBiayaId,
            ],
        ];

        return view('keuangan/laporan', $data);
    }

    /**
     * Export financial report to PDF
     */
    public function exportPdf()
    {
        $startDate    = $this->request->getGet('start_date')     ?? date('Y-m-01');
        $endDate      = $this->request->getGet('end_date')       ?? date('Y-m-d');
        $status       = $this->request->getGet('status')         ?? 'all';
        $userId       = $this->request->getGet('user_id')        ?? null;
        $jenisBiayaId = $this->request->getGet('jenis_biaya_id') ?? null;

        $builder = $this->db->table('perjalanan_dinas pd');
        $builder->select('pd.*, u.name as pegawai_name, u.email as pegawai_email');
        $builder->join('users u', 'u.id = pd.user_id');
        $builder->where('pd.tanggal_berangkat >=', $startDate);
        $builder->where('pd.tanggal_berangkat <=', $endDate);

        if ($status !== 'all') { $builder->where('pd.status', $status); }
        if ($userId)           { $builder->where('pd.user_id', $userId); }
        if ($jenisBiayaId) {
            $builder->whereIn('pd.id', function ($sub) use ($jenisBiayaId) {
                $sub->select('perjalanan_id')->from('rincian_biaya')->where('jenis_biaya_id', $jenisBiayaId);
            });
        }

        $builder->orderBy('pd.tanggal_berangkat', 'DESC');
        $perjalanan = $builder->get()->getResultArray();

        $totalBiaya = 0;
        foreach ($perjalanan as &$p) {
            $rincian      = $this->rincianModel->getByPerjalanan($p['id']);
            $p['rincian'] = $rincian;
            $subtotal = 0;
            foreach ($rincian as $r) {
                if ($jenisBiayaId && $r['jenis_biaya_id'] != $jenisBiayaId) continue;
                $subtotal += $r['total'];
            }
            $p['total_biaya'] = $subtotal;
            $totalBiaya      += $subtotal;
        }
        unset($p);

        $jenisBiayaNama = null;
        if ($jenisBiayaId) {
            $jb = $this->jenisBiayaModel->find($jenisBiayaId);
            $jenisBiayaNama = $jb['nama'] ?? null;
        }

        $data = [
            'perjalanan'      => $perjalanan,
            'totalPerjalanan' => count($perjalanan),
            'totalBiaya'      => $totalBiaya,
            'startDate'       => $startDate,
            'endDate'         => $endDate,
            'jenisBiayaNama'  => $jenisBiayaNama,
        ];

        return view('print/laporan_keuangan', $data);
    }

    /**
     * Export financial report to Excel (CSV format)
     */
    public function exportExcel()
    {
        $startDate    = $this->request->getGet('start_date')     ?? date('Y-m-01');
        $endDate      = $this->request->getGet('end_date')       ?? date('Y-m-d');
        $status       = $this->request->getGet('status')         ?? 'all';
        $userId       = $this->request->getGet('user_id')        ?? null;
        $jenisBiayaId = $this->request->getGet('jenis_biaya_id') ?? null;

        $builder = $this->db->table('perjalanan_dinas pd');
        $builder->select('pd.*, u.name as pegawai_name, u.email as pegawai_email');
        $builder->join('users u', 'u.id = pd.user_id');
        $builder->where('pd.tanggal_berangkat >=', $startDate);
        $builder->where('pd.tanggal_berangkat <=', $endDate);

        if ($status !== 'all') { $builder->where('pd.status', $status); }
        if ($userId)           { $builder->where('pd.user_id', $userId); }
        if ($jenisBiayaId) {
            $builder->whereIn('pd.id', function ($sub) use ($jenisBiayaId) {
                $sub->select('perjalanan_id')->from('rincian_biaya')->where('jenis_biaya_id', $jenisBiayaId);
            });
        }

        $builder->orderBy('pd.tanggal_berangkat', 'DESC');
        $perjalanan = $builder->get()->getResultArray();

        // Add "Filter Jenis Biaya" column header only when filtered
        $csvData = [];
        $headers = ['No', 'Nomor Surat', 'Pegawai', 'Tujuan', 'Tanggal Berangkat', 'Tanggal Pulang', 'Status', 'Total Biaya'];
        if ($jenisBiayaId) {
            $headers[] = 'Keterangan Filter';
        }
        $csvData[] = $headers;

        $jenisBiayaNama = null;
        if ($jenisBiayaId) {
            $jb = $this->jenisBiayaModel->find($jenisBiayaId);
            $jenisBiayaNama = $jb['nama'] ?? null;
        }

        $no = 1;
        foreach ($perjalanan as $p) {
            $rincian  = $this->rincianModel->getByPerjalanan($p['id']);
            $subtotal = 0;
            foreach ($rincian as $r) {
                if ($jenisBiayaId && $r['jenis_biaya_id'] != $jenisBiayaId) continue;
                $subtotal += $r['total'];
            }

            $row = [
                $no++,
                $p['nomor_surat'],
                $p['pegawai_name'],
                $p['tujuan'],
                $p['tanggal_berangkat'],
                $p['tanggal_pulang'],
                $this->getStatusLabel($p['status']),
                number_format($subtotal, 0, ',', '.'),
            ];
            if ($jenisBiayaId) {
                $row[] = $jenisBiayaNama ?? '';
            }
            $csvData[] = $row;
        }

        $filename = 'laporan_keuangan_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }

    /**
     * Get status label in Indonesian
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'draft'         => 'Draft',
            'submitted'     => 'Diajukan',
            'approved_1'    => 'Disetujui Atasan',
            'rejected_1'    => 'Ditolak Atasan',
            'approved_2'    => 'Disetujui Direktur',
            'rejected_2'    => 'Ditolak Direktur',
            'sent_finance'  => 'Dikirim ke Keuangan',
            'completed'     => 'Selesai',
            'cancelled'     => 'Dibatalkan',
        ];

        return $labels[$status] ?? $status;
    }
}
