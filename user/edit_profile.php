<?php
session_start();

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require "../conn.php";
$user_id = $_SESSION["user_id"];
$member = query("SELECT * FROM members WHERE id = $user_id")[0];
$id = $_GET["id"];

if(!is_numeric($id)) exit("404");

if(isset($id))  {
    $nama = htmlspecialchars($_POST["nama"]);
    $mail = htmlspecialchars($_POST["email"]);
    $user = htmlspecialchars($_POST["user"]);
    $pass_enc = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $all_users = query("SELECT * FROM members WHERE id NOT IN (SELECT id FROM members WHERE id = $id)");
    foreach($all_users as $users) {
        if($mail == $users["email"]) {
            echo json_encode(["err" => "Email already used by another user"]); die;
        }

        if($user == $users["username"]) {
            echo json_encode(["err" => "Username already used by another user"]); die;
        }
    }

    if($_POST["password"] != $_POST["password2"]) {
        echo json_encode(["err" => "Password confirmation doesn't match"]); die;
    }

    if(password_verify($_POST["password"], $member["password"])) {
        echo json_encode(["err" => "New password can't be same as old password"]); die;
    }

    $pass = (!empty($_POST["password"])) ? $pass_enc : $member["password"];

    if(!empty($_FILES["pfp"]["name"])) {
        $file = $_FILES["pfp"]["name"];
        $explode = explode(".", $file);

        $fileSize = $_FILES["pfp"]["size"];
        $fileExt = strtolower(end($explode));
        $fileName = uniqid().".$fileExt";

        $allowedExt = ["jpg", "jpeg", "png"];

        if(!in_array($fileExt, $allowedExt)) { 
            echo json_encode(["err" => "Invalid file type"]); die;
        }
        
        if($fileSize > 3000000) {
            echo json_encode(["err" => "File size is too big"]); die;
        }
        
        move_uploaded_file($_FILES["pfp"]["tmp_name"], "images/$fileName");
        $image = $fileName;
        if($member["image"] != "nophoto.png") unlink("images/".$member["image"]);
    } else {
        $image = $member["image"];
    }

    $query = "UPDATE members SET
                            nama = '$nama',
                            username = '$user',
                            email = '$mail',
                            password = '$pass',
                            image = '$image'
                            WHERE id = $id
            ";
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) > 0) {
        echo json_encode(["success" => ["msg" => "Successfully edited", "img" => "$image"]]);
    } else {
        echo json_encode(["err" => "Edit failed"]);
    }
}
?>