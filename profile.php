<?php
include "./sesja.php";
include "./class/database.php";
include "./class/Profile.php";
$error = "";


$db = new Database();
$conn = $db->getConnection();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Profile = new Profile($conn);

    if (isset($_POST["seller"])) {
        $error = $Profile->setSeller($_SESSION['login']);
    }

    if (isset($_POST["money"]) && isset($_POST["addMoney"])) {
        $error = $Profile->addMoney($_SESSION['login'], $_POST["money"]);
    }
}
$sql = "SELECT * FROM `users` WHERE username = '" . $_SESSION['login'] . "';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$userid = $row['id'];
$username = $row['username'];
$role = $row['role'];
$money = $row['money'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["towar"]) && isset($_POST["opis"]) && isset($_POST["cena"]) && isset($_POST["image"]) && isset($_POST["ilosc"])) {
        $error = $Profile->addTowar($userid, $_POST["towar"], $_POST["opis"], $_POST["cena"], $_POST["image"], $_POST["ilosc"]);
    }
}
$conn->close();
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
    <link rel="stylesheet" href="public/styles/profile.css">
</head>
<body>

<?php
include("./components/header.php");
?>

<main>
    <div class="profile_frame">
        <div class="profile">
            <div class="info">
                <img src="./public/avatar/example.png" alt="profile picture" class="avatar">
                <ul>
                    <?php
                    echo "<li>Login: " . $username . "</li>";
                    echo "<li>Rola: " . $role . "</li>";
                    echo "<li>Pieniądze: " . $money . "zł</li>";
                    echo "<li class='error'>" . $error . "</li>"
                    ?>
                </ul>
            </div>
            <div class="func">
                <h3>Profil użytkownika</h3>
                <form method="post">
                    <input type="number" name="money" placeholder="Kwota" max="100000000">
                    <input type="submit" name="addMoney" value="Doładuj konto">
                    <?php
                    if ($role != "Sprzedawca" && $role != "Admin") {
                        echo '<input type = "submit" name = "seller" value = "Zostań sprzedawcą" ></br>';
                    }
                    ?>
                </form>
                <?php
                if ($role != "Użytkownik") {
                    echo "<h4>Dodaj towar do sklepu</h4>";
                    echo '
                        <form method="POST">
                            <input type="text" minlength="3" maxlength="30" placeholder="nazwa" name="towar" required><br>
                            <input type="text" minlength="5" maxlength="255" placeholder="opis" name="opis" required><br>
                            <input type="number" placeholder="koszt" max="10000000" name="cena" required><br>
                            <input type="number" placeholder="ilość" max="5000" name="ilosc" required><br>
                            <input type="text" placeholder="link do obrazka" name="image" required>
                            <input type="submit" name="addTowar" value="Dodaj towar">
                        </form>
                    ';
                }
                ?>
            </div>
        </div>
    </div>
</main>


<?php
include("./components/navbar.php");
?>

</body>
</html>