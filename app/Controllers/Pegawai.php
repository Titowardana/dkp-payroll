<?php namespace App\Controllers;

use App\Models\PegawaiModel;

class Pegawai extends BaseController
{
    public function index()
    {
        $keyword = trim((string) $this->request->getGet('keyword'));
        $model = new PegawaiModel();
        $model->orderBy('nama', 'ASC');
        if ($keyword !== '') {
            $model->groupStart()
                  ->like('nip', $keyword)
                  ->orLike('nama', $keyword)
                  ->groupEnd();
        }

        $editId = (int) $this->request->getGet('edit');
        $editData = $editId > 0 ? (new PegawaiModel())->find($editId) : null;

        return $this->view('pegawai/index', [
            'title'      => 'Data Pegawai',
            'pageTitle'  => 'Data Pegawai',
            'activeMenu' => 'pegawai',
            'pegawai'    => $model->paginate(50),
            'pager'      => $model->pager,
            'keyword'    => $keyword,
            'editData'   => $editData,
        ]);
    }

    public function save()
    {
        $id = (int) ($this->request->getPost('id') ?? 0);
        $rules = [
            'nip' => 'required',
            'nama' => 'required',
            'nama_jabatan' => 'required',
        ];
        if (! $this->validate($rules)) {
            return redirect()->to(site_url('pegawai'))->withInput()->with('pg_err', 'Field wajib belum lengkap.')->with('show_modal', true);
        }
        $data = [
            'nip' => trim((string) $this->request->getPost('nip')),
            'nama' => trim((string) $this->request->getPost('nama')),
            'nik' => trim((string) $this->request->getPost('nik')) ?: null,
            'npwp' => trim((string) $this->request->getPost('npwp')) ?: null,
            'tempat_lahir' => trim((string) $this->request->getPost('tempat_lahir')) ?: null,
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin') ?: 'L',
            'alamat' => trim((string) $this->request->getPost('alamat')) ?: null,
            'no_telp' => trim((string) $this->request->getPost('no_telp')) ?: null,
            'email' => trim((string) $this->request->getPost('email')) ?: null,
            'nama_jabatan' => trim((string) $this->request->getPost('nama_jabatan')),
            'golongan' => trim((string) $this->request->getPost('golongan')) ?: null,
            'eselon' => trim((string) $this->request->getPost('eselon')) ?: null,
            'status_pernikahan' => $this->request->getPost('status_pernikahan') ?: 'Belum Menikah',
            'pasangan_pns' => $this->request->getPost('pasangan_pns') ? 1 : 0,
            'jumlah_istri_suami' => (int) ($this->request->getPost('jumlah_istri_suami') ?? 0),
            'jumlah_anak' => (int) ($this->request->getPost('jumlah_anak') ?? 0),
            'jumlah_tanggungan' => (int) ($this->request->getPost('jumlah_tanggungan') ?? 0),
            'kode_bank' => trim((string) $this->request->getPost('kode_bank')) ?: null,
            'nama_bank' => trim((string) $this->request->getPost('nama_bank')) ?: null,
            'nomor_rekening' => trim((string) $this->request->getPost('nomor_rekening')) ?: null,
            'gaji_pokok' => (float) ($this->request->getPost('gaji_pokok') ?? 0),
            'updated_by' => (int) session('user_id'),
        ];

        $model = new PegawaiModel();
        if ($id > 0) {
            $model->update($id, $data);
            return redirect()->to(site_url('pegawai'))->with('pg_msg', 'Data pegawai berhasil diupdate.');
        }

        $data['created_by'] = (int) session('user_id');
        $model->insert($data);
        
        if ($this->request->getPost('action') === 'save_add') {
            return redirect()->to(site_url('pegawai'))->with('pg_msg', 'Data pegawai berhasil ditambahkan. Silakan tambah data lagi.')->with('show_modal', true);
        }

        return redirect()->to(site_url('pegawai'))->with('pg_msg', 'Data pegawai berhasil ditambahkan.');
    }

    public function detail($id)
    {
        $model = new PegawaiModel();
        $pegawai = $model->find((int) $id);
        
        if (!$pegawai) {
            return redirect()->to(site_url('pegawai'))->with('pg_err', 'Data pegawai tidak ditemukan.');
        }

        return $this->view('pegawai/detail', [
            'title' => 'Detail Pegawai',
            'pageTitle' => 'Detail Pegawai',
            'activeMenu' => 'pegawai',
            'pegawai' => $pegawai
        ]);
    }

    public function delete($id)
    {
        if ($this->request->getMethod() !== 'post') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $slipCount = (new \App\Models\PayrollDetailModel())->where('pegawai_id', (int) $id)->countAllResults();
        if ($slipCount > 0) {
            return redirect()->to(site_url('pegawai'))
                ->with('pg_err', "Pegawai tidak bisa dihapus karena memiliki {$slipCount} data slip gaji yang terkait.");
        }
        (new PegawaiModel())->delete((int) $id);
        return redirect()->to(site_url('pegawai'))->with('pg_msg', 'Data pegawai berhasil dihapus.');
    }
}
