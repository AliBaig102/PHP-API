<?php
namespace config;
use PDO;
use PDOException;
class User
{
    private string $host = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $dbname = "crud";
    private PDO $pdo;
    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAllUsers(): string
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if (count($data) > 0) {
            return $this->response("success", "All Users", $data);
        } else {
            return $this->response("error", "No Users Found");
        }
    }

    public function searchUser($search): string
    {
        $sql = "SELECT * FROM users WHERE name LIKE :search or email LIKE :search";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["search" => "%$search%"]);
        $data = $stmt->fetchAll();
        if (count($data) > 0) {
            return $this->response("success", "User Found", $data);
        } else {
            return $this->response("error", "User Not Found");
        }
    }

    public
    function getUserById($id): string
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $data = $stmt->fetch();
        if ($data) {
            return $this->response("success", "User Found", $data);
        } else {
            return $this->response("error", "User Not Found");
        }
    }

    public
    function createUser($name, $email, $password): string
    {
        $sql = "insert into users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam("name", $name);
        $stmt->bindParam("email", $email);
        $stmt->bindParam("password", $password);
        $stmt->execute();
        return $this->response("success", "User Created Successfully", $this->pdo->lastInsertId());
    }

    public function editUser($id, $name, $email, $password): string
    {
        $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam("name", $name);
        $stmt->bindParam("email", $email);
        $stmt->bindParam("password", $password);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        return $this->response("success", "User Updated Successfully");
    }

    public function deleteUser($id):string
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        return $this->response("success", "User Deleted Successfully");
    }


    private function response($status, $message, $data = null): string
    {

        return json_encode([
            "status" => $status,
            "message" => $message,
            "data" => $data
        ]);
    }

}

$user_obj = new User();