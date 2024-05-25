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

    public function addTowar($userid, $name, $opis ,$price, $ilosc, $image)
    {
        if ($price>0 && $ilosc>0) {
            $sql = "INSERT INTO towary (nazwa, opis, cena, wlasciciel_id, img, ilosc) VALUES (?,?,?,?,?,?)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssss", $name, $opis, $price, $userid, $ilosc, $image);
            $stmt->execute();
            $stmt->close();

            return "Towar został dodany";
        } else {
            return "Cena musi być większa od zera oraz ilość towaru też.";
        }
    }

}