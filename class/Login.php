<?php
class Login
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function register($login, $password, $rpassword)
    {
        if ($password != $rpassword) {
            return "Hasła nie są takie same";
        }

        $sql = "INSERT INTO `users` (username, password, role) VALUES (?, ?, 'user')";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return "Błąd przy rejestracji: " . $this->conn->error;
        }

        $stmt->bind_param("ss", $login, $password);
        $stmt->execute();
        $stmt->close();

        return "Zarejestrowano pomyślnie";
    }

    public function login($login, $password)
    {
        $sql = "SELECT * FROM `users` WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return "Błąd przy logowaniu: " . $this->conn->error;
        }

        $stmt->bind_param("ss", $login, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['login'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            return "Niepoprawne dane logowania";
        }
    }
}

