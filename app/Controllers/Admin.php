<?php

namespace App\Controllers;

use App\Libraries\NotifikasiEmailService;
use App\Models\PerjalananDinasModel;
use App\Models\RincianBiayaModel;
use App\Models\DokumenPerjalananModel;
use App\Models\ApprovalLogModel;
use App\Models\KendaraanModel;
use App\Models\JenisBiayaModel;
use App\Models\PerjalananPesertaModel;

class Admin extends BaseController
{
    protected $perjalananModel;
    protected $rincianModel;
    protected $dokumenModel;
    protected $logModel;
    protected $kendaraanModel;
    protected $jenisBiayaModel;
    protected $pesertaModel;
    protected NotifikasiEmailService $notifEmail;

    public function __construct()
    {
        $this->perjalananModel  = new PerjalananDinasModel();
        $this->rincianModel     = new RincianBiayaModel();
        $this->dokumenModel     = new DokumenPerjalananModel();
        $this->logModel         = new ApprovalLogModel();
        $this->kendaraanModel   = new KendaraanModel();
        $this->jenisBiayaModel  = new JenisBiayaModel();
        $this->pesertaModel     = new PerjalananPesertaModel();
        $this->notifEmail       = new NotifikasiEmailService();
    }

    public function index()
    {
        $data['pending']   = $this->perjalananModel->getByStatusWithUser(['approved_1']);
        $data['processed'] = $this->perjalananModel->getByStatusWithUser(['processed_admin', 'approved_2', 'rejected_2', 'sent_finance', 'completed']);
        $data['arsip']     = $this->perjalananModel->getByStatusWithUser(['draft', 'rejected_1']);
        $data['title']     = 'Dashboard Admin';
        return view('admin/index', $data);
    }

    public function testEmail()
    {
        $to = (string) session()->get('email');
        if ($to === '') {
            return redirect()->to('/admin')->with('error', 'Email akun admin tidak ditemukan.');
        }

        $email    = service('email');
        $cfg      = config('Email');
        $fromMail = $cfg->fromEmail ?: 'no-reply@jaldin.local';
        $fromName = $cfg->fromName ?: 'Sistem Jaldin';

        $email->clear(true);
        $email->setFrom($fromMail, $fromName);
        $email->setTo($to);
        $email->setSubject('Test Email Notifikasi Jaldin');
        $email->setMessage(
            "Halo " . session()->get('name') . ",\n\n"
            . "Ini adalah email test dari sistem Jaldin.\n"
            . "Waktu kirim: " . date('Y-m-d H:i:s') . "\n\n"
            . "Jika email ini masuk, konfigurasi SMTP Anda sudah berjalan."
        );

        if (! $email->send()) {
            log_message('error', 'Gagal kirim email test admin: {err}', [
                'err' => trim(strip_tags($email->printDebugger(['headers']))),
            ]);
            return redirect()->to('/admin')->with('error', 'Gagal kirim email test. Cek konfigurasi SMTP di .env.');
        }

        return redirect()->to('/admin')->with('success', 'Email test berhasil dikirim ke ' . $to);
    }

    public function show($id)
    {
        $perjalanan = $this->perjalananModel->getWithUser($id);
        if (! $perjalanan) {
            return redirect()->to('/admin')->with('error', 'Data tidak ditemukan.');
        }

        $data['perjalanan']  = $perjalanan;
        $data['dokumen']     = $this->dokumenModel->getByPerjalanan($id);
        $data['rincian']     = $this->rincianModel->getByPerjalanan($id);
        $data['logs']        = $this->logModel->getLogsByPerjalanan($id);
        $data['peserta']     = $this->pesertaModel->getByPerjalanan($id);
        $data['kendaraan']           = $this->kendaraanModel->getDropdown();
        $data['jenis_biaya']         = $this->jenisBiayaModel->getAktif();
        $data['busy_kendaraan_ids']  = ($perjalanan['status'] === 'approved_1')
            ? $this->kendaraanModel->getBusyKendaraanIds(
                $perjalanan['tanggal_berangkat'],
                $perjalanan['tanggal_pulang'],
                (int) $id
              )
            : [];
        $data['title']       = 'Detail & Proses Perjalanan';
        return view('admin/show', $data);
    }

