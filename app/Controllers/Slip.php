<?php namespace App\Controllers;

use App\Models\PayrollDetailModel;
use App\Models\PeriodeModel;

class Slip extends BaseController
{
    public function index()
    {
        $periodeId = (int) ($this->request->getGet('periode_id') ?? 0);
        $status    = trim((string) ($this->request->getGet('status') ?? ''));
        $keyword   = trim((string) ($this->request->getGet('keyword') ?? ''));

        // Cegah browser cache agar flash message tidak muncul berulang
        $this->response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $this->response->setHeader('Pragma', 'no-cache');
        $this->response->setHeader('Expires', '0');

        $model = (new PayrollDetailModel())->withRelations()
            ->orderBy('periode_penggajian.tahun', 'DESC')
            ->orderBy('periode_penggajian.bulan', 'DESC')
            ->orderBy('pegawai.nama', 'ASC');

        if ($periodeId > 0) {
            $model->where('payroll_detail.periode_id', $periodeId);
        }
        if ($status !== '') {
            $model->where('payroll_detail.status', $status);
        }
        if ($keyword !== '') {
            $model->groupStart()
                  ->like('pegawai.nama', $keyword)
                  ->orLike('pegawai.nip', $keyword)
                  ->groupEnd();
        }

        return $this->view('slip/index', [
            'title'             => 'Slip Gaji',
            'pageTitle'         => 'Slip Gaji',
            'activeMenu'        => 'slip',
            'rows'              => $model->paginate(50),
            'pager'             => $model->pager,
            'periodes'          => (new PeriodeModel())->orderBy('tahun', 'DESC')->orderBy('bulan', 'DESC')->findAll(),
            'selectedPeriodeId' => $periodeId,
            'selectedStatus'    => $status,
            'keyword'           => $keyword,
        ]);
    }

    public function edit($id)
    {
        $model = (new PayrollDetailModel())->withRelations();
        $slip = $model->find((int) $id);

        if (!$slip) {
            return redirect()->to(site_url('slip'))->with('error', 'Data slip gaji tidak ditemukan.');
        }

        return $this->view('slip/edit', [
            'title' => 'Edit Slip Gaji',
            'pageTitle' => 'Edit Slip Gaji',
            'activeMenu' => 'slip',
            'slip' => $slip
        ]);
    }

