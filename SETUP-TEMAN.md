# Panduan Copy Project ke Laptop Teman

Ikuti langkah ini supaya tampilan, cover, dan PDF tidak hilang saat project dicoba di laptop lain.

## 1. Folder yang wajib ikut

Saat copy project, pastikan folder ini ikut:

- `app`
- `bootstrap`
- `config`
- `database`
- `datasql`
- `public`
- `resources`
- `routes`
- `storage/app/public`
- `vendor`
- `.env`
- `artisan`
- `composer.json`
- `composer.lock`

Yang paling sering bikin PDF hilang adalah folder:

```text
storage/app/public/ebooks
storage/app/public/covers
```

Database hanya menyimpan nama/path file PDF. Jadi kalau database sudah di-import tapi file di `storage/app/public/ebooks` tidak ada, PDF tidak akan muncul.

## 2. Import database

Di phpMyAdmin/XAMPP teman:

1. Buat database bernama `ebook_db`.
2. Import file:

```text
datasql/ebook_db.sql
```

Lalu pastikan isi `.env` sesuai:

```env
DB_DATABASE=ebook_db
DB_USERNAME=root
DB_PASSWORD=
```

Kalau MySQL teman pakai password, isi `DB_PASSWORD` sesuai password laptop teman.

## 3. Buat ulang storage link

Di terminal, masuk ke folder project:

```bash
cd C:\xampp\htdocs\e-book
php artisan storage:link
php artisan optimize:clear
```

Catatan: jangan hanya copy folder `public/storage` dari laptop lama. Di Windows, folder itu biasanya link/junction, dan sering rusak saat dipindah. Buat ulang dengan `php artisan storage:link`.

## 4. Jalankan project dengan cara yang sama

Cara paling aman:

```bash
php artisan serve
```

Buka URL yang muncul, biasanya:

```text
http://127.0.0.1:8000
```

Kalau `.env` masih memakai:

```env
APP_URL=http://localhost:8000
```

maka aksesnya juga sebaiknya dari port `8000`, bukan langsung dari folder `htdocs`.

## 5. Kalau tampilan beda

Halaman utama memakai font dan icon dari internet:

```text
fonts.googleapis.com
unpkg.com
cdnjs.cloudflare.com
```

Kalau laptop teman offline atau CDN diblokir, font/icon bisa berbeda. Solusinya:

- pastikan laptop teman terkoneksi internet, atau
- download asset CDN itu ke project dan ubah menjadi asset lokal.

## 6. Kalau PDF tetap tidak muncul

Cek ini satu per satu:

1. File PDF ada di `storage/app/public/ebooks`.
2. Path di database tabel `ebooks.file_path` sama dengan nama file PDF.
3. Sudah menjalankan `php artisan storage:link`.
4. Buku yang dites gratis, atau user sudah membeli dan status pembelian `approved`.

PDF berbayar memang tidak bisa dibuka kalau user belum punya akses. Untuk tes cepat, coba buku gratis seperti `Atomic Habits`, `Psychology of Money`, atau `Mindset`.

