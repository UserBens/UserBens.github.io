# Setup Laravel Portfolio + CI/CD (100% Gratis)

Stack yang dipakai — semua gratis, tanpa langganan:
- **Laravel 11** (backend + Blade)
- **SQLite** sebagai database (file lokal, tidak butuh server DB berbayar)
- **GitHub** (repo + GitHub Actions, gratis untuk repo publik & sebagian besar kebutuhan repo privat)
- **Render.com** free web service (hosting, tidak butuh kartu kredit)

File-file di paket ini (`.github/workflows/ci-cd.yml`, `Dockerfile`, `render.yaml`, `.env.example`, `docker-compose.yml`) tinggal ditaruh di root project Laravel kamu.

## 1. Buat project Laravel baru (lokal)
```bash
composer create-project laravel/laravel laravel-portfolio
cd laravel-portfolio
```
Lalu salin semua file dari paket ini ke folder project (termasuk folder `.github/`).

## 2. Pakai SQLite (biar gratis, tanpa server DB)
```bash
touch database/database.sqlite
```
Edit `.env` → set `DB_CONNECTION=sqlite` dan `DB_DATABASE=database/database.sqlite` (sudah ada contohnya di `.env.example`).

## 3. Buat model/migration untuk portofolio
Contoh minimal:
```bash
php artisan make:model Project -mcr
```
Isi migration dengan kolom seperti `title`, `description`, `tech_stack`, `image`, `url_demo`, `url_repo`. Buat controller + view Blade untuk menampilkan daftar project, skills, timeline, dsb (bisa reuse struktur & palet warna dari portofolio vanilla kamu yang sudah ada — hero, skills, timeline, sertifikasi, projects, education).

## 4. Push ke GitHub
```bash
git init
git add .
git commit -m "init laravel portfolio"
gh repo create laravel-portfolio --public --source=. --push
# atau buat repo manual di github.com lalu git remote add origin ...
```

## 5. Setup Render.com (hosting gratis)
1. Daftar di https://render.com pakai akun GitHub (tidak perlu kartu kredit untuk free web service).
2. Dashboard → **New** → **Blueprint** → pilih repo `laravel-portfolio`. Render otomatis membaca `render.yaml`.
3. Setelah service dibuat, buka **Settings → Deploy Hook**, salin URL-nya.
4. **Matikan** auto-deploy bawaan Render (sudah di-set `autoDeploy: false` di `render.yaml`) — supaya deploy hanya terjadi lewat GitHub Actions setelah test lolos.

## 6. Setup GitHub Actions (CI/CD)
1. Di repo GitHub → **Settings → Secrets and variables → Actions → New repository secret**.
2. Tambahkan secret `RENDER_DEPLOY_HOOK` = URL deploy hook dari langkah 5.
3. Setiap kali kamu `git push` ke branch `main`:
   - Job **test** jalan dulu (composer install, migrate, `php artisan test`).
   - Kalau lolos → job **deploy** otomatis memanggil deploy hook Render → Render build ulang & deploy.
   - Kalau test gagal → deploy **tidak** jalan (ini inti dari CI/CD: gagal test = tidak naik ke production).

## 7. Cek hasilnya
- Progress build & test: tab **Actions** di GitHub.
- Progress deploy & log runtime: dashboard Render → tab **Logs**.
- Free tier Render akan "tidur" kalau tidak diakses ~15 menit, dan bangun lagi otomatis saat ada request (perlu beberapa detik pertama kali) — wajar untuk tier gratis.

## Alternatif hosting gratis lain (kalau Render tidak cocok)
- **Railway.app** — ada free trial credit, tapi sekarang minta kartu kredit untuk verifikasi.
- **Fly.io** — free allowance untuk app kecil, butuh kartu kredit untuk verifikasi tapi tidak ditagih di batas gratis.
- Kalau mau full gratis tanpa kartu kredit sama sekali, Render biasanya jadi pilihan paling mudah untuk Laravel.

## Testing tambahan (opsional tapi disarankan)
Tambahkan test sederhana di `tests/Feature/HomePageTest.php`:
```php
public function test_homepage_loads(): void
{
    $response = $this->get('/');
    $response->assertStatus(200);
}
```
Ini akan otomatis ikut jalan di job **test** pada workflow CI/CD.
