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
if(!is_numeric($id)) exit("404");

$user_id = $_SESSION["user_id"];
$member = query("SELECT * FROM members WHERE id = $user_id")[0];

$query = "SELECT * FROM posts WHERE id_user = '$user_id' AND id = $id";
$data = query($query)[0];

if(isset($_GET["edit"])) {
    $title = htmlspecialchars($_POST["title"]);
    $category = htmlspecialchars($_POST["category"]);
    $post = $_POST["post"];

    $forbidden_keyword = ["<script>", "<\/script>", "<div>", "alert\(", "javascript:", "style="];
    foreach($forbidden_keyword as $forbidden) {
        if(preg_match("/$forbidden/", $post)) {
            echo json_encode(["err" => "Forbidden characters detected"]); exit;
        }
    }

    if(!empty($_FILES["gambar"]["name"])) {
        $file = $_FILES["gambar"]["name"];
        $explode = explode(".", $file);

        $fileSize = $_FILES["gambar"]["size"];
        $fileExt = strtolower(end($explode));
        $fileName = uniqid().".$fileExt";

        $allowedExt = ["jpg", "jpeg", "png"];

        if(!in_array($fileExt, $allowedExt)) {
            echo json_encode(["err" => "Invalid file type"]); exit;
        }
        
        if($fileSize > 3000000) {
            echo json_encode(["err" => "File size is too big"]); exit;
        }
        
        move_uploaded_file($_FILES["gambar"]["tmp_name"], "uploads/$fileName");
        $gambar = $fileName;
    } else {
        $gambar = $data["image"];
    }

    $update = "UPDATE posts SET
                                title = '$title',
                                category = '$category',
                                post = '$post',
                                image = '$gambar'
                                WHERE id_user = '$user_id' AND id = $id
            ";
    mysqli_query($conn, $update);

    if(mysqli_affected_rows($conn) > 0) {
        if($gambar != $data["image"]) unlink("uploads/".$data["image"]);
        echo json_encode(["success" => ["image" => $gambar, "message" => "Successfully edited"]]); exit;
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
        <h2 style="margin-top: 20px;">Edit Post</h2>

        <form method="post" class="form" style="padding-left: 0; padding-right: 0;" enctype="multipart/form-data">
            <div id="msg"></div>
            <div class="input-wrap">
                <label for="title">Judul</label>
                <input type="text" name="title" class="input" id="title" value="<?= $data["title"]; ?>">
            </div>
            <div class="input-wrap">
                <label for="category">Kategori</label>
                <input type="text" name="category" class="input" id="category" value="<?= $data["category"]; ?>">
            </div>
            <div class="input-wrap">
                <label for="post">Post</label>
                <textarea name="post" id="post" cols="30" rows="10"><?= $data["post"]; ?></textarea>
            </div>
            <div class="input-wrap">
                <img src="uploads/<?= $data["image"]; ?>" height="50">
                <label for="gambar">Gambar</label>
                <input type="file" name="gambar" class="input" id="gambar">
            </div>
            <button type="submit" name="edit" class="btn-submit w-inherit">Edit</button>
            <a href="blog.php" class="btn-secondary">Kembali</a>
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
                            $('.input-wrap img').attr('src', `uploads/${msg.success.image}`)
                            $('#gambar').val('');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>