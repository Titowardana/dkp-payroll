<?php namespace App\Controllers;

use App\Libraries\PayrollImportService;

class ImportSipd extends BaseController
{
    public function index()
    {
        return $this->view('import_sipd/index', [
            'title' => 'Import SIPD',
            'pageTitle' => 'Import SIPD',
            'activeMenu' => 'import_sipd',
        ]);
    }

    public function upload()
    {
        $rules = [
            'bulan' => 'required|integer|greater_than[0]|less_than[13]',
            'tahun' => 'required|integer',
            'excel_file' => 'uploaded[excel_file]|ext_in[excel_file,xls,xlsx]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('im_err', implode(' ', $this->validator->getErrors()));
        }

        $file = $this->request->getFile('excel_file');
        if (! $file->isValid()) {
            return redirect()->back()->withInput()->with('im_err', 'File upload tidak valid.');
        }

        try {
            $service = new PayrollImportService();
            $result = $service->import($file->getTempName(), (int) $this->request->getPost('bulan'), (int) $this->request->getPost('tahun'), (int) session('user_id'));
            return redirect()->to(site_url('import-sipd'))->with('im_msg', 'Import berhasil untuk periode ' . $result['periode'] . '. Data sukses: ' . $result['success_count'] . ', gagal: ' . $result['error_count'])->with('import_errors', $result['errors']);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('im_err', $e->getMessage());
        }
    }
}
