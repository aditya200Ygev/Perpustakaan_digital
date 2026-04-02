<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Perpustakaan Digital</title>

    @vite('resources/css/app.css')

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
</head>
<body>

<div class="auth-page">
<div class="auth-card">


  <div class="left-panel">
    <div class="lp-brand">
      <div class="lp-icon">📚</div>
      <h1>Perpustakaan<br>Digital</h1>
      <p>Daftarkan akun baru untuk mulai menggunakan sistem perpustakaan sekolah.</p>
    </div>

    <div class="lp-illustration">
      <svg viewBox="0 0 240 190"></svg>

      <div class="">
        <span class=""></span>
        <span class=""></span>
        <span class=""></span>
      </div>
    </div>
  </div>

  <div class="right-panel">

    <div class="rp-tabs">
      <a href="{{ route('login') }}" class="rp-tab">Masuk</a>
      <a href="{{ route('register') }}" class="rp-tab rp-tab--active">Daftar</a>
    </div>

    <div class="rp-body rp-body--scroll">

      <h2 class="rp-title">Buat Akun Baru ✨</h2>
      <p class="rp-sub">Daftarkan diri sesuai peran Anda.</p>

      @if($errors->any())
        <div class="flash flash--error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('register.post') }}">
    @csrf

    <!-- ROLE (default anggota) -->
    <input type="hidden" name="role" value="anggota">

    <!-- NAMA -->
    <div class="fg">
      <label class="fg-label">Nama</label>
      <input type="text" name="name" class="fg-input" value="{{ old('name') }}">
    </div>

    <!-- EMAIL -->
    <div class="fg">
      <label class="fg-label">Email</label>
      <input type="email" name="email" class="fg-input" value="{{ old('email') }}">
    </div>

    <!-- PASSWORD -->
    <div class="fg">
      <label class="fg-label">Password</label>
      <div class="fg-pw">
        <input type="password" name="password" id="pw1" class="fg-input">
        <button type="button" class="pw-eye" onclick="eyeToggle('pw1',this)">👁</button>
      </div>
    </div>

    <!-- KONFIRMASI PASSWORD -->
    <div class="fg">
      <label class="fg-label">Konfirmasi Password</label>
      <div class="fg-pw">
        <input type="password" name="password_confirmation" id="pw2" class="fg-input">
        <button type="button" class="pw-eye" onclick="eyeToggle('pw2',this)">👁</button>
      </div>
    </div>

    <!-- NO. TELEPON -->
    <div class="fg">
      <label class="fg-label">No. Telepon</label>
      <input type="text" name="no_telp" class="fg-input" value="{{ old('no_telp') }}">
    </div>

    <!-- ALAMAT -->
    <div class="fg">
      <label class="fg-label">Alamat</label>
      <input type="text" name="alamat" class="fg-input" value="{{ old('alamat') }}">
    </div>

    <!-- NIS -->
    <div class="fg">
      <label class="fg-label">NIS</label>
      <input type="text" name="nis" class="fg-input" value="{{ old('nis') }}">
    </div>

   <!-- KELAS -->
<div class="fg">
  <label class="fg-label">Kelas</label>
  <select name="kelas" class="fg-input">
    <option value="" disabled selected>Pilih kelas</option>
    <option value="X" {{ old('kelas') == 'X' ? 'selected' : '' }}>X</option>
    <option value="XI" {{ old('kelas') == 'XI' ? 'selected' : '' }}>XI</option>
    <option value="XII" {{ old('kelas') == 'XII' ? 'selected' : '' }}>XII</option>
  </select>
</div>

<!-- JURUSAN -->
<div class="fg">
  <label class="fg-label">Jurusan</label>
  <select name="jurusan" class="fg-input">
    <option value="" disabled selected>Pilih jurusan</option>
    <option value="PPLG" {{ old('jurusan') == 'PPLG' ? 'selected' : '' }}>PPLG</option>
    <option value="AKL" {{ old('jurusan') == 'AKL' ? 'selected' : '' }}>AKL</option>
    <option value="APAT" {{ old('jurusan') == 'APAT' ? 'selected' : '' }}>APAT</option>
    <option value="APHP" {{ old('jurusan') == 'APHP' ? 'selected' : '' }}>APHP</option>
    <option value="TBSM" {{ old('jurusan') == 'TBSM' ? 'selected' : '' }}>TBSM</option>
    <option value="TKRO" {{ old('jurusan') == 'TKRO' ? 'selected' : '' }}>TKRO</option>
  </select>
</div>

    <button type="submit" class="btn-submit">Daftar</button>
</form>


      <p class="switch-hint">
        Sudah punya akun?
        <a href="{{ route('login') }}">Masuk di sini</a>
      </p>

    </div>
  </div>

</div>
</div>

<!-- SCRIPT -->
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
  btn.textContent = i.type === 'password' ? '👁' : '🙈';
}
</script>

</body>
</html>
