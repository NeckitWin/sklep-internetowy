<?php

class Like
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function displayFavoriteLIKE($user)
    {
        $sql = "SELECT like_id, nazwa, cena, ilosc, img FROM `like` 
INNER JOIN users ON like.user_id=users.id 
INNER JOIN towary ON like.towar_id=towary.towar_id
WHERE user_id = (SELECT id FROM users WHERE username = '{$user}')";

        $result = $this->conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "
            <form method='POST'>
                <img src='{$row['img']}' alt='{$row['nazwa']}'>
                <h2>{$row['nazwa']}</h2>
                <h2>Cena: {$row['cena']}</h2>
                <h2>Ilość: {$row['ilosc']}</h2>
                <div class='przyciski'>
                    <button type='submit' name='removelike'>Usuń z polubionych</button>
                    <button type='submit' name='addcart'>Dodaj do kosza</button>
                </div>
            </form>
            ";
        }
    }

    public function addCart($user, $towar_id)
    {

    }
}