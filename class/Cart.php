<?php

class Cart
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function displayCart($username)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['addcart'])) {
                $this->addToCart($_POST['cart_id']);
            } elseif (isset($_POST['removelike'])) {
                $this->removeFromCart($_POST['cart_id']);
            }
        }

        $sql = "CALL GetCartByUsername(?)";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Bląd sql: " . $this->conn->errno . $this->conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "
            <form method='POST' class='towary'>
                <input type='hidden' name='cart_id' value='{$row['cart_id']}'>
                <img src='{$row['img']}' alt='{$row['nazwa']}'>
                <h2>{$row['nazwa']}</h2>
                <h2>Cena: {$row['cena']}</h2>
                <h2>Ilość: {$row['ilosc']}</h2>
                <div class='przyciski'>
                    <button type='submit' name='plus'>+</button>
                    <button type='submit' name='minus'>-</button>
                </div>
            </form>
            ";
        }
        $stmt->close();
    }

    public function addToCart($cart_id)
    {
        $sql = "UPDATE koszyk SET ilosc = ilosc + 1 WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Bląd sql: " . $this->conn->errno . $this->conn->error);
        }
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $stmt->close();
    }

    public function removeFromCart($cart_id)
    {
        $sql = "SELECT ilosc FROM koszyk WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Bląd sql: " . $this->conn->errno . $this->conn->error);
        }
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['ilosc'] > 1) {
            $sql_update = "UPDATE koszyk SET ilosc = ilosc - 1 WHERE cart_id = ?";
            $stmt_update = $this->conn->prepare($sql_update);
            if ($stmt_update === false) {
                die("Bląd sql: " . $this->conn->errno . $this->conn->error);
            }
            $stmt_update->bind_param("s", $cart_id);
            $stmt_update->execute();
            $stmt_update->close();
        } else {
            $sql_delete = "DELETE FROM koszyk WHERE cart_id = ?";
            $stmt_delete = $this->conn->prepare($sql_delete);
            if ($stmt_delete === false) {
                die("Bląd sql: " . $this->conn->errno . $this->conn->error);
            }
            $stmt_delete->bind_param("s", $cart_id);
            $stmt_delete->execute();
            $stmt_delete->close();
        }
        $stmt->close();
    }
}
?>