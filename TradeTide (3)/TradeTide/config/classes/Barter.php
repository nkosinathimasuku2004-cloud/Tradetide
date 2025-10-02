<?php
require_once '../config/database.php';

class Barter {
    private $conn;
    private $table = 'barters';

    public $id;
    public $user_id;
    public $title;
    public $description;
    public $category;
    public $type;
    public $value_range;
    public $location;
    public $images;
    public $status;
    public $views;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Create barter
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  (user_id, title, description, category, type, value_range, location, images)
                  VALUES (:user_id, :title, :description, :category, :type, :value_range, :location, :images)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':value_range', $this->value_range);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':images', $this->images);

        return $stmt->execute();
    }

    // Read all barters with pagination
    public function read($limit = 12, $offset = 0, $search = '', $category = '', $type = '') {
        $query = "SELECT b.*, u.first_name, u.last_name, u.area as user_area, u.rating, u.profile_image
                  FROM " . $this->table . " b
                  LEFT JOIN users u ON b.user_id = u.id
                  WHERE b.status = 'active'";

        $params = array();

        if (!empty($search)) {
            $query .= " AND (b.title LIKE :search OR b.description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if (!empty($category)) {
            $query .= " AND b.category = :category";
            $params[':category'] = $category;
        }

        if (!empty($type)) {
            $query .= " AND b.type = :type";
            $params[':type'] = $type;
        }

        $query .= " ORDER BY b.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }

        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    // Read user's barters
    public function readByUser($user_id) {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE user_id = :user_id
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt;
    }

    // Read single barter
    public function readOne($id) {
        $query = "SELECT b.*, u.first_name, u.last_name, u.area as user_area, u.rating, u.profile_image, u.phone
                  FROM " . $this->table . " b
                  LEFT JOIN users u ON b.user_id = u.id
                  WHERE b.id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->category = $row['category'];
            $this->type = $row['type'];
            $this->value_range = $row['value_range'];
            $this->location = $row['location'];
            $this->images = $row['images'];
            $this->status = $row['status'];
            $this->views = $row['views'];
            $this->created_at = $row['created_at'];
            return $row;
        }
        return false;
    }

    // Update barter
    public function update() {
        $query = "UPDATE " . $this->table . "
                  SET title = :title, description = :description, category = :category,
                      type = :type, value_range = :value_range, location = :location,
                      images = :images
                  WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':value_range', $this->value_range);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':images', $this->images);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);

        return $stmt->execute();
    }

    // Delete barter
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);

        return $stmt->execute();
    }

    // Increment views
    public function incrementViews($id) {
        $query = "UPDATE " . $this->table . " SET views = views + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Get categories
    public function getCategories() {
        $query = "SELECT DISTINCT category FROM " . $this->table . " WHERE status = 'active' ORDER BY category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
