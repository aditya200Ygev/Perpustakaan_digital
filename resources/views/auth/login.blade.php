<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Perpustakaan Digital</title>

    <!-- CSS APP -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')
    <!-- FONT -->
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

      <svg viewBox="0 0 240 190">...</svg>

      <div class="role-badges">
        <span class="rbadge">👤 Anggota</span>
        <span class="rbadge">🗂️ Petugas</span>
        <span class="rbadge">👔 Kep. Perpustakaan</span>
      </div>
    </div>
  </div>


  <div class="right-panel">
    <div class="rp-tabs">
      <a href="{{ route('login') }}" class="rp-tab rp-tab--active">Masuk</a>
      <a href="{{ route('register') }}" class="rp-tab">Daftar</a>
    </div>

    <div class="rp-body">
      <h2 class="rp-title">Selamat Datang 👋</h2>
      <p class="rp-sub">Silakan masuk sesuai peran Anda.</p>

      @if(session('success'))
        <div class="flash flash--success">✓ {{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="flash flash--error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf

        @php
          $roles = [
            'anggota' => ['👤','Anggota'],
            'petugas' => ['🗂️','Petugas'],
            'kep_perpustakaan' => ['👔','Kep. Pustaka'],
          ];
          $activeRole = old('role','anggota');
        @endphp

        <div class="role-picker">
          @foreach($roles as $val => [$icon, $label])
          <label class="rpick {{ $activeRole === $val ? 'rpick--on' : '' }}" data-r="{{ $val }}">
            <input type="radio" name="role" value="{{ $val }}" {{ $activeRole === $val ? 'checked' : '' }} hidden>
            <span>{{ $icon }} {{ $label }}</span>
          </label>
          @endforeach
        </div>

        <div class="fg">
          <label>Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="fg-input">
        </div>

        <div class="fg">
          <label>Password</label>
          <div class="fg-pw">
            <input type="password" name="password" id="pw1" class="fg-input">
            <button type="button" onclick="eyeToggle('pw1',this)">👁</button>
          </div>
        </div>

        <button type="submit" class="btn-submit">Masuk ke Sistem</button>
      </form>

    </div>
  </div>

</div>
</div>


<script>
document.querySelectorAll('.rpick').forEach(el => {
  el.addEventListener('click', () => {
    document.querySelectorAll('.rpick').forEach(x => x.classList.remove('rpick--on'));
    el.classList.add('rpick--on');
  });
});

function eyeToggle(id, btn) {
  const i = document.getElementById(id);
  i.type = i.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>
