<?php
class Shop {
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function displayProducts()
    {
        $sql = "SELECT towar_id, username, nazwa, cena, img, ilosc, opis FROM towary INNER JOIN users ON towary.wlasciciel_id = users.id";
        $result = $this->conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<form method='POST'>
                <input type='hidden' name='towar_id' value='{$row['towar_id']}'>
                <img src={$row['img']} alt={$row['nazwa']}>
                <h4>{$row['nazwa']}</h4>
                <p>{$row['opis']}</p>
                <ul class='towar_info'>
                    <li><p>Cena:</p><p>{$row['cena']}zł</p></li>
                    <li><p>Ilość:</p><p>{$row['ilosc']}</p></li>
                    <li><p>Sprzedawca:</p><p>{$row['username']}</p></li>
                </ul>
                <div class='przyciski'>
                    <input type='submit' class='buy' value='dodaj do kosza' name='addcart'>
                    <button type='submit' class='like' name='like'></button>
                </div>
            </form>";
        }
    }

    public function addLike($username, $towar_id)
    {
        $sql = "INSERT INTO `like` (user_id, towar_id) 
            VALUES ((SELECT id FROM users WHERE username = ?), ?) 
            ON DUPLICATE KEY UPDATE towar_id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return "Bląd: " . $this->conn->error;
        }
        $stmt->bind_param("sss", $username, $towar_id, $towar_id);
        $stmt->execute();
        $stmt->close();

        if ($this->conn->affected_rows > 0) {
            return "Towar został dodany do polubionych!";
        } else {
            return "Towar już czeka w polubionych!";
        }
    }

}
?>