<?php
include "./sesja.php";
include "./class/database.php";
include "./class/Shop.php";

$db = new Database();
$conn = $db->getConnection();
$shop = new Shop($conn);
$result='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["login"])) {
        $login = $_SESSION["login"];
        if (isset($_POST["towar_id"])) {
            $towar = $_POST["towar_id"];
            if (isset($_POST["addcart"])) {
               $result = $shop->addCart($login, $towar);
            }
            if (isset($_POST["like"])) {
               $result = $shop->addLike($login, $towar);
            }
        } else {
            $result = "Wyskoczył bląd. Spróbuj ponownie.";
        }
    } else {
        $result = "Zaloguj się";
    }
}

if ($result!='') {
    echo "<script>alert('{$result}');</script>";
}

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
    <link rel="stylesheet" href="public/styles/main.css">
</head>
<body>
<?php include("./components/header.php"); ?>

<main>
    <div class="towary">
        <?php
            echo $shop->displayProducts();
        ?>
    </div>
</main>
<?php include("./components/navbar.php"); ?>
</body>
</html>