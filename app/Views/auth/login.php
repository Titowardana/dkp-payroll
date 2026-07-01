<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login - DKP Slip Gaji') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #030e1f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        /* Animated Oceanic Background Mesh */
        .ocean-bg {
            position: absolute;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: 0;
            overflow: hidden;
            background: linear-gradient(125deg, #021226, #032142, #04386d);
        }
        .blob-1, .blob-2, .blob-3 {
            position: absolute;
            filter: blur(90px);
            opacity: 0.6;
            animation: float-blob 20s infinite ease-in-out alternate;
            border-radius: 50%;
        }
        .blob-1 { width: 500px; height: 500px; background: #00b4d8; top: -10%; left: -10%; animation-delay: 0s; }
        .blob-2 { width: 400px; height: 400px; background: #0077b6; bottom: -5%; right: 5%; animation-delay: 2s; }
        .blob-3 { width: 600px; height: 600px; background: #023e8a; top: 30%; left: 40%; animation-delay: 4s; }

        @keyframes float-blob {
            0% { transform: scale(1) translate(0, 0); }
            50% { transform: scale(1.2) translate(50px, -50px); }
            100% { transform: scale(0.9) translate(-30px, 30px); }
        }

        /* Glassmorphism Container */
        .glass-container {
            position: relative;
            z-index: 10;
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 520px;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 32px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255,255,255,0.1);
            overflow: hidden;
        }

        /* Left Side: Branding */
        .brand-side {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center; /* Center horizontally */
            text-align: center; /* Center text */
            background: linear-gradient(145deg, rgba(2, 62, 138, 0.4) 0%, rgba(3, 14, 31, 0.1) 100%);
            border-right: 1px solid rgba(255,255,255,0.05);
            position: relative;
        }
        .brand-side::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 40%;
            background: linear-gradient(to top, rgba(0, 180, 216, 0.2), transparent);
            pointer-events: none;
        }
        
        .brand-logo {
            width: 90px;
            margin-bottom: 25px;
            filter: drop-shadow(0 5px 15px rgba(0, 180, 216, 0.3));
            animation: floatLogo 4s ease-in-out infinite;
        }

        @keyframes floatLogo {
            0%, 100% { transform: translateY(0); filter: drop-shadow(0 5px 15px rgba(0,180,216,0.3)); }
            50% { transform: translateY(-12px); filter: drop-shadow(0 15px 25px rgba(0,180,216,0.6)); }
        }

        .brand-title {
            color: #ffffff;
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 15px;
            letter-spacing: -1px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 1s ease-out;
        }
        .brand-title span {
            background: linear-gradient(270deg, #48cae4, #00b4d8, #caf0f8, #0077b6);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientFlow 5s ease infinite;
        }

        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.05rem;
            line-height: 1.6;
            font-weight: 300;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Right Side: Login Form */
        .form-side {
            width: 420px;
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.02);
        }
        
        .form-title {
            color: #ffffff;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 30px;
        }

        /* Custom Floating Inputs */
        .input-group-custom {
            position: relative;
            margin-bottom: 24px;
        }
        .input-group-custom i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 1.1rem;
            transition: 0.3s ease;
            z-index: 2;
        }
        .login-input {
            width: 100%;
            height: 56px;
            padding: 0 20px 0 54px;
            background: rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            color: #ffffff;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        .login-input:focus {
            outline: none;
            background: rgba(0, 0, 0, 0.25);
            border-color: rgba(0, 180, 216, 0.5);
            box-shadow: 0 0 0 4px rgba(0, 180, 216, 0.1);
        }
        .login-input:focus + i, .login-input:not(:placeholder-shown) + i {
            color: #00b4d8;
        }
        .login-input::placeholder { color: rgba(255, 255, 255, 0.3); font-weight: 300; }

        /* Cosmic Button */
        .login-btn {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, #0077b6, #00b4d8);
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 119, 182, 0.3);
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }
        .login-btn::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: all 0.5s ease;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 119, 182, 0.5);
            background: linear-gradient(135deg, #0096c7, #48cae4);
        }
        .login-btn:hover::before { left: 100%; }

        /* Custom Alert styling for dark theme */
        .glass-alert {
            background: rgba(220, 38, 38, 0.1) !important;
            border: 1px solid rgba(220, 38, 38, 0.3) !important;
            color: #fca5a5 !important;
            border-radius: 14px;
            backdrop-filter: blur(10px);
        }
        .glass-alert-success {
            background: rgba(22, 163, 74, 0.1) !important;
            border: 1px solid rgba(22, 163, 74, 0.3) !important;
            color: #86efac !important;
        }

        /* Responsive */
        @media (max-width: 850px) {
            .glass-container { flex-direction: column; max-width: 450px; min-height: auto; margin: 20px; }
            .brand-side { padding: 40px 30px; text-align: center; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.05); }
            .brand-logo { width: 70px; margin: 0 auto 20px; }
            .brand-title { font-size: 2.2rem; }
            .form-side { width: 100%; padding: 40px 30px; }
            .form-title { display: none; } /* Hide title on mobile as branding is just above */
        }
    </style>
</head>
<body>

<!-- Animated background elements -->
<div class="ocean-bg">
    <div class="blob-1"></div>
    <div class="blob-2"></div>
    <div class="blob-3"></div>
</div>

<div class="glass-container">
    <!-- Branding Section -->
    <div class="brand-side">
        <img src="<?= base_url('assets/images/logo-dkp-kepri.png') ?>" alt="DKP Logo" class="brand-logo">
        <h1 class="brand-title">DKP<br><span>KEUANGAN SYSTEM</span></h1>
        <p class="brand-subtitle">
            Platform modern dan terintegrasi untuk manajemen dan pemantauan slip gaji pegawai pada Dinas Kelautan dan Perikanan Kepri.
        </p>
    </div>

    <!-- Login Form Section -->
    <div class="form-side">
        <div class="form-title">Silakan Masuk</div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert glass-alert mb-4 px-3 py-2 text-sm text-center">
                <i class="fas fa-exclamation-triangle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert glass-alert-success mb-4 px-3 py-2 text-sm text-center">
                <i class="fas fa-check-circle me-2"></i><?= esc(session()->getFlashdata('message')) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('login') ?>">
            <?= csrf_field() ?>
            
            <div class="input-group-custom">
                <input type="text" class="login-input" name="username" placeholder="Username / NIP" value="<?= old('username') ?>" required autocomplete="off">
                <i class="fas fa-user-circle"></i>
            </div>
            
            <div class="input-group-custom">
                <input type="password" class="login-input" name="password" placeholder="Password" required>
                <i class="fas fa-lock"></i>
            </div>
            
            <button class="login-btn" type="submit">
                Masuk Sistem <i class="fas fa-arrow-right ms-2 fs-6"></i>
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
