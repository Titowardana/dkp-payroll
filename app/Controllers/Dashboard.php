<?php
namespace App\Controllers;

use App\Models\PayrollDetailModel;
use App\Models\PegawaiModel;
use App\Models\PeriodeModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $pegawai = new PegawaiModel();
        $payroll = new PayrollDetailModel();
        $periodeModel = new PeriodeModel();

        $latest = $periodeModel->orderBy('tahun', 'DESC')->orderBy('bulan', 'DESC')->first();
        $latestPeriod = $latest['nama_periode'] ?? ((isset($latest['bulan'], $latest['tahun'])) ? ($latest['bulan'] . '/' . $latest['tahun']) : '-');

        return $this->view('dashboard/admin', [
            'title' => 'Dashboard Admin',
            'pageTitle' => 'Dashboard Admin Keuangan DKP',
            'activeMenu' => 'dashboard',
            'countPegawai' => $pegawai->active()->countAllResults(),
            'countSlip' => $payroll->countAllResults(),
            'countDraft' => $payroll->where('status', 'draft')->countAllResults(),
            'countVerified' => $payroll->where('status', 'verified')->countAllResults(),
            'countApproved' => $payroll->where('status', 'approved')->countAllResults(),
            'latestPeriod' => $latestPeriod,
        ]);
    }
}
