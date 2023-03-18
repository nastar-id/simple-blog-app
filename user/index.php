<?php
session_start();

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require "../conn.php";
$user_id = $_SESSION["user_id"];
$member = query("SELECT * FROM members WHERE id = $user_id")[0];

if(isset($_GET["logout"])) {
    session_destroy();
    session_unset();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navigation">
        <div class="container">
            <div class="nav-content">
                <h1><a href="index.php">Dashboard</a></h1>
                <label class="hamburger" for="toggle">
                    <input type="checkbox" id="toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
                <ul class="nav-links">
                    <h1><a href="index.php">Dashboard</a></h1>
                    <li>
                        <a href="../index.php" class="nav-link active">Home</a>
                    </li>
                    <li>
                        <a href="blog.php" class="nav-link">Blog</a>
                    </li>
                    <li>
                        <a href="galeri.php" class="nav-link">Galeri</a>
                    </li>
                </ul>
                <div class="add">
                    <a href="profile.php"><img src="images/<?= $member["image"] ?>" class="profile-thumb"></a>
                    <a href="?logout" class="logout"><i class="bi bi-box-arrow-in-right"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 style="margin-top: 20px;">Hello <?= $member["nama"]; ?></h2>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>