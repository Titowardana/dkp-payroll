<?php namespace App\Controllers;

use App\Libraries\SlipLogger;
use App\Libraries\SlipPdfService;
use App\Models\ActivityLogModel;
use App\Models\PayrollDetailModel;
use App\Models\PayrollStatusLogModel;
use App\Models\PeriodeModel;

class Bendahara extends BaseController
{
    public function dashboard()
    {
        $selectedPeriodeId = (int) ($this->request->getGet('periode_id') ?? 0);

        $periodeModel = new PeriodeModel();
        $payroll      = new PayrollDetailModel();
        $logModel     = new ActivityLogModel();

        $periodes    = $periodeModel->orderBy('tahun', 'DESC')->orderBy('bulan', 'DESC')->findAll();
        $periodeLabel = 'Semua Periode (Global)';

        $base = $payroll;
        if ($selectedPeriodeId > 0) {
            $base = $payroll->where('periode_id', $selectedPeriodeId);
            $selectedPeriode = $periodeModel->find($selectedPeriodeId);
            if ($selectedPeriode) {
                $periodeLabel = $selectedPeriode['nama_periode'] ?? ($selectedPeriode['bulan'] . '/' . $selectedPeriode['tahun']);
            }
        }

        $pendingVerifikasi = (clone $base)->where('status', 'draft')->countAllResults();
        $sudahDiverifikasi = (clone $base)->where('status', 'verified')->countAllResults();
        $disetujui         = (clone $base)->where('status', 'approved')->countAllResults();
        $totalDibayar      = (float) ((clone $base)->selectSum('gaji_bersih')->where('status', 'paid')->first()['gaji_bersih'] ?? 0);

        $activities = $logModel->where('user_id', (int) session()->get('user_id'))->orderBy('created_at', 'DESC')->findAll(5);

        return $this->view('dashboard/bendahara', compact(
            'periodes','selectedPeriodeId','periodeLabel',
            'pendingVerifikasi','sudahDiverifikasi','disetujui','totalDibayar','activities'
        ) + [
            'title'      => 'Dashboard Bendahara',
            'pageTitle'  => 'Dashboard Bendahara',
            'activeMenu' => 'bendahara_dashboard',
        ]);
    }