    public function update($id)
    {
        $rules = [
            'gaji_pokok' => 'permit_empty|decimal',
            'tunjangan_keluarga' => 'permit_empty|decimal',
            'tunjangan_pasangan_detail' => 'permit_empty|decimal',
            'tunjangan_anak_detail' => 'permit_empty|decimal',
            'tunjangan_jabatan' => 'permit_empty|decimal',
            'tunjangan_fungsional' => 'permit_empty|decimal',
            'tunjangan_fungsional_umum' => 'permit_empty|decimal',
            'tunjangan_beras' => 'permit_empty|decimal',
            'tunjangan_pph' => 'permit_empty|decimal',
            'tunjangan_khusus_papua' => 'permit_empty|decimal',
            'tunjangan_jht' => 'permit_empty|decimal',
            'pembulatan' => 'permit_empty|decimal',
            'tunjangan_lainnya' => 'permit_empty|decimal',
            'potongan_pph21' => 'permit_empty|decimal',
            'iuran_jkn' => 'permit_empty|decimal',
            'potongan_iwp' => 'permit_empty|decimal',
            'iuran_jkk' => 'permit_empty|decimal',
            'iuran_jkm' => 'permit_empty|decimal',
            'zakat' => 'permit_empty|decimal',
            'iuran_pensiun' => 'permit_empty|decimal',
            'iuran_tapera' => 'permit_empty|decimal',
            'bulog' => 'permit_empty|decimal',
            'potongan_lainnya' => 'permit_empty|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('slip_error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }

        $model = new PayrollDetailModel();
        $slip = $model->find((int) $id);

        if (!$slip) {
            return redirect()->to(site_url('slip'))->with('error', 'Data slip gaji tidak ditemukan.');
        }

        $gajiPokok = (float) ($this->request->getPost('gaji_pokok') ?? 0);
        $tunjKeluarga = (float) ($this->request->getPost('tunjangan_keluarga') ?? 0);
        $tunjPasangan = (float) ($this->request->getPost('tunjangan_pasangan_detail') ?? 0);
        $tunjAnak = (float) ($this->request->getPost('tunjangan_anak_detail') ?? 0);
        $tunjJabatan = (float) ($this->request->getPost('tunjangan_jabatan') ?? 0);
        $tunjFungsional = (float) ($this->request->getPost('tunjangan_fungsional') ?? 0);
        $tunjFungsionalUmum = (float) ($this->request->getPost('tunjangan_fungsional_umum') ?? 0);
        $tunjBeras = (float) ($this->request->getPost('tunjangan_beras') ?? 0);
        $tunjPph = (float) ($this->request->getPost('tunjangan_pph') ?? 0);
        $tunjPapua = (float) ($this->request->getPost('tunjangan_khusus_papua') ?? 0);
        $tunjJht = (float) ($this->request->getPost('tunjangan_jht') ?? 0);
        $tunjLain = (float) ($this->request->getPost('tunjangan_lainnya') ?? 0);
        $pembulatan = (float) ($this->request->getPost('pembulatan') ?? 0);

        $potPph21 = (float) ($this->request->getPost('potongan_pph21') ?? 0);
        $iuranJkn = (float) ($this->request->getPost('iuran_jkn') ?? 0);
        $potIwp = (float) ($this->request->getPost('potongan_iwp') ?? 0);
        $iuranJkk = (float) ($this->request->getPost('iuran_jkk') ?? 0);
        $iuranJkm = (float) ($this->request->getPost('iuran_jkm') ?? 0);
        $zakat = (float) ($this->request->getPost('zakat') ?? 0);
        $iuranPensiun = (float) ($this->request->getPost('iuran_pensiun') ?? 0);
        $iuranTapera = (float) ($this->request->getPost('iuran_tapera') ?? 0);
        $bulog = (float) ($this->request->getPost('bulog') ?? 0);
        $potLain = (float) ($this->request->getPost('potongan_lainnya') ?? 0);

        $totalPendapatan = $gajiPokok + $tunjKeluarga + $tunjPasangan + $tunjAnak
            + $tunjJabatan + $tunjFungsional + $tunjFungsionalUmum + $tunjBeras
            + $tunjPph + $tunjPapua + $tunjJht + $tunjLain + $pembulatan;
        $totalPotongan = $potPph21 + $iuranJkn + $potIwp + $iuranJkk + $iuranJkm
            + $zakat + $iuranPensiun + $iuranTapera + $bulog + $potLain;
        $gajiBersih = $totalPendapatan - $totalPotongan;

        $data = [
            'gaji_pokok' => $gajiPokok,
            'tunjangan_keluarga' => $tunjKeluarga,
            'tunjangan_pasangan_detail' => $tunjPasangan,
            'tunjangan_anak_detail' => $tunjAnak,
            'tunjangan_jabatan' => $tunjJabatan,
            'tunjangan_fungsional' => $tunjFungsional,
            'tunjangan_fungsional_umum' => $tunjFungsionalUmum,
            'tunjangan_beras' => $tunjBeras,
            'tunjangan_pph' => $tunjPph,
            'tunjangan_khusus_papua' => $tunjPapua,
            'tunjangan_jht' => $tunjJht,
            'tunjangan_lainnya' => $tunjLain,
            'pembulatan' => $pembulatan,
            'potongan_pph21' => $potPph21,
            'iuran_jkn' => $iuranJkn,
            'potongan_iwp' => $potIwp,
            'iuran_jkk' => $iuranJkk,
            'iuran_jkm' => $iuranJkm,
            'zakat' => $zakat,
            'iuran_pensiun' => $iuranPensiun,
            'iuran_tapera' => $iuranTapera,
            'bulog' => $bulog,
            'potongan_lainnya' => $potLain,
            'total_pendapatan' => $totalPendapatan,
            'total_potongan' => $totalPotongan,
            'gaji_bersih' => $gajiBersih,
            'status' => $this->request->getPost('status') ?: $slip['status'],
        ];

        if ($slip['status'] === 'rejected') {
            $data['status'] = 'draft';
            $data['catatan_penolakan'] = null;
        }

        try {
            $model->update($id, $data);
        } catch (\Throwable $e) {
            log_message('error', '[Slip] Gagal update slip #' . $id . ': ' . $e->getMessage());
            return redirect()->to(site_url('slip/edit/'.$id))->with('slip_error', 'Gagal menyimpan perubahan. Silakan coba lagi.');
        }

        return redirect()->to(site_url('slip/edit/'.$id))->with('slip_message', 'Slip Gaji berhasil diperbarui.');
    }

