<?php

namespace App\Controllers;

use App\Models\PerjalananDinasModel;
use App\Models\RincianBiayaModel;
use App\Models\DokumenPerjalananModel;
use App\Models\ApprovalLogModel;
use App\Models\UserModel;

class Cetak extends BaseController
{
    public function index($id)
    {
        $perjalananModel = new PerjalananDinasModel();
        $rincianModel    = new RincianBiayaModel();
        $logModel        = new ApprovalLogModel();
        $userModel       = new UserModel();

        $perjalanan = $perjalananModel->getWithUser($id);
        if (! $perjalanan) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Get the admin who created rincian
        $adminLog = $logModel->where('perjalanan_id', $id)
                             ->where('role', 'admin')
                             ->orderBy('id', 'DESC')
                             ->first();
        $adminUser = $adminLog ? $userModel->find($adminLog['approved_by']) : null;

        // Get direktur approval
        $direkturLog = $logModel->where('perjalanan_id', $id)
                                ->whereIn('status', ['approved_1', 'approved_2'])
                                ->orderBy('id', 'DESC')
                                ->first();
        $direkturUser = $direkturLog ? $userModel->find($direkturLog['approved_by']) : null;

        $data['perjalanan']   = $perjalanan;
        $data['rincian']      = $rincianModel->getByPerjalanan($id);
        $data['total']        = $rincianModel->getTotalByPerjalanan($id);
        $data['admin_user']   = $adminUser;
        $data['direktur_user'] = $direkturUser;
        $data['pegawai_name'] = $perjalanan['pegawai_name'];

        return view('print/sppd', $data);
    }
}
