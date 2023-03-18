<?php
session_start();

if(isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}

if(isset($_SESSION["success"])) {
    $success = "<div class=\"success-msg\">".$_SESSION["success"]."</div>";
}

require "login_action.php";

$error = "";

if(isset($_POST["login"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $login = login($username, $password);
    if($login == true) {
        $admin = query("SELECT id FROM members WHERE username = '".$_POST["username"]."'")[0]["id"];

        $_SESSION["login"] = true;
        $_SESSION["user_id"] = $admin;

        header("Location: index.php");
        exit;
    } else {
        $error = "<div class=\"error-msg\">Username / Password salah!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css?ts=<?=time()?>&quot">
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
        <form class="form center" method="post">
            <h1>User Login</h1>
            <?php 
            if(isset($_SESSION["success"])) {
                echo $success;
                unset($_SESSION["success"]);
            }
            ?>
            <?= $error; ?>
            <div class="input-wrap">
                <label for="username">Username</label>
                <input type="text" name="username" class="input" id="username">
            </div>
            <div class="input-wrap">
                <label for="password">Password</label>
                <input type="password" name="password" class="input" id="password">
            </div>
            <button type="submit" name="login" class="btn-submit">Login</button>
            <div class="form-links">
                <div class="register">
                    <a href="register.php" class="link">Sign Up</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>