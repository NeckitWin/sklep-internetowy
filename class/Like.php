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
                <input type='hidden' name='like_id' value='{$row['like_id']}'>
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

    public function addCart($user, $like_id)
    {
        $ilosc = 1;

        $sqlcheck = "SELECT * FROM `koszyk` WHERE user_id = (SELECT id FROM users WHERE username = ?) AND towar_id = (SELECT towar_id FROM `like` WHERE like_id = ?)";
        $stmtCheck = $this->conn->prepare($sqlcheck);
        $stmtCheck->bind_param("ss", $user, $like_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        if ($resultCheck->num_rows > 0) {
            return "Towar już jest w koszyku!";
        }
        $stmtCheck->close();

        $sqladd = "INSERT INTO `koszyk` (user_id, towar_id, ilosc) VALUES ((SELECT id FROM users WHERE username=?),(SELECT towar_id FROM `like` WHERE like_id = ?),?)";

        $stmt = $this->conn->prepare($sqladd);
        $stmt->bind_param("sss", $user, $like_id, $ilosc);
        $stmt->execute();
        if ($stmt->error) {
            return "Bląd sql: " . $this->conn->errno . $this->conn->error;
        }
        $stmt->close();

        $sqldeletefromlike = "DELETE FROM `like` WHERE like_id=?";

        $stmtDeleteLike = $this->conn->prepare($sqldeletefromlike);
        if ($stmtDeleteLike->error) {
            return "Bląd sql: " . $this->conn->errno . $this->conn->error;
        }
        $stmtDeleteLike->bind_param("s",$like_id);
        $stmtDeleteLike->execute();
        $stmtDeleteLike->close();

        $sqldeletefromshop = "UPDATE towary SET ilosc=ilosc-? WHERE towar_id = (SELECT towar_id FROM `like` WHERE like_id = ?)";

        $stmtDeleteShop = $this->conn->prepare($sqldeletefromshop);
        if ($stmtDeleteShop->error) {
            return "Bląd sql: " . $this->conn->errno . $this->conn->error;
        }
        $stmtDeleteShop->bind_param("ss", $ilosc, $like_id);
        $stmtDeleteShop->execute();
        $stmtDeleteShop->close();

        return "Towar został dodany do koszyka!";
    }

    public function removeLike($like_id)
    {
        $sql = "DELETE FROM `like` WHERE like_id=?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $like_id);
        $stmt->execute();
        $stmt->close();

        return "Polubione sotało usunęte";
    }
}