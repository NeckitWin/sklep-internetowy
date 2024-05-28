<?php
class Shop {
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function displayProducts($searchTerm = null)
    {
        $sql = "SELECT towar_id, avatar, username, nazwa, cena, img, ilosc, opis FROM towary INNER JOIN users ON towary.wlasciciel_id = users.id";

        if ($searchTerm) {
            $sql .= " WHERE nazwa LIKE ?";
            $searchTerm = "%".$searchTerm."%";
        }

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Bląd sql: " . $this->conn->errno . $this->conn->error);
        }

        if ($searchTerm) {
            $stmt->bind_param("s", $searchTerm);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {

            $avatar='https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png';
            if (isset($row['avatar'])) $avatar=$row['avatar'];

            echo "<form method='POST'>
                <input type='hidden' name='towar_id' value='{$row['towar_id']}'>
                <img src={$row['img']} alt={$row['nazwa']}>
                <h4>{$row['nazwa']}</h4>
                <p>{$row['opis']}</p>
                <ul class='towar_info'>
                    <li><p>Cena:</p><p>{$row['cena']}zł</p></li>
                    <li><p>Ilość:</p><p>{$row['ilosc']}</p></li>
                    <li class='sprzedawca'><p>Sprzedawca:</p><p><img src='{$avatar}' alt='picture profile'>{$row['username']}</p></li>
                </ul>
                <div class='przyciski'>
                    <input type='submit' class='buy' value='dodaj do kosza' name='addcart'>
                    <button type='submit' class='like' name='like'></button>
                </div>
            </form>";
        }
    }

    public function addCart($user, $towar_id)
    {
        $ilosc = 1;

        $this->conn->begin_transaction();

        try {
            $sqlcheck = "SELECT * FROM `koszyk` WHERE (SELECT id FROM users WHERE username=?) AND towar_id = ?";
            $stmtCheck = $this->conn->prepare($sqlcheck);
            $stmtCheck->bind_param("ss", $user, $towar_id);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            if ($resultCheck->num_rows > 0) {
                throw new Exception("Towar już jest w koszyku!");
            }
            $stmtCheck->close();

            $sqladd = "INSERT INTO `koszyk` (user_id, towar_id, ilosc) VALUES ((SELECT id FROM users WHERE username=?),?,?)";
            $stmt = $this->conn->prepare($sqladd);
            $stmt->bind_param("sss", $user, $towar_id, $ilosc);
            $stmt->execute();
            if ($stmt->error) {
                throw new Exception("Bląd sql: " . $this->conn->errno . $this->conn->error);
            }
            $stmt->close();

            $this->conn->commit();
            return "Towar został dodany do koszyka!";
        } catch (Exception $e) {
            $this->conn->rollback();
            return $e->getMessage();
        }
    }

    public function addLike($username, $towar_id)
    {
        $sqlcheck = "SELECT * FROM `like` WHERE user_id = (SELECT id FROM users WHERE username = ?) AND towar_id = ?";
        $stmtCheck = $this->conn->prepare($sqlcheck);
        $stmtCheck->bind_param("ss", $username, $towar_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        if ($resultCheck->num_rows > 0) {
            return "Towar już czeka w polubionych!";
        }
        $stmtCheck->close();

        $sqladd = "INSERT INTO `like` (user_id, towar_id) VALUES ((SELECT id FROM users WHERE username = ?), ?)";
        $stmt = $this->conn->prepare($sqladd);
        $stmt->bind_param("ss", $username, $towar_id);
        $stmt->execute();
        if ($stmt->error) {
            return "Bląd: " . $this->conn->error;
        }
        $stmt->close();

        return "Towar został dodany do polubionych!";
    }
}
?>