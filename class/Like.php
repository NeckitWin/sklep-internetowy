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
        $sql = "SELECT towary.towar_id, like_id, nazwa, cena, ilosc, img FROM `like` 
INNER JOIN users ON like.user_id=users.id 
INNER JOIN towary ON like.towar_id=towary.towar_id
WHERE user_id = (SELECT id FROM users WHERE username = ?)";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Bląd sql: " . $this->conn->errno . $this->conn->error);
        }
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "
            <form method='POST'>
                <input type='hidden' name='towar_id' value='{$row['towar_id']}'>
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
        $stmt->close();
    }

    public function addCart($user, $towar_id)
    {

    }
}