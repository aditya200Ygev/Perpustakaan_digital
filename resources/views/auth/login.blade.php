<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Perpustakaan Digital</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
            min-height: 600px;
        }

        /* LEFT PANEL */
        .left-panel {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e1b4b 100%);
            color: white;
            padding: 60px 40px;
            width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .brand {
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(251, 191, 36, 0.3);
        }

        .brand h1 {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 16px;
        }

        .brand p {
            font-size: 14px;
            opacity: 0.8;
            line-height: 1.6;
        }

        .illustration {
            position: relative;
            z-index: 1;
            margin-top: auto;
        }

        .illustration svg {
            width: 100%;
            max-width: 280px;
            opacity: 0.9;
        }

        /* RIGHT PANEL */
        .right-panel {
            width: 55%;
            padding: 60px 50px;
            background: #fafafa;
            display: flex;
            flex-direction: column;
        }

        .tabs {
            display: flex;
            gap: 32px;
            margin-bottom: 40px;
            border-bottom: 2px solid #e5e7eb;
        }

        .tab {
            padding-bottom: 12px;
            font-weight: 600;
            font-size: 16px;
            color: #9ca3af;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            position: relative;
        }

        .tab:hover {
            color: #4b5563;
        }

        .tab.active {
            color: #1f2937;
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #1f2937;
        }

        .welcome {
            margin-bottom: 32px;
        }

        .welcome h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .welcome p {
            color: #6b7280;
            font-size: 14px;
        }

        /* FLASH MESSAGES */
        .flash {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .flash-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .flash-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* FORM GROUP */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background: #f0f4ff;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #6b7280;
            padding: 4px;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #374151;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px 0;
            font-size: 14px;
            color: #4b5563;
        }

        .remember input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: #1e1b4b;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: #312e81;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 27, 75, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                min-height: auto;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                padding: 40px 30px;
            }

            .left-panel {
                text-align: center;
            }

            .brand-icon {
                margin: 0 auto 20px;
            }

            .illustration {
                display: none;
            }
        }

        /* LOADING STATE */
        .btn-submit.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-submit.loading::after {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="auth-container">
    {{-- LEFT PANEL --}}
    <div class="left-panel">
        <div class="brand">
            <div class="brand-icon"> <img src="{{ asset('images/logo-sekolah.png') }}"
                 alt="Logo Sekolah"
                 class="w-full h-full object-cover"></div>
            <h1>Perpustakaan<br>Digital</h1>
            <p>Sistem informasi manajemen perpustakaan sekolah terpadu.</p>
        </div>

        <div class="illustration">
            <svg viewBox="0 0 240 190" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="20" y="40" width="200" height="140" rx="12" fill="rgba(255,255,255,0.1)"/>
                <rect x="40" y="60" width="160" height="20" rx="4" fill="rgba(255,255,255,0.2)"/>
                <rect x="40" y="90" width="120" height="16" rx="4" fill="rgba(255,255,255,0.15)"/>
                <rect x="40" y="115" width="140" height="16" rx="4" fill="rgba(255,255,255,0.15)"/>
                <rect x="40" y="140" width="100" height="16" rx="4" fill="rgba(255,255,255,0.15)"/>
                <circle cx="180" cy="140" r="25" fill="rgba(251, 191, 36, 0.3)"/>
            </svg>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="right-panel">
        {{-- TABS --}}
        <div class="tabs">
            <a href="{{ route('login') }}" class="tab active">Masuk</a>
            <a href="{{ route('register') }}" class="tab">Daftar</a>
        </div>

        <div class="welcome">
            <h2>Selamat Datang </h2>
            <p>Silakan login dengan email dan password</p>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="flash flash-success">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ERROR MESSAGE --}}
        @if($errors->any())
            <div class="flash flash-error">
                <span>✕</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- LOGIN FORM --}}
        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf

            {{-- EMAIL --}}
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control"
                    placeholder="nama@email.com"
                    required
                    autofocus
                >
            </div>

            {{-- PASSWORD --}}
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="••••••••"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        👁
                    </button>
                </div>
            </div>

            {{-- REMEMBER ME --}}
            <div class="remember">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="margin: 0; cursor: pointer;">Ingat saya</label>
            </div>

            {{-- SUBMIT BUTTON --}}
            <button type="submit" class="btn-submit" id="submitBtn">
                Masuk ke Sistem
            </button>
        </form>
    </div>
</div>

<script>
    // Toggle Password Visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.querySelector('.password-toggle');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleBtn.textContent = '🙈';
        } else {
            passwordInput.type = 'password';
            toggleBtn.textContent = '👁';
        }
    }

    // Loading State
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
    });

    // Auto-hide flash messages after 5 seconds
    setTimeout(() => {
        const flashes = document.querySelectorAll('.flash');
        flashes.forEach(flash => {
            flash.style.transition = 'opacity 0.5s, transform 0.5s';
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-10px)';
            setTimeout(() => flash.remove(), 500);
        });
    }, 5000);

    // Input animation
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
</script>

</body>
</html>
