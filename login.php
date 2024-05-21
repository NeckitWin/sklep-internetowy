<?php
require "./sesja.php";
include "./database.php";
global $conn;
$error = "error";
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
    <link rel="stylesheet" href="public/styles/login.css">
</head>
<body>

<main>
    <div class="login">
        <form method="post">
            <h3>Zarejestrować się</h3>
            <input type="text" placeholder="Login" minlength="3" maxlength="32" required>
            <input type="password" name="password" placeholder="Hasło" minlength="8" maxlength="64" required>
            <input type="password" name="rpassword" placeholder="Powtórz hasło" minlength="8" maxlength="64" required>
            <input type="submit">
        </form>
        <form method="get">
            <h3>Zalogować się</h3>
            <input type="text" placeholder="Login" minlength="3" maxlength="32" required>
            <input type="password" name="password" placeholder="Hasło" minlength="8" maxlength="64" required>
            <input type="submit">
        </form>
        <p class="error"><?php $error ?></p>
    </div>
</main>

<?php include("./components/navbar.php") ?>

</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['login']) || !isset($_POST['password']) || !isset($_POST['rpassword'])) {
        $error = "Wypełnij wszystkie pola";
    }
    // Проверка, на sql инъекции
    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['login'])) {
        return  $error = "Login zawiera niedozwolone znaki";
    }

    $login = $_POST['login'];
    $password = $_POST['password'];
    $rpassword = $_POST['rpassword'];

    if ($password !== $rpassword) {
        $error = "Hasła nie są takie same";
    } else {
        $sql = "INSERT INTO users (login, password) VALUES ('$login', '$password')";
        if ($conn->query($sql)) {
            $error = "Zarejestrowano pomyślnie";
        } else {
            $error = "Błąd podczas rejestracji";
        }
    }
}
?>