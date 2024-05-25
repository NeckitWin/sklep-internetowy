<?php
include "./sesja.php";
include "./class/database.php";
$db = new Database();
$conn = $db->getConnection();

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
            $sql = "SELECT username, nazwa, img, ilosc, opis FROM towary INNER JOIN users ON towary.wlasciciel_id = users.id";

            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
        ?>

            <?php
            echo "<form method='POST'>
                <img src={$row['img']} alt={$row['nazwa']}>
                <h4>{$row['nazwa']}</h4>
                <p>{$row['opis']}</p>
                <ul class='towar_info'>
                    <li><p>Cena:</p><p>{$row['nazwa']}</p></li>
                    <li><p>Ilość:</p><p>{$row['ilosc']}</p></li>
                    <li><p>Sprzedawca:</p><p>{$row['username']}</p></li>
                </ul>
                <input type='submit' value='kupić' name='buy'>
            </form>";
            ?>
    </div>
</main>

<?php include("./components/navbar.php"); ?>
</body>
</html>