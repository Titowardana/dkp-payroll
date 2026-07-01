<?php namespace App\Controllers;

use App\Libraries\SlipPdfService;
use App\Models\PayrollDetailModel;
use App\Models\PeriodeModel;
use ZipArchive;

class GenerateSlip extends BaseController
{
    public function index()
    {
        $periodeId = (int) ($this->request->getGet('periode_id') ?? 0);
        $search = trim((string) ($this->request->getGet('search') ?? ''));
        $model = (new PayrollDetailModel())->withRelations()->orderBy('periode_penggajian.tahun', 'DESC')->orderBy('periode_penggajian.bulan', 'DESC')->orderBy('pegawai.nama', 'ASC');
        if ($periodeId > 0) {
            $model->where('payroll_detail.periode_id', $periodeId);
        }
        if ($search !== '') {
            $model->groupStart()->like('pegawai.nama', $search)->orLike('pegawai.nip', $search)->groupEnd();
        }
        return $this->view('generate_slip/index', [
            'title' => 'Generate Slip',
            'pageTitle' => 'Generate Slip',
            'activeMenu' => 'generate_slip',
            'rows' => $model->paginate(50),
            'pager' => $model->pager,
            'periodes' => (new PeriodeModel())->orderBy('tahun', 'DESC')->orderBy('bulan', 'DESC')->findAll(),
            'selectedPeriodeId' => $periodeId,
            'search' => $search,
        ]);
    }

    public function single($id)
    {
        $row = (new PayrollDetailModel())->withRelations()->where('payroll_detail.id', (int) $id)->first();
        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Slip tidak ditemukan.');
        }
        $pdf = (new SlipPdfService())->renderPdf($row);
        $filename = 'SLIP_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', trim((string) $row['nama'])) . '_' . ($row['bulan'] ?? '') . '_' . ($row['tahun'] ?? '') . '.pdf';
        return $this->response->setHeader('Content-Type', 'application/pdf')->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')->setBody($pdf);
    }

    public function download($id)
    {
        $row = (new PayrollDetailModel())->withRelations()->where('payroll_detail.id', (int) $id)->first();
        if (! $row) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Slip tidak ditemukan.');
        }
        $pdf = (new SlipPdfService())->renderPdf($row);
        $filename = 'SLIP_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', trim((string) $row['nama'])) . '_' . ($row['bulan'] ?? '') . '_' . ($row['tahun'] ?? '') . '.pdf';
        return $this->response->download($filename, $pdf);
    }

    public function bulk()
    {
        set_time_limit(0);
        ini_set('memory_limit', '4096M');

        $periodeId = (int) ($this->request->getGet('periode_id') ?? 0);
        $model = (new PayrollDetailModel())->withRelations()->orderBy('pegawai.nama', 'ASC');
        if ($periodeId > 0) {
            $model->where('payroll_detail.periode_id', $periodeId);
        }
        $total = $model->countAllResults(false);
        if ($total === 0) {
            return redirect()->to(site_url('generate-slip'))->with('error', 'Tidak ada data slip untuk digenerate.');
        }

        $tmpZip = WRITEPATH . 'uploads/slip_' . time() . '.zip';
        if (! is_dir(dirname($tmpZip))) {
            mkdir(dirname($tmpZip), 0777, true);
        }
        $zip = new ZipArchive();
        if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return redirect()->to(site_url('generate-slip'))->with('error', 'Gagal membuat file ZIP.');
        }

        session_write_close();

        $service = new SlipPdfService();
        $offset = 0;
        $chunkSize = 25;
        while ($offset < $total) {
            $chunk = $model->findAll($chunkSize, $offset);
            foreach ($chunk as $row) {
                $filename = 'SLIP_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', trim((string) $row['nama'])) . '_' . ($row['bulan'] ?? '') . '_' . ($row['tahun'] ?? '') . '.pdf';
                $pdfContent = $service->renderPdf($row);
                $zip->addFromString($filename, $pdfContent);
                unset($pdfContent);
                gc_collect_cycles();
            }
            unset($chunk);
            gc_collect_cycles();
            $offset += $chunkSize;
        }
        $zip->close();

        register_shutdown_function(function() use ($tmpZip) {
            if (file_exists($tmpZip)) {
                unlink($tmpZip);
            }
        });

        return $this->response->download($tmpZip, null)->setFileName('SLIP_GAJI.zip');
    }
}
