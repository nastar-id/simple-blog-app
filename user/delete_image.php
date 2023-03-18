<?php
session_start();

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
require "../conn.php";

$id = $_GET["id"];
if(empty($id)) {
    header("Location: galeri.php");
    exit;
}
if(!is_numeric($id)) die("404");

$image = query("SELECT image FROM gallery WHERE id_user = '$user_id' AND id = $id")[0]["image"];
mysqli_query($conn, "DELETE FROM gallery WHERE id_user = '$user_id' AND id = $id");

if(unlink("uploads/gallery/$image") && mysqli_affected_rows($conn) > 0) {
    echo "
        <script>
            alert('Sukses Dihapus');
            window.location = 'galeri.php';
        </script>
        ";
} else {
    echo "<script>alert('Gagal Dihapus')</script>";
}
?>