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

    public function __construct()
    {
        $this->perjalananModel = new PerjalananDinasModel();
        $this->rincianModel    = new RincianBiayaModel();
        $this->dokumenModel    = new DokumenPerjalananModel();
        $this->logModel        = new ApprovalLogModel();
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
}
