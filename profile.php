<?php
include "./sesja.php";
include "./class/database.php";
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
} else {
    $db = new Database();
    $conn = $db->getConnection();
    $sql = "SELECT * FROM `users` WHERE username = '" . $_SESSION['login'] . "';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $role = $row['role'];
    $money = $row['money'];
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Database();
    $conn = $db->getConnection();
    $sql = "UPDATE `users` SET role = 'Sprzedawca' WHERE username = '" . $_SESSION['login'] . "';";
    $conn->query($sql);
    $conn->close();
    header("Location: profile.php");
    exit();
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
                    echo "<li>Pieniądze: " . $money . "</li>";
                    ?>
                </ul>
            </div>
            <div class="func">
                <h3>Towary użytkownika</h3>
                <form method="post">
                    <input type="submit" value="Zostać sprzedawcą">
                </form>
            </div>
        </div>
    </div>
</main>


<?php
include("./components/navbar.php");
?>

</body>
</html>