    // ================================================================
    //  VERIFIKASI
    // ================================================================
    public function verifikasi()
    {
        if ($this->request->getMethod() === 'post') {
            $detailModel = new PayrollDetailModel();
            $db = \Config\Database::connect();

            // ── Bulk action ──────────────────────────────────────────
            $ids = $this->request->getPost('ids');
            if (!empty($ids) && is_array($ids)) {
                $ids = array_map('intval', $ids);
                
                $db->transStart();
                // Ambil data slip sebelum update (untuk log) dan pastikan berstatus draft
                $slips = $detailModel->withRelations()->whereIn('payroll_detail.id', $ids)->where('payroll_detail.status', 'draft')->findAll();
                
                if (!empty($slips)) {
                    $validIds = array_column($slips, 'id');
                    $detailModel->whereIn('id', $validIds)->set(['status' => 'verified'])->update();
                    SlipLogger::verifikasiMassal($slips);
                }
                $db->transComplete();
                
                if ($db->transStatus() === false) {
                    return redirect()->to(base_url('bendahara/verifikasi'))->with('vrf_err', 'Gagal memverifikasi slip (Database Error).');
                }
                
                return redirect()->to(base_url('bendahara/verifikasi'))
                    ->with('vrf_msg', count($slips) . ' slip berhasil diverifikasi sekaligus.');
            }

            // ── Single action ─────────────────────────────────────────
            $id = (int) $this->request->getPost('pay_id');
            $actionType = $this->request->getPost('action_type');
            $alasan = trim((string)$this->request->getPost('alasan_revisi'));

            if ($id > 0) {
                if ($actionType === 'reject' && empty($alasan)) {
                    return redirect()->to(base_url('bendahara/verifikasi'))->with('vrf_err', 'Alasan revisi wajib diisi!');
                }

                $db->transStart();
                $slip = $detailModel->withRelations()->where('payroll_detail.id', $id)->whereIn('payroll_detail.status', ['draft', 'verified'])->first();
                if ($slip) {
                    if ($actionType === 'reject') {
                        $detailModel->update($id, [
                            'status' => 'rejected',
                            'catatan_penolakan' => $alasan
                        ]);
                        SlipLogger::reject($slip, $alasan);
                        $msg = 'Slip dikembalikan ke Admin untuk direvisi.';
                    } elseif ($actionType === 'unverify') {
                        $detailModel->update($id, ['status' => 'draft']);
                        SlipLogger::unverify($slip);
                        $msg = 'Slip dikembalikan ke status Draft.';
                    } else {
                        $detailModel->update($id, ['status' => 'verified']);
                        SlipLogger::verifikasi($slip);
                        $msg = 'Slip berhasil diverifikasi.';
                    }
                }
                $db->transComplete();
                
                if ($db->transStatus() === false) {
                    return redirect()->to(base_url('bendahara/verifikasi'))->with('vrf_err', 'Gagal memproses slip (Database Error).');
                }
                if (!$slip) {
                    return redirect()->to(base_url('bendahara/verifikasi'))->with('vrf_err', 'Data tidak valid atau status sudah bukan draft.');
                }
                
                return redirect()->to(base_url('bendahara/verifikasi'))->with('vrf_msg', $msg);
            }
        }

        $periodeId = (int) ($this->request->getGet('periode_id') ?? 0);
        $model = (new PayrollDetailModel())->withRelations()->where('payroll_detail.status', 'draft')
            ->orderBy('periode_penggajian.tahun', 'DESC')->orderBy('periode_penggajian.bulan', 'DESC');
        if ($periodeId > 0) $model->where('payroll_detail.periode_id', $periodeId);

        $verifiedModel = (new PayrollDetailModel())->withRelations()->where('payroll_detail.status', 'verified')
            ->orderBy('periode_penggajian.tahun', 'DESC')->orderBy('periode_penggajian.bulan', 'DESC');
        if ($periodeId > 0) $verifiedModel->where('payroll_detail.periode_id', $periodeId);

        $histori = (new PayrollStatusLogModel())->getHistori(['aksi' => 'verifikasi'], 10);

        return $this->view('bendahara/verifikasi', [
            'title'            => 'Verifikasi Slip',
            'pageTitle'        => 'Verifikasi Slip',
            'activeMenu'       => 'verifikasi',
            'rows'             => $model->paginate(50),
            'pager'            => $model->pager,
            'verifiedRows'     => $verifiedModel->paginate(50),
            'verifiedPager'    => $verifiedModel->pager,
            'periodes'         => (new PeriodeModel())->orderBy('tahun','DESC')->orderBy('bulan','DESC')->findAll(),
            'selectedPeriodeId'=> $periodeId,
            'histori'          => $histori,
        ]);
    }