    public function addRincian($id)
    {
        $perjalanan = $this->perjalananModel->find($id);
        if (! $perjalanan || $perjalanan['status'] !== 'approved_1') {
            return redirect()->to('/admin')->with('error', 'Tidak dapat menambah rincian biaya pada status ini.');
        }

        $jenisBiayaId = (int) $this->request->getPost('jenis_biaya_id');
        // Resolve judul and default satuan from master data
        $judul         = '';
        $satuanDefault = 'Kali';
        if ($jenisBiayaId) {
            $jenis         = $this->jenisBiayaModel->find($jenisBiayaId);
            $judul         = $jenis['nama']            ?? '';
            $satuanDefault = $jenis['satuan_default']  ?? 'Kali';
        }

        $keteranganArr  = (array) ($this->request->getPost('keterangan')   ?? []);
        $kendaraanArr   = (array) ($this->request->getPost('kendaraan_id') ?? []);
        $qtyArr         = (array) ($this->request->getPost('qty')          ?? []);
        $satuanArr      = (array) ($this->request->getPost('satuan')       ?? []);
        $hargaArr       = (array) ($this->request->getPost('harga')        ?? []);

        $inserted = 0;
        foreach ($keteranganArr as $i => $ket) {
            $ket = trim($ket);
            if ($ket === '') continue;

            $qty         = (float) ($qtyArr[$i]       ?? 1);
            $harga       = (float) ($hargaArr[$i]     ?? 0);
            $sat         = $satuanArr[$i]              ?? $satuanDefault;
            $kendaraanId = ($kendaraanArr[$i] ?? '') ?: null;
            $total       = $qty * $harga;

            $this->rincianModel->insert([
                'perjalanan_id'  => $id,
                'jenis_biaya_id' => $jenisBiayaId ?: null,
                'judul'          => $judul,
                'keterangan'     => $ket,
                'kendaraan_id'   => $kendaraanId,
                'uraian'         => $ket, // fallback for NOT NULL constraint
                'qty'            => $qty,
                'satuan'         => $sat,
                'harga'          => $harga,
                'total'          => $total,
            ]);
            $inserted++;
        }

        $msg = $inserted > 1
            ? "{$inserted} baris rincian biaya ditambahkan."
            : 'Rincian biaya ditambahkan.';

        return redirect()->to('/admin/show/' . $id)->with('success', $msg);
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

        $updatedPerjalanan = $this->perjalananModel->find($id);
        if ($updatedPerjalanan) {
            $this->notifEmail->kirimAksiRole(
                'direktur',
                $updatedPerjalanan,
                'Persetujuan Tahap 2 (Rincian Biaya)'
            );

            $this->notifEmail->kirimStatusPemohon(
                $updatedPerjalanan,
                'Diproses Admin (menunggu persetujuan direktur untuk rincian biaya)'
            );
        }

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

    // ─── Jenis Biaya Management ────────────────────────────────────────────────

    public function jenisBiaya()
    {
        $data['list']  = $this->jenisBiayaModel->orderBy('nama', 'ASC')->findAll();
        $data['title'] = 'Master Jenis Biaya';
        return view('admin/jenis_biaya', $data);
    }

    public function jenisBiayaStore()
    {
        $rules = [
            'nama'           => 'required|max_length[150]',
            'satuan_default' => 'required',
            'harga_default'  => 'required|decimal',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $id = (int) $this->request->getPost('id');
        $payload = [
            'nama'            => $this->request->getPost('nama'),
            'satuan_default'  => $this->request->getPost('satuan_default'),
            'harga_default'   => (float) str_replace(['.', ','], ['', '.'], $this->request->getPost('harga_default')),
            'keterangan'      => $this->request->getPost('keterangan'),
            'butuh_kendaraan' => $this->request->getPost('butuh_kendaraan') ? 1 : 0,
        ];

        if ($id) {
            $this->jenisBiayaModel->update($id, $payload);
            $msg = 'Jenis biaya berhasil diperbarui.';
        } else {
            $payload['aktif'] = 1;
            $this->jenisBiayaModel->insert($payload);
            $msg = 'Jenis biaya berhasil ditambahkan.';
        }

        return redirect()->to('/admin/jenis-biaya')->with('success', $msg);
    }

    public function jenisBiayaToggle($id)
    {
        $j = $this->jenisBiayaModel->find($id);
        if ($j) {
            $this->jenisBiayaModel->update($id, ['aktif' => $j['aktif'] ? 0 : 1]);
        }
        return redirect()->to('/admin/jenis-biaya')->with('success', 'Status jenis biaya diperbarui.');
    }

    public function jenisBiayaDelete($id)
    {
        $this->jenisBiayaModel->delete($id);
        return redirect()->to('/admin/jenis-biaya')->with('success', 'Jenis biaya dihapus.');
    }
}
