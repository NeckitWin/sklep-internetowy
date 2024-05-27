<?php
include "./sesja.php";
include "./class/database.php";
include "./class/Like.php";

$db = new Database();
$conn = $db->getConnection();
$like = new Like($conn);
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sklep internetowy</title>
    <link rel="stylesheet" href="public/styles/style.css">
    <link rel="stylesheet" href="public/styles/like.css">
</head>
<body>

<?php
include("./components/header.php");
?>

<main>
    <div class="frame">
        <h1 class="beauh">Towary polubione</h1>
        <div class="polubione">
            <?php
                if (isset($_SESSION['login'])) {
                    echo $like->displayFavoriteLIKE($_SESSION['login']);
                } else {
                    echo "Musisz się załogować, żeby korzystać z tej funkcji";
                }
            ?>
        </div>
    </div>
</main>

<?php
include("./components/navbar.php");
?>

</body>
</html>