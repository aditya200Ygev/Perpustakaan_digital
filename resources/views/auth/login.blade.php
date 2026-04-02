<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Perpustakaan Digital</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- OPTIONAL: kalau vite error, boleh dihapus --}}
     @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="auth-page">
<div class="auth-card">

  {{-- LEFT PANEL --}}
  <div class="left-panel">
    <div class="lp-brand">
      <div class="lp-icon">📚</div>
      <h1>Perpustakaan<br>Digital</h1>
      <p>Sistem informasi manajemen perpustakaan sekolah terpadu.</p>
    </div>

    <div class="lp-illustration">
      <svg viewBox="0 0 240 190"></svg>

      <div class="role-badges">

      </div>
    </div>
  </div>

  {{-- RIGHT PANEL --}}
  <div class="right-panel">

    <div class="rp-tabs">
      <a href="{{ route('login') }}" class="rp-tab rp-tab--active">Masuk</a>
      <a href="{{ route('register') }}" class="rp-tab">Daftar</a>
    </div>

    <div class="rp-body">
      <h2 class="rp-title">Selamat Datang 👋</h2>
      <p class="rp-sub">Silakan login dengan email dan password</p>

      {{-- SUCCESS --}}
      @if(session('success'))
        <div class="flash flash--success">✓ {{ session('success') }}</div>
      @endif

      {{-- ERROR --}}
      @if($errors->any())
        <div class="flash flash--error">{{ $errors->first() }}</div>
      @endif

      {{-- FORM LOGIN --}}
      <form method="POST" action="{{ route('login.post') }}">
        @csrf

        {{-- EMAIL --}}
        <div class="fg">
          <label>Email</label>
          <input type="email" name="email" value="{{ old('email') }}"
                 class="fg-input" required>
        </div>

        {{-- PASSWORD --}}
        <div class="fg">
          <label>Password</label>
          <div class="fg-pw">
            <input type="password" name="password" id="pw1"
                   class="fg-input" required>
            <button type="button" onclick="eyeToggle('pw1',this)">👁</button>
          </div>
        </div>

        {{-- REMEMBER --}}
        <div class="flex items-center gap-2 text-xs mt-2">
          <input type="checkbox" name="remember">
          <span>Ingat saya</span>
        </div>

        {{-- BUTTON --}}
        <button type="submit" class="btn-submit mt-4">
          Masuk ke Sistem
        </button>

      </form>

    </div>
  </div>

</div>
</div>

<script>
function eyeToggle(id, btn) {
  const input = document.getElementById(id);
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>
