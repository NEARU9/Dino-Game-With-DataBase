<?php
session_start();
include "koneksi.php";

// Validasi form
$errors = array();

if ($_POST["signup"]) {
    $signup_username = mysqli_real_escape_string($kon, $_POST["signup_username"]);
    $signup_password = mysqli_real_escape_string($kon, $_POST["signup_password"]);

    // Validasi apakah username atau password kosong
    if (empty($signup_username) || empty($signup_password)) {
        $errors[] =  "Username dan Password harus diisi";
    }

    if (empty($errors)) {
        // Validasi apakah username sudah ada
        $sqlCheckUsername = mysqli_query($kon, "SELECT * FROM tbplayer WHERE username='$signup_username'");
        $countUsername = mysqli_num_rows($sqlCheckUsername);

        if ($countUsername > 0) {
            $errors[] = "Username sudah digunakan, silakan pilih username lain.";
        } else {
            // Jika username belum ada, lakukan proses signup
            $sqlSignup = mysqli_query($kon, "INSERT INTO tbplayer (username, password) VALUES ('$signup_username', '$signup_password')");
            if ($sqlSignup) {
                echo "<div align='center' style='color: white;'>Signup Berhasil. Selamat datang, $signup_username!</div>";
                // Redirect ke index.php setelah signup berhasil
                header("Location: index.php");
                exit(); // Penting untuk memastikan tidak ada output lain setelah header
            } else {
                echo "<div align='center' style='color: red;'>Signup Gagal</div>";
            }
        }
    } else {
        // Tampilkan pesan kesalahan
        $error_message = implode("<br>", $errors);
        echo "<script>document.getElementById('error-message').innerHTML = '$error_message';</script>";
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="signup.css">
    <style>
        #error-message {
            color: red;
        }
    </style>

</head>

<body>
<div id="signin">
    <fieldset>
        <!-- Form Signup -->
        <form name="formSignup" method="post" action="" enctype="multipart/form-data">
            <h3>SILAHKAN SIGNUP</h3>
            
            <input name="signup_username" type="text" id="signup_username" placeholder="Buat Username">
            <input name="signup_password" type="password" id="signup_password" placeholder="Buat Password">
            <input name="signup" type="submit" value="SIGNUP">
        </form>

        <!-- Tambahkan elemen div untuk menampilkan pesan kesalahan -->
        <div id="error-message" align="center"></div>
    </fieldset>

    <!-- PHP code ... -->
    <?php
    if (empty($errors)) {
        // ... (kode lainnya)

    } else {
        // Tampilkan pesan kesalahan
        $error_message = implode("<br>", $errors);
        echo "<script>document.getElementById('error-message').innerHTML = '$error_message';</script>";
    }
    ?>
</div>
</body>
</html>

