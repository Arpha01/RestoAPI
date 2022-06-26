# RestoAPI

Halo 👋 selamat datang di halaman repository RestoAPI. RestoAPI adalah sebuah aplikasi web sederhana pendataan restoran terfavorit di seluruh indonesia yang menggunakan Laravel sebagai API dan Angular sebagai Frontend.

Untuk repository UI Frontend dapat diakses di https://github.com/Arpha01/RestoUI

Untuk format waktu yang digunakan dalam project ini menggunakan 24hour format, dan **tidak** menggunakan am/pm

#### Tools yang digunakan dalam pengembangan

| Tools                                | Version       |
| -------------                        |:-------------:|
| Composer                             | 2.1.14        |
| Laravel                              | 9.11          |
| PHP                                  | 8.0.7         |
| Laravel Sanctum (Authentication)     | 2.15          |
| Laragon                              | 5.0           |
| Postman                              | -             | 

# Setup
- Clone Repository ini
- Ekstrak file yang telah didownload
- Jalankan terminal, dan arahkan terminal ke direktori ekstrak file menggunakan `cd pathtodirectory`
- Dalam terminal jalankan `composer install`
- Ubah nama file .env.example file ke .env
- Edit file .env pada `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` sesuaikan dengan konfigurasi database anda

# Kredensial
Berikut adalah kredensial default yang dapat digunakan untuk login

| Email                                | Password      | Type    |
| -------------                        |:-------------:|---------|
| admin@mail.com                       | admin123      | Admin   |
| user@mail.com                        | user123       | User    |

# Tampilan & Pengujian
Halaman home 
![halaman home](https://user-images.githubusercontent.com/11209553/175824414-c06af4c0-5368-4d2a-b1ea-8d6fc88191d2.png)

Halaman login user biasa di  /auth/login
![Halaman login user](https://user-images.githubusercontent.com/11209553/175824436-6a237d7f-ccf0-4fc6-ac9a-1bb36d6fd4c0.png)
 
Halaman login user Admin di /auth/admin/login
 ![Halaman login admin](https://user-images.githubusercontent.com/11209553/175824422-d1996c75-8a05-404c-b8b9-ef5c053155d5.png)

User melihat data restoran di /restaurant
![User melihat data restoran](https://user-images.githubusercontent.com/11209553/175824443-bd87e10f-53d0-48bf-bd9a-403bb263d4fa.png)

User melakukan filter berdasarkan hari dan waktu
![User filter data restoran](https://user-images.githubusercontent.com/11209553/175824464-88e960d2-2e18-4c3d-9531-3da1b1221221.png)

Admin dapat melihat data restoran
![Admin melihat data restoran](https://user-images.githubusercontent.com/11209553/175824473-4b9c4f64-3f55-4b20-a093-6dfd9fbe59f3.png)
 
Admin melakukan filter data restoran
![Admin filter data restoran](https://user-images.githubusercontent.com/11209553/175824485-bf0fb00d-0244-4b3a-9813-d1db7d613214.png)

Admin menambahkan restoran baru di /restaurant/create
![Admin Buat data restoran](https://user-images.githubusercontent.com/11209553/175824492-c65226f5-19db-4591-81b2-fe9e102d7aa4.png)

Admin berhasil menambahkan restoran baru
![Admin berhasil simpan data restoran](https://user-images.githubusercontent.com/11209553/175824504-7d77cd82-c396-45e2-8ed9-1f0c4ac83b38.png)

