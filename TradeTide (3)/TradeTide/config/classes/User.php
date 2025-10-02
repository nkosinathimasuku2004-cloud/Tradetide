<?php
require_once '../config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $phone;
    public $area;
    public $bio;
    public $skills;
    public $profile_image;
    public $rating;
    public $total_trades;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Create user
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  (first_name, last_name, email, password, phone, area, bio, skills)
                  VALUES (:first_name, :last_name, :email, :password, :phone, :area, :bio, :skills)";

        $stmt = $this->conn->prepare($query);

        // Hash password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind parameters
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':area', $this->area);
        $stmt->bindParam(':bio', $this->bio);
        $stmt->bindParam(':skills', $this->skills);

        return $stmt->execute();
    }

    // Read user by email
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];
            $this->area = $row['area'];
            $this->bio = $row['bio'];
            $this->skills = $row['skills'];
            $this->profile_image = $row['profile_image'];
            $this->rating = $row['rating'];
            $this->total_trades = $row['total_trades'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Read user by ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->area = $row['area'];
            $this->bio = $row['bio'];
            $this->skills = $row['skills'];
            $this->profile_image = $row['profile_image'];
            $this->rating = $row['rating'];
            $this->total_trades = $row['total_trades'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Update user
    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET first_name = :first_name, last_name = :last_name,
                      phone = :phone, area = :area, bio = :bio, skills = :skills
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':area', $this->area);
        $stmt->bindParam(':bio', $this->bio);
        $stmt->bindParam(':skills', $this->skills);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Verify password
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }

    // Email exists check
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
?>
