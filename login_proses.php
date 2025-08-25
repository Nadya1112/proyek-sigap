<?php
echo "Data dari formulir sudah diterima!" . PHP_EOL;

if (isset($_POST['email']) && isset($_POST['password'])) {
    
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    echo "Email/Akun: " . $email . PHP_EOL;
    echo "Kata Sandi: " . $password . PHP_EOL;

    
    
    if ($email == "admin@sigap.co.id" && $password == "rahasia123") {
        echo "Login berhasil! Selamat datang.";
    } else {
        echo "Login gagal. Email atau kata sandi salah.";
    }

} else {
    echo "Mohon lengkapi formulir.";
}
?>