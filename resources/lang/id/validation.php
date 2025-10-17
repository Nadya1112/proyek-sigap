<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut berisi pesan default yang digunakan oleh
    | kelas validator. Beberapa aturan memiliki beberapa versi seperti
    | aturan ukuran. Silakan sesuaikan setiap pesan di sini.
    |
    */

    'accepted' => ':attribute harus diterima.',
    'min' => [
        'string' => ':attribute harus memiliki minimal :min karakter.',
    ],
    'required' => 'Kolom :attribute wajib diisi.',
    'unique'   => ':attribute tersebut sudah terdaftar.',
    'email'    => ':attribute harus berupa alamat email yang valid.',
    'confirmed'=> 'Konfirmasi :attribute tidak cocok.',

    // Anda bisa menambahkan terjemahan lain di sini jika perlu

    /*
    |--------------------------------------------------------------------------
    | Atribut Validasi Kustom
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk menukar placeholder atribut
    | kami dengan sesuatu yang lebih mudah dibaca seperti "Alamat E-Mail"
    | sebagai ganti dari "email". Ini membantu kami membuat pesan kami lebih ekspresif.
    |
    */

    'attributes' => [
        'name'      => 'Nama lengkap',
        'email'     => 'Email',
        'kontak'    => 'Nomor HP',
        'password'  => 'Kata sandi',
    ],

];