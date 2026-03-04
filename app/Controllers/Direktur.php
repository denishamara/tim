<?php

namespace App\Controllers;

use App\Models\PerjalananDinasModel;
use App\Models\RincianBiayaModel;
use App\Models\DokumenPerjalananModel;
use App\Models\ApprovalLogModel;

class Direktur extends BaseController
{
    protected $perjalananModel;
    protected $rincianModel;
    protected $dokumenModel;
    protected $logModel;

    public function __construct()
    {
        $this->perjalananModel = new PerjalananDinasModel();
        $this->rincianModel    = new RincianBiayaModel();
        $this->dokumenModel    = new DokumenPerjalananModel();
        $this->logModel        = new ApprovalLogModel();
    }

    public function index()
    {
        // Tahap 1: Approve perjalanan dari pegawai (status: draft)
        $data['pending_trip']    = $this->perjalananModel->getByStatusWithUser(['draft']);
        // Tahap 2: Approve rincian biaya dari admin (status: processed_admin)
        $data['pending_rincian'] = $this->perjalananModel->getByStatusWithUser(['processed_admin']);
        // Sudah diproses
        $data['processed']       = $this->perjalananModel->getByStatusWithUser(['approved_1', 'rejected_1', 'approved_2', 'rejected_2', 'sent_finance', 'completed']);
        $data['title']           = 'Dashboard Direktur';
        return view('direktur/index', $data);
    }

    public function show($id)
    {
        $perjalanan = $this->perjalananModel->getWithUser($id);
        if (! $perjalanan) {
            return redirect()->to('/direktur')->with('error', 'Data tidak ditemukan.');
        }

        $data['perjalanan'] = $perjalanan;
        $data['dokumen']    = $this->dokumenModel->getByPerjalanan($id);
        $data['rincian']    = $this->rincianModel->getByPerjalanan($id);
        $data['logs']       = $this->logModel->getLogsByPerjalanan($id);
        $data['title']      = 'Detail Perjalanan';
        return view('direktur/show', $data);
    }

    public function approve($id)
    {
        $perjalanan = $this->perjalananModel->find($id);
        $catatan    = $this->request->getPost('catatan') ?? '';

        if (! $perjalanan) return redirect()->to('/direktur')->with('error', 'Data tidak ditemukan.');

        if ($perjalanan['status'] === 'draft') {
            // Approve perjalanan (tahap 1)
            $this->perjalananModel->update($id, ['status' => 'approved_1']);
            $logStatus = 'approved_1';
        } elseif ($perjalanan['status'] === 'processed_admin') {
            // Approve rincian biaya (tahap 2)
            $this->perjalananModel->update($id, ['status' => 'approved_2']);
            $logStatus = 'approved_2';
        } else {
            return redirect()->to('/direktur')->with('error', 'Status tidak valid untuk disetujui.');
        }

        $this->logModel->insert([
            'perjalanan_id' => $id,
            'approved_by'   => session()->get('user_id'),
            'role'          => 'direktur',
            'status'        => $logStatus,
            'catatan'       => $catatan ?: 'Disetujui oleh direktur.',
            'approved_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/direktur')->with('success', 'Perjalanan berhasil disetujui.');
    }

    public function reject($id)
    {
        $perjalanan = $this->perjalananModel->find($id);
        $catatan    = $this->request->getPost('catatan') ?? 'Ditolak oleh direktur.';

        if (! $perjalanan) return redirect()->to('/direktur')->with('error', 'Data tidak ditemukan.');

        if ($perjalanan['status'] === 'draft') {
            $this->perjalananModel->update($id, ['status' => 'rejected_1']);
            $logStatus = 'rejected_1';
        } elseif ($perjalanan['status'] === 'processed_admin') {
            $this->perjalananModel->update($id, ['status' => 'rejected_2']);
            $logStatus = 'rejected_2';
        } else {
            return redirect()->to('/direktur')->with('error', 'Status tidak valid untuk ditolak.');
        }

        $this->logModel->insert([
            'perjalanan_id' => $id,
            'approved_by'   => session()->get('user_id'),
            'role'          => 'direktur',
            'status'        => $logStatus,
            'catatan'       => $catatan,
            'approved_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/direktur')->with('success', 'Perjalanan berhasil ditolak.');
    }
}
