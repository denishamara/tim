<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PasswordResetModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function loginPost()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user      = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'logged_in' => true,
                'user_id'   => $user['id'],
                'name'      => $user['name'],
                'email'     => $user['email'],
                'role'      => $user['role'],
            ]);
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Email atau password salah.')->withInput();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout.');
    }

    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/register');
    }

    public function registerPost()
    {
        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'pegawai', // Default role
        ];

        if ($userModel->insert($data)) {
            return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
        }

        return redirect()->back()->withInput()->with('error', 'Registrasi gagal. Coba lagi.');
    }

    public function forgotPassword()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/forgot_password');
    }

    public function forgotPasswordPost()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Email tidak valid.');
        }

        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak ditemukan dalam sistem.');
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        
        // Save token to database
        $resetModel = new PasswordResetModel();
        
        // Delete old tokens for this email
        $resetModel->where('email', $email)->delete();
        
        // Insert new token
        $resetModel->insert([
            'email'      => $email,
            'token'      => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Generate reset link
        $resetLink = base_url('reset-password/' . $token);
        
        return redirect()->back()->with('success', 'Silakan gunakan link di bawah ini untuk reset password:')
                                 ->with('reset_link', $resetLink);
    }

    public function resetPassword($token)
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        $resetModel = new PasswordResetModel();
        $reset = $resetModel->where('token', $token)->first();

        if (!$reset) {
            return redirect()->to('/login')->with('error', 'Token reset password tidak valid atau sudah kadaluarsa.');
        }

        // Check if token is expired (24 hours)
        $createdAt = strtotime($reset['created_at']);
        $now = time();
        $diff = $now - $createdAt;
        
        if ($diff > 86400) { // 24 hours in seconds
            $resetModel->delete($reset['id']);
            return redirect()->to('/login')->with('error', 'Token reset password sudah kadaluarsa.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function resetPasswordPost()
    {
        $rules = [
            'token'            => 'required',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $resetModel = new PasswordResetModel();
        $reset = $resetModel->where('token', $token)->first();

        if (!$reset) {
            return redirect()->to('/login')->with('error', 'Token tidak valid.');
        }

        // Update password
        $userModel = new UserModel();
        $user = $userModel->where('email', $reset['email'])->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan.');
        }

        $newPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $userModel->update($user['id'], ['password' => $newPassword]);

        // Delete used token
        $resetModel->delete($reset['id']);

        return redirect()->to('/login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