    // ================================================================
    //  APPROVAL
    // ================================================================
    public function approval()
    {
        if ($this->request->getMethod() === 'post') {
            $detailModel = new PayrollDetailModel();
            $db = \Config\Database::connect();

            // ── Bulk action ──────────────────────────────────────────
            $ids = $this->request->getPost('ids');
            if (!empty($ids) && is_array($ids)) {
                $ids   = array_map('intval', $ids);
                
                $db->transStart();
                $slips = $detailModel->withRelations()->whereIn('payroll_detail.id', $ids)->where('payroll_detail.status', 'verified')->findAll();
                if (!empty($slips)) {
                    $validIds = array_column($slips, 'id');
                    $detailModel->whereIn('id', $validIds)->set(['status' => 'approved'])->update();
                    SlipLogger::approvalMassal($slips);
                }
                $db->transComplete();
                
                if ($db->transStatus() === false) {
                    return redirect()->to(base_url('bendahara/approval'))->with('apr_err', 'Gagal menyetujui slip (Database Error).');
                }
                
                return redirect()->to(base_url('bendahara/approval'))
                    ->with('apr_msg', count($slips) . ' slip berhasil disetujui sekaligus.');
            }

            // ── Single action ─────────────────────────────────────────
            $id = (int) $this->request->getPost('pay_id');
            if ($id > 0) {
                $db->transStart();
                $slip = $detailModel->withRelations()->where('payroll_detail.id', $id)->where('payroll_detail.status', 'verified')->first();
                if ($slip) {
                    $detailModel->update($id, ['status' => 'approved']);
                    SlipLogger::approval($slip);
                }
                $db->transComplete();
                
                if ($db->transStatus() === false) {
                    return redirect()->to(base_url('bendahara/approval'))->with('apr_err', 'Gagal menyetujui slip (Database Error).');
                }
                if (!$slip) {
                    return redirect()->to(base_url('bendahara/approval'))->with('apr_err', 'Data tidak valid atau status sudah bukan verified.');
                }
                
                return redirect()->to(base_url('bendahara/approval'))
                    ->with('apr_msg', 'Slip berhasil disetujui.');
            }
        }

        $periodeId = (int) ($this->request->getGet('periode_id') ?? 0);
        $model = (new PayrollDetailModel())->withRelations()->where('payroll_detail.status', 'verified')
            ->orderBy('periode_penggajian.tahun', 'DESC')->orderBy('periode_penggajian.bulan', 'DESC');
        if ($periodeId > 0) $model->where('payroll_detail.periode_id', $periodeId);

        // Histori 10 aksi approval terakhir
        $histori = (new PayrollStatusLogModel())->getHistori(['aksi' => 'approval'], 10);

        return $this->view('bendahara/approval', [
            'title'            => 'Approval Slip',
            'pageTitle'        => 'Approval Slip',
            'activeMenu'       => 'approval',
            'rows'             => $model->paginate(50),
            'pager'            => $model->pager,
            'periodes'         => (new PeriodeModel())->orderBy('tahun','DESC')->orderBy('bulan','DESC')->findAll(),
            'selectedPeriodeId'=> $periodeId,
            'histori'          => $histori,
        ]);
    }

    // ================================================================
    //  FINALISASI
    // ================================================================
    public function finalisasi()
    {
        $db = db_connect();
        if ($this->request->getMethod() === 'post' && $this->request->getPost('finalize_id')) {
            $periodeId = (int) $this->request->getPost('finalize_id');
            
            $db->transStart();
            
            $cek = $db->query(
                "SELECT COUNT(*) AS total_slip,
                        SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS paid_count
                 FROM payroll_detail WHERE periode_id = ? FOR UPDATE", [$periodeId]
            )->getRowArray();

            if (!$cek || (int)$cek['total_slip'] === 0) {
                $db->transComplete();
                return redirect()->to(base_url('bendahara/finalisasi'))
                    ->with('fin_err', 'Tidak bisa finalisasi: belum ada slip pada periode ini.');
            }
            if ((int)$cek['paid_count'] !== (int)$cek['total_slip']) {
                $db->transComplete();
                return redirect()->to(base_url('bendahara/finalisasi'))
                    ->with('fin_err', 'Semua slip harus berstatus Paid sebelum periode dapat difinalisasi.');
            }

            $periode = (new PeriodeModel())->find($periodeId);
            (new PeriodeModel())->update($periodeId, ['status' => 'selesai']);
            
            SlipLogger::finalisasi(
                $periodeId,
                $periode['nama_periode'] ?? ($periode['bulan'].'/'.$periode['tahun']),
                (int) $cek['total_slip']
            );
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                return redirect()->to(base_url('bendahara/finalisasi'))->with('fin_err', 'Gagal memfinalisasi periode (Database Error).');
            }

            return redirect()->to(base_url('bendahara/finalisasi'))
                ->with('fin_msg', 'Periode berhasil difinalisasi.');
        }

