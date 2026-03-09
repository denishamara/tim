<?php

namespace App\Controllers;

use App\Models\PerjalananDinasModel;
use App\Models\RincianBiayaModel;
use App\Models\DokumenPerjalananModel;
use App\Models\ApprovalLogModel;

class Keuangan extends BaseController
{
    protected $perjalananModel;
    protected $rincianModel;
    protected $dokumenModel;
    protected $logModel;
    protected $db;

    public function __construct()
    {
        $this->perjalananModel = new PerjalananDinasModel();
        $this->rincianModel    = new RincianBiayaModel();
        $this->dokumenModel    = new DokumenPerjalananModel();
        $this->logModel        = new ApprovalLogModel();
        $this->db              = \Config\Database::connect();
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

        return redirect()->to('/keuangan')->with('success', 'Perjalanan telah diselesaikan dan dicatat di sistem keuangan.');
    }

    /**
     * Financial Report Page with filters
     */
    public function laporan()
    {
        // Get filter parameters from request
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01'); // First day of current month
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');     // Today
        $status    = $this->request->getGet('status') ?? 'all';
        $userId    = $this->request->getGet('user_id') ?? null;

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

        $builder->orderBy('pd.tanggal_berangkat', 'DESC');
        $perjalanan = $builder->get()->getResultArray();

        // Calculate statistics
        $totalPerjalanan = count($perjalanan);
        $totalBiaya = 0;
        $biayaPerStatus = [];
        $biayaPerJenis = [];

        foreach ($perjalanan as &$p) {
            // Get rincian biaya for each perjalanan
            $rincian = $this->rincianModel->getByPerjalanan($p['id']);
            $p['rincian'] = $rincian;
            
            $subtotal = 0;
            foreach ($rincian as $r) {
                $subtotal += $r['total'];
                
                // Group by jenis biaya
                $jenisNama = $r['nama_jenis_biaya'] ?? 'Lainnya';
                if (!isset($biayaPerJenis[$jenisNama])) {
                    $biayaPerJenis[$jenisNama] = 0;
                }
                $biayaPerJenis[$jenisNama] += $r['total'];
            }
            
            $p['total_biaya'] = $subtotal;
            $totalBiaya += $subtotal;

            // Group by status
            if (!isset($biayaPerStatus[$p['status']])) {
                $biayaPerStatus[$p['status']] = ['count' => 0, 'total' => 0];
            }
            $biayaPerStatus[$p['status']]['count']++;
            $biayaPerStatus[$p['status']]['total'] += $subtotal;
        }

        // Get all users for filter dropdown
        $userModel = new \App\Models\UserModel();
        $users = $userModel->findAll();

        // Sort biaya per jenis by total (descending)
        arsort($biayaPerJenis);

        $data = [
            'title'           => 'Laporan Keuangan',
            'perjalanan'      => $perjalanan,
            'totalPerjalanan' => $totalPerjalanan,
            'totalBiaya'      => $totalBiaya,
            'avgBiaya'        => $totalPerjalanan > 0 ? $totalBiaya / $totalPerjalanan : 0,
            'biayaPerStatus'  => $biayaPerStatus,
            'biayaPerJenis'   => $biayaPerJenis,
            'users'           => $users,
            'filters'         => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'status'     => $status,
                'user_id'    => $userId,
            ],
        ];

        return view('keuangan/laporan', $data);
    }

    /**
     * Export financial report to PDF
     */
    public function exportPdf()
    {
        // Get filter parameters
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');
        $status    = $this->request->getGet('status') ?? 'all';
        $userId    = $this->request->getGet('user_id') ?? null;

        // Build query (same as laporan method)
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

        $builder->orderBy('pd.tanggal_berangkat', 'DESC');
        $perjalanan = $builder->get()->getResultArray();

        // Calculate totals
        $totalBiaya = 0;
        foreach ($perjalanan as &$p) {
            $rincian = $this->rincianModel->getByPerjalanan($p['id']);
            $p['rincian'] = $rincian;
            
            $subtotal = 0;
            foreach ($rincian as $r) {
                $subtotal += $r['total'];
            }
            $p['total_biaya'] = $subtotal;
            $totalBiaya += $subtotal;
        }

        $data = [
            'perjalanan'      => $perjalanan,
            'totalPerjalanan' => count($perjalanan),
            'totalBiaya'      => $totalBiaya,
            'startDate'       => $startDate,
            'endDate'         => $endDate,
        ];

        return view('print/laporan_keuangan', $data);
    }

    /**
     * Export financial report to Excel (CSV format)
     */
    public function exportExcel()
    {
        // Get filter parameters
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-d');
        $status    = $this->request->getGet('status') ?? 'all';
        $userId    = $this->request->getGet('user_id') ?? null;

        // Build query
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

        $builder->orderBy('pd.tanggal_berangkat', 'DESC');
        $perjalanan = $builder->get()->getResultArray();

        // Prepare CSV data
        $csvData = [];
        $csvData[] = ['No', 'Nomor Surat', 'Pegawai', 'Tujuan', 'Tanggal Berangkat', 'Tanggal Pulang', 'Status', 'Total Biaya'];

        $no = 1;
        foreach ($perjalanan as $p) {
            $totalBiaya = $this->rincianModel->getTotalByPerjalanan($p['id']);
            
            $csvData[] = [
                $no++,
                $p['nomor_surat'],
                $p['pegawai_name'],
                $p['tujuan'],
                $p['tanggal_berangkat'],
                $p['tanggal_pulang'],
                $this->getStatusLabel($p['status']),
                number_format($totalBiaya, 0, ',', '.'),
            ];
        }

        // Set headers for download
        $filename = 'laporan_keuangan_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
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
