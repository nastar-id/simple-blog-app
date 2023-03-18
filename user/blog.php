<?php
session_start();

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require "../conn.php";
$user_id = $_SESSION["user_id"];
$member = query("SELECT * FROM members WHERE id = $user_id")[0];
$result = query("SELECT * FROM posts WHERE id_user = $user_id ORDER BY id DESC");

if(isset($_GET["logout"])) {
    session_destroy();
    session_unset();
    header("Location: login.php");
    exit;
}

if(isset($_POST["tambah"])) {
    $title = htmlspecialchars($_POST["title"]);
    $category = htmlspecialchars($_POST["category"]);
    $post = $_POST["post"];

    $forbidden_keyword = ["<script>", "<\/script>", "<div", "alert\(", "javascript:", "style=", "onload="];
    foreach($forbidden_keyword as $forbidden) {
        if(preg_match("/$forbidden/", $post)) {
            echo "
                <script>
                    alert('Forbidden characters detected')
                    window.location = 'blog.php'
                </script>
            "; exit;
        }
    }

    $file = $_FILES["gambar"]["name"];
    $explode = explode(".", $file);

    $fileSize = $_FILES["gambar"]["size"];
    $fileExt = strtolower(end($explode));
    $fileName = uniqid().".$fileExt";

    $allowedExt = ["jpg", "jpeg", "png"];

    if($_FILES["gambar"]["error"] == 4) {
        echo "
        <script>
            alert('Pilih gambar terlebih dahulu')
            window.location = 'blog.php'
        </script>
        "; exit;
    }


    if(!in_array($fileExt, $allowedExt)) {
        echo "
        <script>
            alert('Tipe file dilarang')
            window.location = 'blog.php'
        </script>
        "; exit;
    }
    
    if($fileSize > 3000000) {
        echo "
        <script>
            alert('Ukuran gambar terlalu besar')
            window.location = 'blog.php'
        </script>
        "; exit;
    }

    move_uploaded_file($_FILES["gambar"]["tmp_name"], "uploads/$fileName");
    $image = $fileName;

    mysqli_query($conn, "INSERT INTO posts VALUES(NULL, '$user_id', '$title', '$post', '$category', '$image')");
    if(mysqli_affected_rows($conn) > 0) {
        echo "
            <script>
                alert('Sukses Ditambahkan')
                window.location = '?'
            </script>
            ";
    } else {
        echo "<script>alert('Gagal Ditambahkan')</script>";
    }
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
                        <a href="blog.php" class="nav-link active">Blog</a>
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
        <h2 style="margin-top: 20px;">Daftar Artikel</h2>
        
        <div class="modal-box" id="add-form">
            <div class="modal-content-wrapper">
                <div class="modal-close" id="close-form">
                    <i class="bi bi-x-lg"></i>
                </div>
                <form method="post" class="form" enctype="multipart/form-data">
                    <div class="input-wrap">
                        <label for="title">Judul</label>
                        <input type="text" name="title" class="input" id="title" required>
                    </div>
                    <div class="input-wrap">
                        <label for="category">Kategori</label>
                        <input type="text" name="category" class="input" id="category">
                    </div>
                    <div class="input-wrap">
                        <label for="post">Artikel</label>
                        <textarea name="post" id="post"></textarea>
                    </div>
                    <div class="input-wrap">
                        <label for="gambar">Gambar</label>
                        <input type="file" name="gambar" class="input" id="gambar">
                    </div>
                    <button type="submit" name="tambah" class="btn-submit">Tambah</button>
                </form>
            </div>
        </div>
        
        <div class="actions">
            <button id="add-artikel">Tambah Artikel</button>
            <form method="get">
                <input type="text" name="src" id="searchbar" size="20" placeholder="Cari artikel" autocomplete="off">
            </form>
        </div>
        
        <div id="table">
            <?php if(!empty($result)): ?>
            <table border="1" cellspacing="0" class="table">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Artikel</th>
                    <th>Kategori</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>

                <?php
                $int = 1;
                foreach($result as $data):
                $title_wrapped = substr($data["title"], 0, 45);
                $post_content = (strlen($data["post"]) > 70)
                            ? substr($data["post"], 0, 70)."..."
                            : $data["post"];
                ?>

                <tr>
                    <td><?= $int; ?></td>
                    <td style="width: 25%;"><?= $title_wrapped; ?></td>
                    <td style="width: 55%;"><?= $post_content; ?></td>
                    <td style="width: 10%;"><?= $data["category"]; ?></td>
                    <td><img src="uploads/<?= $data["image"]; ?>"></td>
                    <td nowrap="nowrap" width="50">
                        <a href="edit_post.php?id=<?= $data["id"]; ?>">Edit</a>
                        <a href="delete_post.php?id=<?= $data["id"]; ?>" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                    </td>
                </tr>
                
                <?php $int++; ?>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <span style="display: block; text-align: center; margin-top: 25px;">There's no data</span>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="../assets/js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchbar').on('input', function() {
                let value = $('#searchbar').val();
                $('#table').load(`data.php?src=${value}`);
            });
        });
    </script>
</body>
</html>