<?php
include "./sesja.php";
include "./class/database.php";
include "./class/Login.php";

$db = new Database();
$conn = $db->getConnection();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new Login($conn);
    $login = $_POST["login"];
    $password = $_POST["password"];
    $rpassword = $_POST["rpassword"];
    $error = $user->register($login, $password, $rpassword);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["login"]) && isset($_GET["password"])) {
        $user = new Login($conn);
        $login = $_GET["login"];
        $password = $_GET["password"];
        $error = $user->login($login, $password);
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
    <link rel="stylesheet" href="public/styles/login.css">
</head>
<body>

<?php
    include "./components/header.php";
?>

<main>
    <div class="login_frame">
        <div class="login">
            <form method="post">
                <h3>Zarejestrować się</h3>
                <input type="text" name="login" placeholder="Login" minlength="3" maxlength="32" required>
                <input type="password" name="password" placeholder="Hasło" minlength="8" maxlength="64" required>
                <input type="password" name="rpassword" placeholder="Powtórz hasło" minlength="8" maxlength="64"
                       required>
                <input type="submit">
            </form>
            <form method="get">
                <h3>Zalogować się</h3>
                <input type="text" name="login" placeholder="Login" minlength="3" maxlength="32" required>
                <input type="password" name="password" placeholder="Hasło" minlength="8" maxlength="64" required>
                <input type="submit">
            </form>
        </div>
        <p class="error"><?php echo $error ?></p>
    </div>
</main>

<?php include("./components/navbar.php") ?>

</body>
</html>