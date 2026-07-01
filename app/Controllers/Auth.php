<?php
namespace App\Controllers;

use App\Models\ActivityLogModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('loggedin')) {
            return redirect()->to(base_url(session()->get('role') === 'bendahara' ? 'bendahara/dashboard' : 'dashboard'));
        }

        return $this->view('auth/login', [
            'title' => 'Login - DKP Slip Gaji',
        ]);
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        $maxAttempts = 5;
        $lockoutMinutes = 15;
        $attempts = session()->get('login_attempts', 0);
        $lockoutTime = session()->get('login_lockout', 0);

        if ($attempts >= $maxAttempts && time() < $lockoutTime) {
            $remaining = ceil(($lockoutTime - time()) / 60);
            return redirect()->back()->withInput()->with('error', "Terlalu banyak percobaan login. Coba lagi dalam {$remaining} menit.");
        }

        $userModel = new UserModel();
        $user = $userModel->findActiveByUsername((string) $this->request->getPost('username'));

        if (!$user || !password_verify((string) $this->request->getPost('password'), (string) $user['password'])) {
            session()->set('login_attempts', $attempts + 1);
            if ($attempts + 1 >= $maxAttempts) {
                session()->set('login_lockout', time() + ($lockoutMinutes * 60));
            }
            return redirect()->back()->withInput()->with('error', 'Username atau password salah, atau akun tidak aktif.');
        }

        session()->remove(['login_attempts', 'login_lockout']);

        session()->regenerate(true);
        session()->set([
            'loggedin' => true,
            'user_id' => (int) $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'role' => $user['role'],
            'login_time' => time(),
            'last_activity' => time(),
        ]);

        $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
        try {
            $logModel = new ActivityLogModel();
            $logModel->insert([
                'user_id' => $user['id'],
                'activity_type' => 'Login',
                'description' => ($user['full_name'] ?: $user['username']) . ' login ke sistem.',
                'ip_address' => (string) $this->request->getIPAddress(),
                'user_agent' => (string) $this->request->getUserAgent(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Auth] Gagal mencatat log login: ' . $e->getMessage());
        }

        return redirect()->to(base_url($user['role'] === 'bendahara' ? 'bendahara/dashboard' : 'dashboard'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('message', 'Anda berhasil logout.');
    }
}
