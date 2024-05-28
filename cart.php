<?php
include "./sesja.php";
include "./class/database.php";
include "./class/Cart.php";

$db = new Database();
$conn = $db->getConnection();
$cart = new Cart($conn);
$result='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cart_id']) && isset($_POST['removelike'])) {
        $cart->addToCart($_POST['cart_id']);
    }

    if (isset($_POST['cart_id']) && isset($_POST['addcart'])) {
        $cart->removeFromCart($_POST['cart_id']);
    }
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
    <link rel="stylesheet" href="public/styles/like.css">
</head>
<body>

<?php
include("./components/header.php");
?>

<main class="frame">
    <h1 class="beauh">Koszyk</h1>
    <div class="polubione">
        <?php
        if (isset($_SESSION['login'])) {
            echo $cart->displayCart($_SESSION['login']);
        } else {
            echo "Musisz się załogować, żeby korzystać z tej funkcji";
        }
        ?>
        <?php echo "<div>{$result}</div>" ?>
        <form method="POST">
            <input type="submit" value="Zamów" class="buy">
        </form>
    </div>
</main>

<?php
include("./components/navbar.php");
?>

</body>
</html>