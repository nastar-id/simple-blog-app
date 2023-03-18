<?php
session_start();


if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if(isset($_GET["logout"])) {
    session_destroy();
    session_unset();
    header("Location: login.php");
    exit;
}

require "../conn.php";

function filesize_formatted($path)
{
    $size = filesize($path);
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

$user_id = $_SESSION["user_id"];
$member = query("SELECT * FROM members WHERE id = $user_id")[0];
$result = query("SELECT * FROM gallery WHERE id_user = $user_id");

if(isset($_POST["tambah"])) {
    $file = $_FILES["gambar"]["name"];
    $explode = explode(".", $file);

    $fileSize = $_FILES["gambar"]["size"];
    $fileExt = strtolower(end($explode));
    $fileName = "IMG_".uniqid().".$fileExt";

    $allowedExt = ["jpg", "jpeg", "png"];

    if($_FILES["gambar"]["error"] == 4) {
        echo "
        <script>
            alert('Pilih gambar terlebih dahulu');
            window.location = '?';
        </script>
        "; die;
    }


    if(!in_array($fileExt, $allowedExt)) {
        echo "
        <script>
            alert('Tipe file dilarang');
            window.location = '?';
        </script>
        "; die;
    }
    
    if($fileSize > 3000000) {
        echo "
        <script>
            alert('Ukuran gambar terlalu besar');
            window.location = '?';
        </script>
        "; die;
    }

    move_uploaded_file($_FILES["gambar"]["tmp_name"], "uploads/gallery/$fileName");
    $image = $fileName;

    mysqli_query($conn, "INSERT INTO gallery VALUES(NULL, '$user_id', '$image')");
    if(mysqli_affected_rows($conn) > 0) {
        echo "
            <script>
                alert('Sukses Ditambahkan');
                window.location = '?';
            </script>
            ";
    } else {
        echo "<script>alert('Gagal Ditambahkan')</script>";
    }
}

function show_images($image, $int) {
    if($int <= 6) {
        return "<div class=\"photo\">
            <img src=\"uploads/gallery/$image\">
        </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>

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
                        <a href="galeri.php" class="nav-link active">Galeri</a>
                    </li>
                </ul>
                <div class="add">
                    <a href="profile.php"><img src="images/<?= $member["image"] ?>" class="profile-thumb"></a>
                    <a href="?logout" class="logout"><i class="bi bi-box-arrow-in-right"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <div class="modal-box" id="add-form">
        <div class="modal-content-wrapper">
            <div class="modal-close" id="close-form">
                <i class="bi bi-x-lg"></i>
            </div>
            <form method="post" class="form" enctype="multipart/form-data">
                <div class="input-wrap">
                    <label for="gambar">Gambar (Max 3mb)</label>
                    <input type="file" name="gambar" class="input" id="gambar">
                </div>
                <button type="submit" name="tambah" class="btn-submit">Tambah</button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <h2 style="margin-top: 20px;">Gallery Gambar</h2>

        <div class="actions">
            <button id="add-artikel">Tambah Gambar</button>
        </div>
        <?php if(!empty($result)): ?>
            <div id="table">
                <table border="1" cellspacing="0" class="table">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>File name</th>
                        <th>Size</th>
                        <th>Aksi</th>
                    </tr>
                    <?php $i = 1; foreach($result as $data): ?>
                    <tr>
                        <td style="width: 5%;"><?= $i; ?></td>
                        <td style="width: 17%;"><img src="uploads/gallery/<?= $data["image"]; ?>" alt=""></td>
                        <td><a href="uploads/gallery/<?= $data["image"]; ?>" target="_blank"><?= $data["image"]; ?></a></td>
                        <td nowrap="nowrap" style="width: 15%;"><?= filesize_formatted("uploads/gallery/".$data["image"]); ?></td>
                        <td nowrap="nowrap" style="width: 5%;">
                            <a href="edit_image.php?id=<?= $data["id"]; ?>">Edit</a>
                            <a href="delete_image.php?id=<?= $data["id"]; ?>" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php $i++; endforeach; ?>
                </table>
            </div>
        <?php else: ?>
        <span style="display: block; text-align: center; margin-top: 25px;">There's no data</span>
        <?php endif; ?>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>