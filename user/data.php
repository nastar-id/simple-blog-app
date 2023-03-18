<?php
session_start();

if(!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require "../conn.php";

if(isset($_GET["src"])) {
    $search = $_GET["src"];
    $user_id = $_SESSION["user_id"];

    $keyword = "SELECT * FROM posts 
                WHERE
                (title LIKE '%$search%' OR
                post LIKE '%$search%' OR
                category LIKE '%$search%') AND
                id_user = '$user_id'
                ORDER BY id DESC";
    $result = query($keyword);
}
?>

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
    $post = (strlen($data["post"]) > 70)
                            ? substr($data["post"], 0, 70)."..."
                            : $data["post"];
    ?>
    <tr>
        <td><?= $int; ?></td>
        <td style="width: 25%;"><?= $title_wrapped; ?></td>
        <td style="width: 55%;"><?= $post; ?></td>
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