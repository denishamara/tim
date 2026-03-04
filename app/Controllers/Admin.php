<?php

namespace App\Controllers;

use App\Models\PerjalananDinasModel;
use App\Models\RincianBiayaModel;
use App\Models\DokumenPerjalananModel;
use App\Models\ApprovalLogModel;
use App\Models\KendaraanModel;

class Admin extends BaseController
{
    protected $perjalananModel;
    protected $rincianModel;
    protected $dokumenModel;
    protected $logModel;
    protected $kendaraanModel;

    public function __construct()
    {
        $this->perjalananModel = new PerjalananDinasModel();
        $this->rincianModel    = new RincianBiayaModel();
        $this->dokumenModel    = new DokumenPerjalananModel();
        $this->logModel        = new ApprovalLogModel();
        $this->kendaraanModel  = new KendaraanModel();
    }

    public function index()
    {
        $data['pending']   = $this->perjalananModel->getByStatusWithUser(['approved_1']);
        $data['processed'] = $this->perjalananModel->getByStatusWithUser(['processed_admin', 'approved_2', 'rejected_2', 'sent_finance', 'completed']);
        $data['arsip']     = $this->perjalananModel->getByStatusWithUser(['draft', 'rejected_1']);
        $data['title']     = 'Dashboard Admin';
        return view('admin/index', $data);
    }

    public function show($id)
    {
        $perjalanan = $this->perjalananModel->getWithUser($id);
        if (! $perjalanan) {
            return redirect()->to('/admin')->with('error', 'Data tidak ditemukan.');
        }

        $data['perjalanan'] = $perjalanan;
        $data['dokumen']    = $this->dokumenModel->getByPerjalanan($id);
        $data['rincian']    = $this->rincianModel->getByPerjalanan($id);
        $data['logs']       = $this->logModel->getLogsByPerjalanan($id);
        $data['kendaraan']  = $this->kendaraanModel->getDropdown();
        $data['title']      = 'Detail & Proses Perjalanan';
        return view('admin/show', $data);
    }

    public function addRincian($id)
    {
        $perjalanan = $this->perjalananModel->find($id);
        if (! $perjalanan || $perjalanan['status'] !== 'approved_1') {
            return redirect()->to('/admin')->with('error', 'Tidak dapat menambah rincian biaya pada status ini.');
        }

        $qty   = (float) $this->request->getPost('qty');
        $harga = (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $this->request->getPost('harga'));
        $total = $qty * $harga;

        $this->rincianModel->insert([
            'perjalanan_id' => $id,
            'judul'         => $this->request->getPost('judul'),
            'keterangan'    => $this->request->getPost('keterangan'),
            'kendaraan_id'  => $this->request->getPost('kendaraan_id') ?: null,
            'uraian'        => $this->request->getPost('keterangan'), // fallback for NOT NULL constraint
            'qty'           => $qty,
            'satuan'        => $this->request->getPost('satuan'),
            'harga'         => $harga,
            'total'         => $total,
        ]);

        return redirect()->to('/admin/show/' . $id)->with('success', 'Rincian biaya ditambahkan.');
    }

    public function deleteRincian($id, $rincianId)
    {
        $this->rincianModel->delete($rincianId);
        return redirect()->to('/admin/show/' . $id)->with('success', 'Rincian biaya dihapus.');
    }

    public function submitToDirector($id)
    {
        $perjalanan = $this->perjalananModel->find($id);
        if (! $perjalanan || $perjalanan['status'] !== 'approved_1') {
            return redirect()->to('/admin')->with('error', 'Status tidak valid untuk dikirim ke direktur.');
        }

        $totalBiaya = $this->rincianModel->getTotalByPerjalanan($id);

        $this->perjalananModel->update($id, [
            'status'          => 'processed_admin',
            'total_pengajuan' => $totalBiaya,
        ]);

        $this->logModel->insert([
            'perjalanan_id' => $id,
            'approved_by'   => session()->get('user_id'),
            'role'          => 'admin',
            'status'        => 'processed_admin',
            'catatan'       => 'Perincian biaya telah disiapkan oleh admin, menunggu persetujuan direktur.',
            'approved_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin')->with('success', 'Perjalanan telah dikirim ke direktur untuk persetujuan rincian biaya.');
    }

    public function arsip()
    {
        $data['list']  = $this->perjalananModel->getByStatusWithUser(['draft', 'rejected_1', 'approved_1', 'processed_admin', 'approved_2', 'rejected_2', 'sent_finance', 'completed']);
        $data['title'] = 'Arsip Semua Perjalanan';
        return view('admin/arsip', $data);
    }

    // ─── Kendaraan Management ──────────────────────────────────────────────────

    public function kendaraan()
    {
        $data['list']  = $this->kendaraanModel->orderBy('nama_kendaraan', 'ASC')->findAll();
        $data['title'] = 'Data Kendaraan';
        return view('admin/kendaraan', $data);
    }

    public function kendaraanStore()
    {
        $rules = [
            'nama_kendaraan' => 'required',
            'nomor_polisi'   => 'required',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->kendaraanModel->insert([
            'nama_kendaraan' => $this->request->getPost('nama_kendaraan'),
            'nomor_polisi'   => strtoupper($this->request->getPost('nomor_polisi')),
            'jenis'          => $this->request->getPost('jenis'),
            'keterangan'     => $this->request->getPost('keterangan'),
            'aktif'          => 1,
        ]);

        return redirect()->to('/admin/kendaraan')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function kendaraanToggle($id)
    {
        $k = $this->kendaraanModel->find($id);
        if ($k) {
            $this->kendaraanModel->update($id, ['aktif' => $k['aktif'] ? 0 : 1]);
        }
        return redirect()->to('/admin/kendaraan')->with('success', 'Status kendaraan diperbarui.');
    }

    public function kendaraanDelete($id)
    {
        $this->kendaraanModel->delete($id);
        return redirect()->to('/admin/kendaraan')->with('success', 'Kendaraan dihapus.');
    }
}
