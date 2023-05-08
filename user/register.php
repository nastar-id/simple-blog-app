<?php
session_start();

require "../conn.php";

$msg = "";

if(isset($_POST["nama"])) {
    $nama = $_POST["nama"];
    $user = $_POST["user"];
    $mail = $_POST["email"];
    $pass = $_POST["pass"];
    $pass2 = $_POST["pass2"];

    $same_user = mysqli_query($conn, "SELECT username FROM members WHERE username = '$user'");
    $same_email = mysqli_query($conn, "SELECT email FROM members WHERE email = '$mail'");

    if($pass2 !== $pass) {
        $msg = "Konfirmasi password tidak sesuai";
        echo $msg; exit;
    } elseif(mysqli_fetch_assoc($same_user)) {
        $msg = "Username ini sudah terdaftar";
        echo $msg; exit;
    } elseif(mysqli_fetch_assoc($same_email)) {
        $msg = "Email ini sudah terdaftar";
        echo $msg; exit;
    } else {
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO members VALUES(NULL, '$nama', '$mail', '$user', '$pass', 'nophoto.png')");
        
        if(mysqli_affected_rows($conn) > 0) {
            $msg = "Registrasi sukses";

            $_SESSION["success"] = $msg;
            echo $msg; exit;
        } else {
            $msg = "Registrasi gagal";
            echo $msg; exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body {
            background-color: firebrick;
        }

        .form .form-links {
            margin: 11px auto 0 auto;
        }
    </style>
</head>
<body>

    <div class="container">
        <form method="post" class="form center" enctype="multipart/form-data">
            <h1>Registrasi admin</h1>
                <div class="input-wrap">
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" class="input" id="nama">
                </div>
                <div class="input-wrap">
                    <label for="user">Username</label>
                    <input type="text" name="user" class="input" id="user">
                </div>
                <div class="input-wrap">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="input" id="email">
                </div>
                <div class="input-wrap">
                    <label for="pass">Password</label>
                    <input type="password" name="pass" class="input" id="pass">
                </div>
                <div class="input-wrap">
                    <label for="pass2">Konfirmasi Password</label>
                    <input type="password" name="pass2" class="input" id="pass2">
                </div>
                <button type="submit" name="submit" class="btn-submit">Register</button>
                <div class="form-links">
                    <div>
                        <a href="login.php">Sign In</a>
                    </div>
                </div>
                <div id="msg"></div>
        </form>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.form').on('submit', (function(e) {
                const form = document.querySelector('.form');
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    data:  new FormData(form),
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data) {
                        if(data != 'Registrasi sukses') {
                            $('#msg').html(`<div class="error-msg">${data}</div>`);
                        } else {
                            window.location = 'login.php';
                        }
                    }
                });
            }));
        });
    </script>
</body>
</html>
