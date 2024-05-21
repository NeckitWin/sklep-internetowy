<link rel="stylesheet" href="./components/styles/header.css">
<header class="title">
    <p>Mógłbyś mieć tu swoją reklamę</p>
    <p>
        <?php
        if (isset($_SESSION['login'])) {
            echo "Witaj, " . $_SESSION['login'];
        } else {
            echo "Witaj na stronie!";
        }
        ?>
    </p>
</header>