        $rows = $db->query(
            "SELECT p.id, p.nama_periode, p.bulan, p.tahun, p.status,
                SUM(CASE WHEN pd.status='draft'    THEN 1 ELSE 0 END) AS draft_count,
                SUM(CASE WHEN pd.status='verified' THEN 1 ELSE 0 END) AS verified_count,
                SUM(CASE WHEN pd.status='approved' THEN 1 ELSE 0 END) AS approved_count,
                SUM(CASE WHEN pd.status='paid'     THEN 1 ELSE 0 END) AS paid_count,
                SUM(CASE WHEN pd.status!='paid'    THEN 1 ELSE 0 END) AS belum_bayar,
                COUNT(pd.id) AS total_slip,
                SUM(pd.gaji_bersih) AS total_gaji
             FROM periode_penggajian p
             LEFT JOIN payroll_detail pd ON p.id = pd.periode_id
             WHERE p.status IN ('draft','proses','selesai')
             GROUP BY p.id
             ORDER BY p.tahun DESC, p.bulan DESC"
        )->getResultArray();

        return $this->view('bendahara/finalisasi', [
            'title'      => 'Finalisasi Periode',
            'pageTitle'  => 'Finalisasi Periode',
            'activeMenu' => 'finalisasi',
            'rows'       => $rows,
        ]);
    }

    // ================================================================
    //  RINCIAN DETAIL PER PEGAWAI
    // ================================================================
    public function detail($periodeId)
    {
        $periode = (new PeriodeModel())->find((int) $periodeId);
        if (!$periode) return redirect()->to(base_url('bendahara/finalisasi'));

        $model = (new PayrollDetailModel())->withRelations()
            ->where('payroll_detail.periode_id', (int) $periodeId)
            ->orderBy('pegawai.nama', 'ASC');

        return $this->view('bendahara/finalisasi_detail', [
            'title'      => 'Rincian Penggajian - ' . ($periode['nama_periode'] ?? ''),
            'pageTitle'  => 'Rincian Per Pegawai',
            'activeMenu' => 'finalisasi',
            'rows'       => $model->paginate(50),
            'pager'      => $model->pager,
            'periode'    => $periode,
        ]);
    }

    // ================================================================
    //  BAYAR
    // ================================================================
    public function bayar()
    {
        if ($this->request->getMethod() !== 'post') return redirect()->to(base_url('bendahara/finalisasi'));

        $periodeId = (int) $this->request->getPost('periode_id');
        $id        = (int) $this->request->getPost('pay_id');
        $payAll    = (int) $this->request->getPost('pay_all');

        $db = \Config\Database::connect();
        $db->transStart();

        $detailModel = new PayrollDetailModel();
        
        $processed = 0;

        if ($payAll === 1) {
            $slips = $detailModel->withRelations()
                ->where('payroll_detail.periode_id', $periodeId)
                ->where('payroll_detail.status', 'approved')
                ->findAll();
            
            foreach ($slips as $slip) {
                $detailModel->update($slip['id'], [
                    'status'       => 'paid',
                    'tanggal_bayar'=> date('Y-m-d H:i:s'),
                ]);
                $processed++;
            }
            if ($processed > 0) {
                $periodeNama = $slips[0]['nama_periode'] ?? '';
                SlipLogger::bayarBulk($processed, $periodeId, $periodeNama);
            }
        } else {
            $slip = $detailModel->withRelations()->where('payroll_detail.id', $id)->where('payroll_detail.status', 'approved')->first();
            
            if ($slip) {
                $detailModel->update($id, [
                    'status'       => 'paid',
                    'tanggal_bayar'=> date('Y-m-d H:i:s'),
                ]);
                SlipLogger::bayar($slip, $periodeId);
                $processed++;
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false || $processed === 0) {
            return redirect()->to(base_url('bendahara/finalisasi/detail/' . $periodeId))
                ->with('fnd_err', 'Gagal memproses pembayaran atau tidak ada slip yang valid.');
        }

        $msg = $payAll === 1 ? "$processed slip berhasil ditandai sebagai Paid." : "Slip berhasil ditandai sebagai Paid.";
        return redirect()->to(base_url('bendahara/finalisasi/detail/' . $periodeId))
            ->with('fnd_msg', $msg);
    }

    // ================================================================
    //  HISTORI AKSI (halaman penuh)
    // ================================================================
    public function histori()
    {
        if (strtolower($this->request->getMethod()) === 'post' && $this->request->getPost('action_clear') === 'all') {
            $db = \Config\Database::connect();
            $db->table('payroll_status_logs')->emptyTable();
            return redirect()->to(base_url('bendahara/histori'))->with('hst_msg', 'Seluruh riwayat histori berhasil dihapus secara permanen.');
        }

        $aksi      = $this->request->getGet('aksi') ?? '';
        $periodeId = (int) ($this->request->getGet('periode_id') ?? 0);
        $userId    = (int) ($this->request->getGet('user_id') ?? 0);

        $filter = [];
        if ($aksi)      $filter['aksi']      = $aksi;
        if ($periodeId) $filter['periode_id'] = $periodeId;
        if ($userId)    $filter['user_id']    = $userId;

        $logModel = new PayrollStatusLogModel();
        $logModel->select('payroll_status_logs.*, u.full_name AS user_fullname')
            ->join('users u', 'u.id = payroll_status_logs.user_id', 'left');
        if ($aksi)      $logModel->where('payroll_status_logs.aksi', $aksi);
        if ($periodeId) $logModel->where('payroll_status_logs.periode_id', $periodeId);
        if ($userId)    $logModel->where('payroll_status_logs.user_id', $userId);
        $logModel->orderBy('payroll_status_logs.created_at', 'DESC');
        $rows = $logModel->paginate(50);
        $summary  = $logModel->getSummary();
        $periodes = (new PeriodeModel())->orderBy('tahun','DESC')->orderBy('bulan','DESC')->findAll();

        return $this->view('bendahara/histori', [
            'title'            => 'Histori Aksi',
            'pageTitle'        => 'Histori Aksi',
            'activeMenu'       => 'histori',
            'rows'             => $rows,
            'pager'            => $logModel->pager,
            'summary'          => $summary,
            'periodes'         => $periodes,
            'filterAksi'       => $aksi,
            'selectedPeriodeId'=> $periodeId,
        ]);
    }
    // ================================================================
    //  EXPORT PDF REKAP FINALISASI
    // ================================================================
    public function exportPdfRekap($periodeId)
    {
        $periode = (new PeriodeModel())->find((int) $periodeId);
        if (!$periode) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Periode tidak ditemukan.');

        $rows = (new PayrollDetailModel())->withRelations()
            ->where('payroll_detail.periode_id', (int) $periodeId)
            ->orderBy('pegawai.nama', 'ASC')
            ->findAll();

        $html = view('bendahara/pdf_rekap_finalisasi', [
            'periode' => $periode,
            'rows'    => $rows,
            'totalPegawai' => count($rows),
            'totalGapok'  => array_sum(array_column($rows, 'gaji_pokok')),
            'totalPend'   => array_sum(array_column($rows, 'total_pendapatan')),
            'totalPot'    => array_sum(array_column($rows, 'total_potongan')),
            'totalBersih' => array_sum(array_column($rows, 'gaji_bersih')),
            'sudahPaid'   => count(array_filter($rows, function($r) { return $r['status'] === 'paid'; })),
            'belumPaid'   => count($rows) - count(array_filter($rows, function($r) { return $r['status'] === 'paid'; }))
        ]);

        if (! class_exists(\Dompdf\Dompdf::class)) {
            $composerVendor = defined('ROOTPATH') ? ROOTPATH . 'vendor/autoload.php' : dirname(__DIR__, 2) . '/vendor/autoload.php';
            if (file_exists($composerVendor)) {
                require_once $composerVendor;
            }
        }

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'REKAP_FINALISASI_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', trim((string)($periode['nama_periode'] ?? $periode['bulan'].'_'.$periode['tahun']))) . '.pdf';
        
        $pdf = $dompdf->output();
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($pdf);
    }

    // ================================================================
    //  PREVIEW SLIP (Akses PDF untuk Bendahara)
    // ================================================================
    public function previewSlip(int $id)
    {
        $row = (new PayrollDetailModel())->withRelations()->where('payroll_detail.id', $id)->first();
        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Slip tidak ditemukan.');
        }
        $pdf = (new SlipPdfService())->renderPdf($row);
        $filename = 'SLIP_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', trim((string)$row['nama'])) . '_' . ($row['bulan'] ?? '') . '_' . ($row['tahun'] ?? '') . '.pdf';
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($pdf);
    }
}
