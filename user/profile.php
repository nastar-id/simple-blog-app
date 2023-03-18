<?php
session_start();

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require "../conn.php";
$user_id = $_SESSION["user_id"];
$member = query("SELECT * FROM members WHERE id = $user_id")[0];

$posts = query("SELECT * FROM posts WHERE id_user = $user_id");
$count_posts = count($posts);

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
    <link rel="stylesheet" href="../assets/css/style.css?ts=<?=time()?>&quot">
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
                        <a href="../index.php" class="nav-link">Home</a>
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
        <h2 style="margin-top: 20px;">Detail Akun</h2>
        <div id="msg"></div>
        <div class="profile">
            <div class="profile-details">
                <div class="background-header"></div>
                <div class="details">
                    <div class="profile-image">
                        <img src="images/<?= $member["image"] ?>">
                        <label for="pfp"><i class="bi bi-camera"></i></label>
                    </div>
                    <div>
                        <p id="name"><?= $member["nama"]; ?></p>
                        <p id="mail"><?= $member["email"]; ?></p>
                    </div>
                </div>
                <div class="footer">
                    <div class="footer-info">
                        <span>Posts</span>
                        <span><?= $count_posts; ?></span>
                    </div>
                </div>
            </div>

            <div class="profile-edit">
                <form method="POST" class="form" enctype="multipart/form-data">
                    <input type="file" name="pfp" id="pfp" style="display: none;">
                    <label for="nama">Nama</label>
                    <div class="input-wrap">
                        <input type="text" name="nama" class="input" id="nama" value="<?= $member["nama"]; ?>" required>
                    </div>
                    <div class="input-wrap">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="input" id="email" value="<?= $member["email"]; ?>" required>
                    </div>
                    <div class="input-wrap">
                        <label for="user">Username</label>
                        <input type="text" name="user" class="input" id="user" value="<?= $member["username"]; ?>" required>
                    </div>
                    <div class="input-wrap">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="input" id="password">
                    </div>
                    <div class="input-wrap">
                        <label for="password2">Repeat Password</label>
                        <input type="password" name="password2" class="input" id="password2">
                    </div>
                    <button type="submit" class="btn-submit" name="submit">Edit Profile</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pfp').on('change', function() {
                if($('#pfp').get(0).files[0]){
                    const reader = new FileReader();
                    reader.onload = function(){
                        $('.profile-image img').attr('src', reader.result);
                    }
                    reader.readAsDataURL($('#pfp').get(0).files[0]);
                }
            });

            $('.form').on('submit', function(e) {
                e.preventDefault();
                const form = document.querySelector('.form');
                $.ajax({
                    url: "edit_profile.php?id=<?= $member["id"]; ?>",
                    type: "POST",
                    data: new FormData(form),
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data) {
                        // console.log(data);
                        const msg = JSON.parse(data);
                        if("success" in msg == false) {
                            $('#msg').html(`<div class="error-msg" style="margin-top: 10px;">${msg.err}!</div>`);
                        } else {
                            $('#msg').html(`<div class="success-msg" style="margin-top: 10px;">${msg.success.msg}!</div>`);
                            $('.profile-thumb').attr('src', `images/${msg.success.img}`);
                            $('#name').text($('#nama').val());
                            $('#mail').text($('#email').val());
                        }

                        $('input[type="password"]').val('');
                    }
                });
            });
        });
    </script>
</body>
</html>