<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Perpustakaan Digital</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

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
            max-width: 1000px;
            width: 100%;
            display: flex;
            min-height: 700px;
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

        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }

        .brand { position: relative; z-index: 1; }

        .brand-icon {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 36px; margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(251, 191, 36, 0.3);
        }

        .brand h1 { font-size: 32px; font-weight: 700; line-height: 1.3; margin-bottom: 16px; }
        .brand p { font-size: 14px; opacity: 0.8; line-height: 1.6; }

        .illustration { position: relative; z-index: 1; margin-top: auto; }
        .illustration svg { width: 100%; max-width: 280px; opacity: 0.9; }

        /* RIGHT PANEL */
        .right-panel {
            width: 55%;
            padding: 40px 40px;
            background: #fafafa;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            max-height: 700px;
        }

        .tabs {
            display: flex; gap: 32px; margin-bottom: 24px;
            border-bottom: 2px solid #e5e7eb; padding-bottom: 12px;
        }

        .tab {
            padding: 8px 0; font-weight: 600; font-size: 16px;
            color: #9ca3af; cursor: pointer; transition: all 0.3s;
            text-decoration: none; position: relative;
        }
        .tab:hover { color: #4b5563; }
        .tab.active { color: #1f2937; }
        .tab.active::after {
            content: ''; position: absolute; bottom: -14px; left: 0; right: 0;
            height: 2px; background: #1f2937;
        }

        .welcome { margin-bottom: 24px; }
        .welcome h2 { font-size: 24px; font-weight: 700; color: #1f2937; margin-bottom: 6px; }
        .welcome p { color: #6b7280; font-size: 14px; }

        /* FLASH MESSAGES */
        .flash {
            padding: 12px 16px; border-radius: 10px; margin-bottom: 20px;
            font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .flash-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .flash-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* FORM */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid .full { grid-column: 1 / -1; }

        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block; font-weight: 600; font-size: 13px;
            color: #374151; margin-bottom: 6px;
        }
        .form-control {
            width: 100%; padding: 12px 14px;
            border: 2px solid #e5e7eb; border-radius: 10px;
            font-size: 14px; transition: all 0.3s; background: #f0f4ff;
        }
        .form-control:focus {
            outline: none; border-color: #667eea; background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        .form-control::placeholder { color: #9ca3af; }

        .password-wrapper { position: relative; }
        .password-toggle {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%); background: none; border: none;
            cursor: pointer; font-size: 16px; color: #6b7280;
            padding: 4px; transition: color 0.3s;
        }
        .password-toggle:hover { color: #374151; }

        .btn-submit {
            width: 100%; padding: 14px;
            background: #1e1b4b; color: white; border: none;
            border-radius: 10px; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all 0.3s; margin-top: 8px;
        }
        .btn-submit:hover { background: #312e81; transform: translateY(-2px); box-shadow: 0 10px 25px rgba(30, 27, 75, 0.3); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit.loading { opacity: 0.7; cursor: not-allowed; }
        .btn-submit.loading::after {
            content: ''; display: inline-block; width: 14px; height: 14px;
            border: 2px solid #ffffff; border-radius: 50%;
            border-top-color: transparent; animation: spin 0.8s linear infinite;
            margin-left: 8px; vertical-align: middle;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .switch-hint {
            text-align: center; margin-top: 20px; font-size: 14px; color: #6b7280;
        }
        .switch-hint a { color: #667eea; font-weight: 600; text-decoration: none; }
        .switch-hint a:hover { text-decoration: underline; }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .auth-container { flex-direction: column; min-height: auto; }
            .left-panel, .right-panel { width: 100%; padding: 40px 30px; }
            .left-panel { text-align: center; }
            .brand-icon { margin: 0 auto 20px; }
            .illustration { display: none; }
            .form-grid { grid-template-columns: 1fr; }
            .right-panel { max-height: none; overflow-y: visible; }
        }
    </style>
</head>
<body>

<div class="auth-container">
    {{-- LEFT PANEL --}}
    <div class="left-panel">
        <div class="brand">
            <div class="brand-icon">
                 <img src="{{ asset('image/logo-sekolah.png') }}"
                 alt="Logo Sekolah"
                 class="w-full h-full object-cover">
            </div>
            <h1>Perpustakaan<br>Digital</h1>
            <p>Daftarkan akun baru untuk mulai menggunakan sistem perpustakaan sekolah.</p>
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
            <a href="{{ route('login') }}" class="tab">Masuk</a>
            <a href="{{ route('register') }}" class="tab active">Daftar</a>
        </div>

        <div class="welcome">
            <h2>Buat Akun Baru </h2>
            <p>Daftarkan diri sesuai jurusan dan kelas Anda.</p>
        </div>

        {{-- ERROR MESSAGE --}}
        @if($errors->any())
            <div class="flash flash-error">
                <span>✕</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- REGISTER FORM --}}
        <form method="POST" action="{{ route('register.post') }}" id="registerForm">
            @csrf

            {{-- ROLE (Hidden - Default: Anggota) --}}
            <input type="hidden" name="role" value="anggota">

            {{-- Nama & Email --}}
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control"
                           value="{{ old('name') }}" placeholder="Nama lengkap" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ old('email') }}" placeholder="nama@email.com" required>
                </div>
            </div>

            {{-- Password & Confirm --}}
            <div class="form-grid">
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="••••••••" required minlength="8">
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)">👁</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control" placeholder="••••••••" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">👁</button>
                    </div>
                </div>
            </div>

            {{-- No. Telepon & Alamat --}}
            <div class="form-grid">
                <div class="form-group">
                    <label for="no_telp">No. Telepon</label>
                    <input type="text" id="no_telp" name="no_telp" class="form-control"
                           value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group full">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" class="form-control"
                           value="{{ old('alamat') }}" placeholder="Alamat lengkap">
                </div>
            </div>

            {{-- Data Anggota: NIS, Kelas, Jurusan --}}
            <div class="form-grid">
                <div class="form-group">
                    <label for="nis">NIS</label>
                    <input type="text" id="nis" name="nis" class="form-control"
                           value="{{ old('nis') }}" placeholder="Nomor Induk Siswa">
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <select id="kelas" name="kelas" class="form-control" required>
                        <option value="">Pilih kelas</option>
                        <option value="X" {{ old('kelas') == 'X' ? 'selected' : '' }}>X</option>
                        <option value="XI" {{ old('kelas') == 'XI' ? 'selected' : '' }}>XI</option>
                        <option value="XII" {{ old('kelas') == 'XII' ? 'selected' : '' }}>XII</option>
                    </select>
                </div>
                <div class="form-group full">
                    <label for="jurusan">Jurusan</label>
                    <select id="jurusan" name="jurusan" class="form-control" required>
                        <option value="">Pilih jurusan</option>
                        <option value="PPLG" {{ old('jurusan') == 'PPLG' ? 'selected' : '' }}>PPLG - Pengembangan Perangkat Lunak</option>
                        <option value="AKL" {{ old('jurusan') == 'AKL' ? 'selected' : '' }}>AKL - Akuntansi</option>
                        <option value="APAT" {{ old('jurusan') == 'APAT' ? 'selected' : '' }}>APAT - Administrasi Perkantoran</option>
                        <option value="APHP" {{ old('jurusan') == 'APHP' ? 'selected' : '' }}>APHP - Agribisnis Pengolahan Hasil Pertanian</option>
                        <option value="TBSM" {{ old('jurusan') == 'TBSM' ? 'selected' : '' }}>TBSM - Teknik Bisnis Sepeda Motor</option>
                        <option value="TKRO" {{ old('jurusan') == 'TKRO' ? 'selected' : '' }}>TKRO - Teknik Kendaraan Ringan</option>
                    </select>
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="btn-submit" id="submitBtn">
                Daftar Sekarang
            </button>
        </form>

        {{-- Switch to Login --}}
        <p class="switch-hint">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </p>
    </div>
</div>

<script>
    // Toggle Password Visibility
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁';
        }
    }

    // Loading State on Submit
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        // Simple validation check
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;

        if (password !== confirm) {
            e.preventDefault();
            alert('❌ Password dan konfirmasi password tidak cocok!');
            return;
        }

        if (password.length < 8) {
            e.preventDefault();
            alert('❌ Password minimal 8 karakter!');
            return;
        }

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

    // Input focus animation
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#667eea';
            this.style.background = 'white';
        });
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.style.borderColor = '#e5e7eb';
                this.style.background = '#f0f4ff';
            }
        });
    });
</script>

</body>
</html>
