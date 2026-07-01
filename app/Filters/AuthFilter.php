<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('loggedin')) {
            return redirect()->to(base_url('login'));
        }

        $lastActivity = (int) session()->get('last_activity');
        if ($lastActivity > 0 && (time() - $lastActivity) > (60 * 30)) {
            session()->destroy();
            return redirect()->to(base_url('login'))->with('error', 'Sesi Anda habis. Silakan login lagi.');
        }

        session()->set('last_activity', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