    public function create()
    {
        return $this->view('slip/create', [
            'title' => 'Tambah Slip Gaji Manual',
            'pageTitle' => 'Tambah Slip Gaji',
            'activeMenu' => 'slip',
            'periodes' => (new PeriodeModel())->orderBy('tahun', 'DESC')->orderBy('bulan', 'DESC')->findAll(),
            'pegawais' => (new \App\Models\PegawaiModel())->active()->orderBy('nama', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $rules = [
            'periode_id' => 'required|integer|is_not_unique[periode_penggajian.id]',
            'pegawai_id' => 'required|integer|is_not_unique[pegawai.id]',
            'gaji_pokok' => 'permit_empty|decimal',
            'tunjangan_keluarga' => 'permit_empty|decimal',
            'tunjangan_pasangan_detail' => 'permit_empty|decimal',
            'tunjangan_anak_detail' => 'permit_empty|decimal',
            'tunjangan_jabatan' => 'permit_empty|decimal',
            'tunjangan_fungsional' => 'permit_empty|decimal',
            'tunjangan_fungsional_umum' => 'permit_empty|decimal',
            'tunjangan_beras' => 'permit_empty|decimal',
            'tunjangan_pph' => 'permit_empty|decimal',
            'tunjangan_khusus_papua' => 'permit_empty|decimal',
            'tunjangan_jht' => 'permit_empty|decimal',
            'pembulatan' => 'permit_empty|decimal',
            'tunjangan_lainnya' => 'permit_empty|decimal',
            'potongan_pph21' => 'permit_empty|decimal',
            'iuran_jkn' => 'permit_empty|decimal',
            'potongan_iwp' => 'permit_empty|decimal',
            'iuran_jkk' => 'permit_empty|decimal',
            'iuran_jkm' => 'permit_empty|decimal',
            'zakat' => 'permit_empty|decimal',
            'iuran_pensiun' => 'permit_empty|decimal',
            'iuran_tapera' => 'permit_empty|decimal',
            'bulog' => 'permit_empty|decimal',
            'potongan_lainnya' => 'permit_empty|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('slip_error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }

        $model = new PayrollDetailModel();

        $periodeId = (int) $this->request->getPost('periode_id');
        $pegawaiId = (int) $this->request->getPost('pegawai_id');

        $gajiPokok = (float) ($this->request->getPost('gaji_pokok') ?? 0);
        $tunjKeluarga = (float) ($this->request->getPost('tunjangan_keluarga') ?? 0);
        $tunjPasangan = (float) ($this->request->getPost('tunjangan_pasangan_detail') ?? 0);
        $tunjAnak = (float) ($this->request->getPost('tunjangan_anak_detail') ?? 0);
        $tunjJabatan = (float) ($this->request->getPost('tunjangan_jabatan') ?? 0);
        $tunjFungsional = (float) ($this->request->getPost('tunjangan_fungsional') ?? 0);
        $tunjFungsionalUmum = (float) ($this->request->getPost('tunjangan_fungsional_umum') ?? 0);
        $tunjBeras = (float) ($this->request->getPost('tunjangan_beras') ?? 0);
        $tunjPph = (float) ($this->request->getPost('tunjangan_pph') ?? 0);
        $tunjPapua = (float) ($this->request->getPost('tunjangan_khusus_papua') ?? 0);
        $tunjJht = (float) ($this->request->getPost('tunjangan_jht') ?? 0);
        $tunjLain = (float) ($this->request->getPost('tunjangan_lainnya') ?? 0);
        $pembulatan = (float) ($this->request->getPost('pembulatan') ?? 0);

        $potPph21 = (float) ($this->request->getPost('potongan_pph21') ?? 0);
        $iuranJkn = (float) ($this->request->getPost('iuran_jkn') ?? 0);
        $potIwp = (float) ($this->request->getPost('potongan_iwp') ?? 0);
        $iuranJkk = (float) ($this->request->getPost('iuran_jkk') ?? 0);
        $iuranJkm = (float) ($this->request->getPost('iuran_jkm') ?? 0);
        $zakat = (float) ($this->request->getPost('zakat') ?? 0);
        $iuranPensiun = (float) ($this->request->getPost('iuran_pensiun') ?? 0);
        $iuranTapera = (float) ($this->request->getPost('iuran_tapera') ?? 0);
        $bulog = (float) ($this->request->getPost('bulog') ?? 0);
        $potLain = (float) ($this->request->getPost('potongan_lainnya') ?? 0);

        $totalPendapatan = $gajiPokok + $tunjKeluarga + $tunjPasangan + $tunjAnak
            + $tunjJabatan + $tunjFungsional + $tunjFungsionalUmum + $tunjBeras
            + $tunjPph + $tunjPapua + $tunjJht + $tunjLain + $pembulatan;
        $totalPotongan = $potPph21 + $iuranJkn + $potIwp + $iuranJkk + $iuranJkm
            + $zakat + $iuranPensiun + $iuranTapera + $bulog + $potLain;
        $gajiBersih = $totalPendapatan - $totalPotongan;

        $data = [
            'periode_id' => $periodeId,
            'pegawai_id' => $pegawaiId,
            'gaji_pokok' => $gajiPokok,
            'tunjangan_keluarga' => $tunjKeluarga,
            'tunjangan_pasangan_detail' => $tunjPasangan,
            'tunjangan_anak_detail' => $tunjAnak,
            'tunjangan_jabatan' => $tunjJabatan,
            'tunjangan_fungsional' => $tunjFungsional,
            'tunjangan_fungsional_umum' => $tunjFungsionalUmum,
            'tunjangan_beras' => $tunjBeras,
            'tunjangan_pph' => $tunjPph,
            'tunjangan_khusus_papua' => $tunjPapua,
            'tunjangan_jht' => $tunjJht,
            'tunjangan_lainnya' => $tunjLain,
            'pembulatan' => $pembulatan,
            'potongan_pph21' => $potPph21,
            'iuran_jkn' => $iuranJkn,
            'potongan_iwp' => $potIwp,
            'iuran_jkk' => $iuranJkk,
            'iuran_jkm' => $iuranJkm,
            'zakat' => $zakat,
            'iuran_pensiun' => $iuranPensiun,
            'iuran_tapera' => $iuranTapera,
            'bulog' => $bulog,
            'potongan_lainnya' => $potLain,
            'total_pendapatan' => $totalPendapatan,
            'total_potongan' => $totalPotongan,
            'gaji_bersih' => $gajiBersih,
            'status' => 'draft',
        ];

        try {
            $model->insert($data);
        } catch (\Throwable $e) {
            log_message('error', '[Slip] Gagal insert slip: ' . $e->getMessage());
            return redirect()->back()->with('slip_error', 'Gagal menyimpan slip. Silakan coba lagi.');
        }

        if ($gajiPokok > 0) {
            (new \App\Models\PegawaiModel())->update($pegawaiId, [
                'gaji_pokok' => $gajiPokok,
            ]);
        }

        return redirect()->to(site_url('slip'))->with('slip_message', 'Slip Gaji Manual berhasil ditambahkan.');
    }

    public function delete($id)
    {
        if ($this->request->getMethod() !== 'post') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $model = new PayrollDetailModel();
        $slip = $model->find((int) $id);
        if (!$slip) {
            return redirect()->to(site_url('slip'))->with('error', 'Data slip gaji tidak ditemukan.');
        }
        if (! in_array($slip['status'], ['draft', 'rejected'])) {
            return redirect()->to(site_url('slip'))->with('error', 'Hanya slip berstatus Draft atau Rejected yang bisa dihapus.');
        }
        $model->delete((int) $id);
        return redirect()->to(site_url('slip'))->with('slip_message', 'Slip gaji berhasil dihapus.');
    }
}
