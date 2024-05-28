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
        $sql = "CALL GetFavorite(?)";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Bląd sql: " . $this->conn->errno . $this->conn->error);
        }
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "
            <form method='POST' class='towary'>
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

        $this->conn->begin_transaction();

        try {
            $sqlcheck = "SELECT * FROM `koszyk` WHERE user_id = (SELECT id FROM users WHERE username = ?) AND towar_id = (SELECT towar_id FROM `like` WHERE like_id = ?)";
            $stmtCheck = $this->conn->prepare($sqlcheck);
            $stmtCheck->bind_param("ss", $user, $like_id);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            if ($resultCheck->num_rows > 0) {
                throw new Exception("Towar już jest w koszyku!");
            }
            $stmtCheck->close();

            $sqladd = "INSERT INTO `koszyk` (user_id, towar_id, ilosc) VALUES ((SELECT id FROM users WHERE username=?),(SELECT towar_id FROM `like` WHERE like_id = ?),?)";
            $stmt = $this->conn->prepare($sqladd);
            $stmt->bind_param("sss", $user, $like_id, $ilosc);
            $stmt->execute();
            if ($stmt->error) {
                throw new Exception("Bląd sql: " . $this->conn->errno . $this->conn->error);
            }
            $stmt->close();

            $sqldeletefromlike = "DELETE FROM `like` WHERE like_id=?";
            $stmtDeleteLike = $this->conn->prepare($sqldeletefromlike);
            $stmtDeleteLike->bind_param("s",$like_id);
            $stmtDeleteLike->execute();
            if ($stmtDeleteLike->error) {
                throw new Exception("Bląd sql: " . $this->conn->errno . $this->conn->error);
            }
            $stmtDeleteLike->close();

            $sqldeletefromshop = "UPDATE towary SET ilosc=ilosc-? WHERE towar_id = (SELECT towar_id FROM `like` WHERE like_id = ?)";
            $stmtDeleteShop = $this->conn->prepare($sqldeletefromshop);
            $stmtDeleteShop->bind_param("ss", $ilosc, $like_id);
            $stmtDeleteShop->execute();
            if ($stmtDeleteShop->error) {
                throw new Exception("Bląd sql: " . $this->conn->errno . $this->conn->error);
            }
            $stmtDeleteShop->close();

            $this->conn->commit();
            return "Towar został dodany do koszyka!";
        } catch (Exception $e) {
            $this->conn->rollback();
            return $e->getMessage();
        }
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