<?php

namespace App\Libraries;

use App\Models\UserModel;
use Throwable;

class NotifikasiEmailService
{
    protected $email;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->email     = service('email');
        $this->userModel = new UserModel();
    }

    public function kirimAksiRole($roles, array $perjalanan, string $aksi, ?string $catatan = null): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        $users = $this->userModel
            ->whereIn('role', $roles)
            ->where('email !=', '')
            ->findAll();

        $emails = array_values(array_filter(array_column($users, 'email')));
        if (empty($emails)) {
            return false;
        }

        $subject = 'Notifikasi Perjalanan Dinas: ' . ($perjalanan['nomor_surat'] ?? '-');
        $message = $this->buildBody(
            $perjalanan,
            "Terdapat pengajuan perjalanan yang memerlukan aksi Anda: {$aksi}.",
            $catatan
        );

        return $this->send($emails, $subject, $message);
    }

    public function kirimStatusPemohon(array $perjalanan, string $statusLabel, ?string $catatan = null): bool
    {
        if (empty($perjalanan['user_id'])) {
            return false;
        }

        $pemohon = $this->userModel->find($perjalanan['user_id']);
        if (! $pemohon || empty($pemohon['email'])) {
            return false;
        }

        $subject = 'Update Status Perjalanan Dinas: ' . ($perjalanan['nomor_surat'] ?? '-');
        $message = $this->buildBody(
            $perjalanan,
            "Status pengajuan Anda saat ini: {$statusLabel}.",
            $catatan
        );

        return $this->send([$pemohon['email']], $subject, $message);
    }

    protected function buildBody(array $perjalanan, string $headline, ?string $catatan = null): string
    {
        $nomor  = $perjalanan['nomor_surat'] ?? '-';
        $tujuan = $perjalanan['tujuan'] ?? '-';
        $kota   = $perjalanan['kota_tujuan'] ?? '-';
        $tglB   = $perjalanan['tanggal_berangkat'] ?? '-';
        $tglP   = $perjalanan['tanggal_pulang'] ?? '-';

        $body = "{$headline}\n\n"
            . "Nomor Surat: {$nomor}\n"
            . "Tujuan: {$tujuan} ({$kota})\n"
            . "Tanggal Berangkat: {$tglB}\n"
            . "Tanggal Pulang: {$tglP}\n";

        if (! empty($catatan)) {
            $body .= "\nCatatan:\n{$catatan}\n";
        }

        $body .= "\nSilakan login ke aplikasi untuk detail lengkap.";

        return $body;
    }

    protected function send(array $to, string $subject, string $message): bool
    {
        try {
            $config   = config('Email');
            $fromMail = $config->fromEmail ?: 'no-reply@jaldin.local';
            $fromName = $config->fromName ?: 'Sistem Jaldin';

            $this->email->clear(true);
            $this->email->setFrom($fromMail, $fromName);
            $this->email->setTo($to);
            $this->email->setSubject($subject);
            $this->email->setMessage($message);

            if (! $this->email->send()) {
                log_message('error', 'Gagal kirim email notifikasi: {error}', [
                    'error' => trim(strip_tags($this->email->printDebugger(['headers']))),
                ]);
                return false;
            }

            return true;
        } catch (Throwable $e) {
            log_message('error', 'Exception saat kirim email notifikasi: {msg}', ['msg' => $e->getMessage()]);
            return false;
        }
    }
}
