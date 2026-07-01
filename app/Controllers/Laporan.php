<?php namespace App\Controllers;

use App\Models\PayrollDetailModel;
use App\Models\PeriodeModel;

class Laporan extends BaseController
{
    public function index()
    {
        $periodeId = (int) ($this->request->getGet('periode_id') ?? 0);
        $periodeModel = new PeriodeModel();
        $payroll = new PayrollDetailModel();

        $summaryQuery = (clone $payroll)->selectSum('total_pendapatan')->selectSum('total_potongan')->selectSum('gaji_bersih');
        if ($periodeId > 0) {
            $summaryQuery->where('periode_id', $periodeId);
        }
        $summaryRaw = $summaryQuery->first();
        $summary = [
            'total_slip' => (clone $payroll)->where($periodeId > 0 ? ['periode_id' => $periodeId] : [])->countAllResults(),
            'total_pendapatan' => (float) ($summaryRaw['total_pendapatan'] ?? 0),
            'total_potongan' => (float) ($summaryRaw['total_potongan'] ?? 0),
            'total_bersih' => (float) ($summaryRaw['gaji_bersih'] ?? 0),
        ];

        $query = $payroll->withRelations();
        if ($periodeId > 0) {
            $query->where('payroll_detail.periode_id', $periodeId);
        }
        $query->orderBy('pegawai.nama', 'ASC');

        return $this->view('laporan/index', [
            'title' => 'Laporan Payroll',
            'pageTitle' => 'Laporan Payroll',
            'activeMenu' => 'laporan',
            'rows' => $query->paginate(50),
            'pager' => $query->pager,
            'summary' => $summary,
            'periodes' => $periodeModel->orderBy('tahun', 'DESC')->orderBy('bulan', 'DESC')->findAll(),
            'selectedPeriodeId' => $periodeId,
        ]);
    }
}
