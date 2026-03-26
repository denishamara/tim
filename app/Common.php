<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (! function_exists('sppd_get_notification_count')) {
	function sppd_get_notification_count(\CodeIgniter\Database\BaseConnection $db, ?int $userId, string $role, array $statuses): int
	{
		if (empty($statuses)) {
			return 0;
		}

		$builder = $db->table('perjalanan_dinas');
		$builder->whereIn('status', $statuses);

		if ($role === 'pegawai' && $userId) {
			$builder->where('user_id', $userId);
		}

		return (int) $builder->countAllResults();
	}
}

if (! function_exists('sppd_sidebar_notifications')) {
	function sppd_sidebar_notifications(): array
	{
		$role   = (string) (session()->get('role') ?? '');
		$userId = session()->get('user_id') ? (int) session()->get('user_id') : null;

		if ($role === '') {
			return ['total' => 0, 'items' => []];
		}

		$db = \Config\Database::connect();
		$items = [];

		if ($role === 'pegawai') {
			$items = [
				[
					'label' => 'Pengajuan Ditolak',
					'count' => sppd_get_notification_count($db, $userId, $role, ['rejected_1', 'rejected_2']),
					'href'  => '/pegawai',
				],
				[
					'label' => 'Menunggu Proses',
					'count' => sppd_get_notification_count($db, $userId, $role, ['draft', 'approved_1', 'processed_admin', 'approved_2']),
					'href'  => '/pegawai',
				],
			];
		} elseif ($role === 'admin') {
			$items = [
				[
					'label' => 'Perlu Dirinci',
					'count' => sppd_get_notification_count($db, $userId, $role, ['approved_1']),
					'href'  => '/admin',
				],
			];
		} elseif ($role === 'direktur') {
			$items = [
				[
					'label' => 'Approve Perjalanan',
					'count' => sppd_get_notification_count($db, $userId, $role, ['draft']),
					'href'  => '/direktur',
				],
				[
					'label' => 'Approve Rincian',
					'count' => sppd_get_notification_count($db, $userId, $role, ['processed_admin']),
					'href'  => '/direktur',
				],
			];
		} elseif ($role === 'keuangan') {
			$items = [
				[
					'label' => 'Menunggu Pencairan',
					'count' => sppd_get_notification_count($db, $userId, $role, ['approved_2']),
					'href'  => '/keuangan',
				],
				[
					'label' => 'Selesai Dicatat',
					'count' => sppd_get_notification_count($db, $userId, $role, ['completed']),
					'href'  => '/keuangan',
				],
			];
		}

		$total = array_sum(array_map(static fn(array $item): int => (int) $item['count'], $items));

		return [
			'total' => $total,
			'items' => $items,
		];
	}
}

if (! function_exists('sppd_process_timeline')) {
	function sppd_process_timeline(array $perjalanan, array $logs): array
	{
		$logByStatus = [];
		foreach ($logs as $log) {
			if (! empty($log['status'])) {
				$logByStatus[$log['status']] = $log;
			}
		}

		$formatTime = static function (?string $datetime): string {
			if (empty($datetime)) {
				return '-';
			}

			$ts = strtotime($datetime);
			return $ts ? date('d M Y H:i', $ts) : '-';
		};

		$buildDetail = static function (?array $log, string $fallback): string {
			if (! $log) {
				return $fallback;
			}

			$role = ucfirst((string) ($log['role'] ?? ''));
			$name = (string) ($log['approver_name'] ?? '');
			$catatan = trim((string) ($log['catatan'] ?? ''));

			if ($role !== '' || $name !== '') {
				$actor = trim($role . ($name !== '' ? ': ' . $name : ''));
				return $catatan !== '' ? $actor . ' - ' . $catatan : $actor;
			}

			return $catatan !== '' ? $catatan : $fallback;
		};

		$rejected1 = $logByStatus['rejected_1'] ?? null;
		$approved1 = $logByStatus['approved_1'] ?? null;
		$processed = $logByStatus['processed_admin'] ?? null;
		$rejected2 = $logByStatus['rejected_2'] ?? null;
		$approved2 = $logByStatus['approved_2'] ?? null;
		$completed = $logByStatus['completed'] ?? null;

		$pegawaiName = (string) ($perjalanan['pegawai_name'] ?? 'Pegawai');

		$items = [];
		$items[] = [
			'label'  => 'Pengajuan dibuat',
			'state'  => 'done',
			'detail' => 'Pegawai: ' . $pegawaiName,
			'time'   => $formatTime($perjalanan['created_at'] ?? null),
		];

		if ($rejected1) {
			$items[] = [
				'label'  => 'Persetujuan Direktur (Tahap 1)',
				'state'  => 'rejected',
				'detail' => $buildDetail($rejected1, 'Ditolak oleh direktur.'),
				'time'   => $formatTime($rejected1['approved_at'] ?? null),
			];
		} elseif ($approved1) {
			$items[] = [
				'label'  => 'Persetujuan Direktur (Tahap 1)',
				'state'  => 'done',
				'detail' => $buildDetail($approved1, 'Disetujui oleh direktur.'),
				'time'   => $formatTime($approved1['approved_at'] ?? null),
			];
		} else {
			$items[] = [
				'label'  => 'Persetujuan Direktur (Tahap 1)',
				'state'  => 'pending',
				'detail' => 'Menunggu persetujuan direktur.',
				'time'   => '-',
			];
		}

		if ($processed) {
			$items[] = [
				'label'  => 'Perincian Biaya oleh Admin',
				'state'  => 'done',
				'detail' => $buildDetail($processed, 'Rincian biaya diproses admin.'),
				'time'   => $formatTime($processed['approved_at'] ?? null),
			];
		} else {
			$items[] = [
				'label'  => 'Perincian Biaya oleh Admin',
				'state'  => 'pending',
				'detail' => 'Belum diproses admin.',
				'time'   => '-',
			];
		}

		if ($rejected2) {
			$items[] = [
				'label'  => 'Persetujuan Direktur (Tahap 2)',
				'state'  => 'rejected',
				'detail' => $buildDetail($rejected2, 'Rincian biaya ditolak direktur.'),
				'time'   => $formatTime($rejected2['approved_at'] ?? null),
			];
		} elseif ($approved2) {
			$items[] = [
				'label'  => 'Persetujuan Direktur (Tahap 2)',
				'state'  => 'done',
				'detail' => $buildDetail($approved2, 'Rincian biaya disetujui direktur.'),
				'time'   => $formatTime($approved2['approved_at'] ?? null),
			];
		} else {
			$items[] = [
				'label'  => 'Persetujuan Direktur (Tahap 2)',
				'state'  => 'pending',
				'detail' => 'Menunggu persetujuan direktur untuk rincian biaya.',
				'time'   => '-',
			];
		}

		if ($completed) {
			$items[] = [
				'label'  => 'Pencatatan Keuangan',
				'state'  => 'done',
				'detail' => $buildDetail($completed, 'Dana operasional dicatat keuangan.'),
				'time'   => $formatTime($completed['approved_at'] ?? null),
			];
		} else {
			$items[] = [
				'label'  => 'Pencatatan Keuangan',
				'state'  => 'pending',
				'detail' => 'Belum dicatat bagian keuangan.',
				'time'   => '-',
			];
		}

		return $items;
	}
}
