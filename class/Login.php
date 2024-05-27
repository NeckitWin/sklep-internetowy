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

        $sql = "SELECT * FROM `users` WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return "Błąd przy rejestracji: " . $this->conn->error;
        }

        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $stmt->close();
            return "Użytkownik o takim loginie już istnieje";
        }

        $stmt->close();

        $sql = "INSERT INTO `users` (username, password, role) VALUES (?, ?, 'Użytkownik')";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return "Błąd przy rejestracji: " . $this->conn->error;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bind_param("ss", $login, $hashedPassword);
        $stmt->execute();
        $stmt->close();

        return "Zarejestrowano pomyślnie";
    }
    public function login($login, $password)
    {
        $sql = "SELECT * FROM `users` WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return "Błąd przy logowaniu: " . $this->conn->error;
        }

        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['login'] = $row['username'];
                header("Location: index.php");
                exit();
            } else {
                return "Niepoprawne dane logowania";
            }
        } else {
            return "Niepoprawne dane logowania";
        }
    }
}

