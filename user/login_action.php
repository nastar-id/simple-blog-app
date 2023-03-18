<?php
require "../conn.php";

function login($username, $password) {
    global $conn;
    $user = mysqli_query($conn, "SELECT * FROM members WHERE username = '$username'");
    if(mysqli_num_rows($user) == 1) {
        $row = mysqli_fetch_assoc($user);
        if(password_verify($password, $row["password"])) {
            return true;
        }
    }

    return false;
}
?>