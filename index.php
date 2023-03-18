<?php
session_start();
require "conn.php";

if(isset($_GET["src"])) {
    $search = $_GET["src"];

    $keyword = "SELECT * FROM posts 
                WHERE
                title LIKE '%$search%' OR
                post LIKE '%$search%' OR
                category LIKE '%$search%'
                ORDER BY id DESC";
    $query = query($keyword);
} else {
    $query = query("SELECT * FROM posts ORDER BY id DESC");
}

$post = query("SELECT * FROM posts ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css?ts=<?=time()?>&quot">
</head>
<body>

    <div class="corner-links">
        <a href="https://github.com/nastar-id" target="_blank" rel="noopener noreferrer"><i class="bi bi-github"></i></a>
        <a href="https://instagram.com/rasyad173" target="_blank" rel="noopener noreferrer"><i class="bi bi-instagram"></i></a>
        <a href="https://www.facebook.com/profile.php?id=100055993891162" target="_blank" rel="noopener noreferrer"><i class="bi bi-facebook"></i></a>
    </div>

    <div class="container">
        <h1 style="margin-top: 25px; font-size: 29px;">Daftar postingan</h1>
        <?php if(isset($_GET["src"])): ?>
        <p style="margin-top: 15px;">Hasil pencarian: <?= $_GET["src"]; ?></p>
        <a href="index.php" style="display: inline-block; margin-top: 3px;">Kembali</a>
        <?php endif; ?>

        <div class="blog">
            <div class="side-section">
                <?php 
                if(isset($_SESSION["login"])):
                $user_id = $_SESSION["user_id"];
                $user = query("SELECT * FROM members WHERE id = $user_id")[0];
                ?>
                <div class="account">
                    <span>Logged in as</span>
                    <a href="user/profile.php">
                        <img src="user/images/<?= $user["image"]; ?>" alt="" class="profile-thumb">
                        <?= $user["nama"]; ?>
                    </a>
                </div>
                <?php endif; ?>

                <div class="search-bar">
                    <form method="get">
                        <input type="text" name="src" size="20" placeholder="Cari artikel" autocomplete="off">
                        <button type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>
                <div class="recent-post">
                    <h2>Recent post</h2>
                    <div class="post-info" style="box-shadow: none;">
                        <a href="blog.php?id=<?= $post[0]["id"]; ?>">
                            <div class="img-wrap">
                                <img src="user/uploads/<?= $post[0]["image"]; ?>">
                                <div class="category"><?= $post[0]["category"]; ?></div>
                            </div>
                        </a>
                        <div class="post-title" style="font-size: 18px; padding-bottom: 12px;">
                            <a href="blog.php?id=<?= $post[0]["id"]; ?>"><?= $post[0]["title"]; ?></a>
                        </div>
                    </div>
                </div>
                <div class="categories">
                    <h2>Categories</h2>
                    <div id="cat">

                        <?php 
                        $category = query("SELECT DISTINCT category FROM posts");
                        foreach($category as $cat):
                            $cat = $cat["category"];
                            $cate = query("SELECT category FROM posts WHERE category ='$cat'");
                            foreach($cate as $kategori)
                            ?>
                            <a href="?src=<?= $kategori["category"]; ?>">
                                <div class="cat">
                                    <span class="total"><?= count($cate); ?></span>
                                    <span class="cat-name"><?= $kategori["category"]; ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
    
            <div class="posts">
                <?php 
                foreach($query as $data): 
                $user_id = $data["id_user"];
                $user = query("SELECT nama, image FROM members WHERE id = $user_id")[0];
                $post_preview = (strlen($data["post"]) > 125)
                                ? substr($data["post"], 0, 125)."..."
                                : $data["post"];
                ?>
                <div class="post-info">
                    <div class="post-header">
                        <div class="name">
                            <img src="user/images/<?= $user["image"]; ?>" class="profile-thumb"><?= $user["nama"]; ?>
                        </div> <span style="margin-left: 5px;">posted</span>
                    </div>
                    <a href="blog.php?id=<?= $data["id"]; ?>">
                        <div class="img-wrap">
                            <img src="user/uploads/<?= $data["image"]; ?>">
                            <div class="category"><?= $data["category"]; ?></div>
                        </div>
                    </a>
                    <div class="post-title">
                        <a href="blog.php?id=<?= $data["id"]; ?>"><?= $data["title"]; ?></a>
                    </div>
                    <div class="content-preview">
                        <p><?= $post_preview; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</body>
</html>