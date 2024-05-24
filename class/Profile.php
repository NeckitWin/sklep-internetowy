<?php

class Profile
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function setSeller($username)
    {
        $sql = "UPDATE users SET users.role='Sprzedawca' WHERE users.username=?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();

        return "Użytkownik został sprzedawcą";
    }

    public function addMoney($username, $amount)
    {
        if ($amount > 0) {
            $sql = "UPDATE users SET users.money=users.money+? WHERE users.username=?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $amount, $username);
            $stmt->execute();
            $stmt->close();

            return "Kwota " . $amount . "zł została dodana";
        } else {
            return "Dodana może być tylko kwota dodatnia";
        }
    }

}