<?php

namespace App\Controllers;

use App\Models\PerjalananDinasModel;
use App\Models\DokumenPerjalananModel;
use App\Models\RincianBiayaModel;
use App\Models\ApprovalLogModel;

class Pegawai extends BaseController
{
    protected $perjalananModel;
    protected $dokumenModel;

    public function __construct()
    {
        $this->perjalananModel = new PerjalananDinasModel();
        $this->dokumenModel    = new DokumenPerjalananModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $data['perjalanan'] = $this->perjalananModel->where('user_id', $userId)
                                                    ->orderBy('created_at', 'DESC')
                                                    ->findAll();
        $data['title'] = 'Perjalanan Dinas Saya';
        return view('pegawai/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Ajukan Perjalanan Dinas';
        return view('pegawai/create', $data);
    }

    public function store()
    {
        $userId = session()->get('user_id');

        $rules = [
            'tujuan'             => 'required',
            'kota_tujuan'        => 'required',
            'tanggal_berangkat'  => 'required|valid_date',
            'tanggal_pulang'     => 'required|valid_date',
            'keperluan'          => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nomorSurat = $this->perjalananModel->generateNomorSurat();

        $id = $this->perjalananModel->insert([
            'nomor_surat'       => $nomorSurat,
            'user_id'           => $userId,
            'tujuan'            => $this->request->getPost('tujuan'),
            'kota_tujuan'       => $this->request->getPost('kota_tujuan'),
            'tanggal_berangkat' => $this->request->getPost('tanggal_berangkat'),
            'jam_berangkat'     => $this->request->getPost('jam_berangkat'),
            'tanggal_pulang'    => $this->request->getPost('tanggal_pulang'),
            'jam_pulang'        => $this->request->getPost('jam_pulang'),
            'keperluan'         => $this->request->getPost('keperluan'),
            'catatan'           => $this->request->getPost('catatan'),
            'status'            => 'draft',
        ]);

        // Upload dokumen
        $file = $this->request->getFile('dokumen');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);
            $this->dokumenModel->insert([
                'perjalanan_id' => $id,
                'nama_file'     => $file->getClientName(),
                'path_file'     => 'uploads/' . $newName,
                'uploaded_by'   => $userId,
            ]);
        }

        return redirect()->to('/pegawai')->with('success', 'Pengajuan berhasil dikirim dengan nomor ' . $nomorSurat);
    }

    public function show($id)
    {
        $userId     = session()->get('user_id');
        $perjalanan = $this->perjalananModel->getWithUser($id);

        if (! $perjalanan || $perjalanan['user_id'] != $userId) {
            return redirect()->to('/pegawai')->with('error', 'Data tidak ditemukan.');
        }

        $rincianModel = new RincianBiayaModel();
        $logModel     = new ApprovalLogModel();

        $data['perjalanan'] = $perjalanan;
        $data['dokumen']    = $this->dokumenModel->getByPerjalanan($id);
        $data['rincian']    = $rincianModel->getByPerjalanan($id);
        $data['logs']       = $logModel->getLogsByPerjalanan($id);
        $data['title']      = 'Detail Perjalanan Dinas';
        return view('pegawai/show', $data);
    }
}
