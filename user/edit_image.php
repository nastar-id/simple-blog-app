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

$id = $_GET["id"];
if(empty($id)) {
    header("Location: index.php");
    exit;
}
if(!is_numeric($id)) die("404");

$user_id = $_SESSION["user_id"];
$member = query("SELECT * FROM members WHERE id = $user_id")[0];
$query = "SELECT * FROM gallery WHERE id_user = '$user_id' AND id = $id";
$data = query($query)[0];

if(isset($_GET["edit"])) {
    $file = $_FILES["gambar"]["name"];
    $explode = explode(".", $file);

    $fileSize = $_FILES["gambar"]["size"];
    $fileExt = strtolower(end($explode));
    $fileName = "IMG_".uniqid().".$fileExt";

    $allowedExt = ["jpg", "jpeg", "png"];

    if($_FILES["gambar"]["error"] == 4) {
        echo json_encode(["err" => "Please select file"]); exit;
    }


    if(!in_array($fileExt, $allowedExt)) {
        echo json_encode(["err" => "Invalid file type"]); exit;
    }
    
    if($fileSize > 3000000) {
        echo json_encode(["err" => "File size is too big"]); exit;
    }

    move_uploaded_file($_FILES["gambar"]["tmp_name"], "uploads/gallery/$fileName");
    $image = $fileName;

    mysqli_query($conn, "UPDATE gallery set image = '$image' WHERE id_user = '$user_id' AND id = $id");
    if(mysqli_affected_rows($conn) > 0) {
        unlink("uploads/gallery/".$data["image"]);
        echo json_encode(["success" => ["image" => $image, "message" => "Successfully edited"]]); exit;
    } else {
        echo json_encode(["err" => "Edit failed"]); exit;
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

    <div class="container">
        <h2 style="margin-top: 20px;">Edit Gambar</h2>

        <form method="post" class="form" style="padding-left: 0; padding-right: 0;" enctype="multipart/form-data">
            <div id="msg"></div>
            <div class="input-wrap">
                <img src="uploads/gallery/<?= $data["image"]; ?>" height="150">
                <label for="gambar">Gambar (Max 3mb)</label>
                <input type="file" name="gambar" class="input" id="gambar">
            </div>
            <button type="submit" name="edit" class="btn-submit">Edit</button>
            <a href="galeri.php" class="btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="../assets/js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.form').on('submit', function(e) {
                e.preventDefault();
                const form = $('.form').get(0);
                $.ajax({
                    url: "?id=<?= $id; ?>&edit",
                    type: "POST",
                    data: new FormData(form),
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data) {
                        // console.log(data);
                        const msg = JSON.parse(data);
                        if("success" in msg == false) {
                            $('#msg').html(`<div class="error-msg">${msg.err}</div>`);
                        } else{
                            $('#msg').html(`<div class="success-msg">${msg.success.message}</div>`);
                            $('.input-wrap img').attr('src', `uploads/gallery/${msg.success.image}`)
                            $('#gambar').val('');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>