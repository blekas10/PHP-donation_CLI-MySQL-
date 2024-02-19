<?php
require_once 'dbConnect.php'; // Make sure this points to your database connection script

class Charity {
    public $id;
    public $name;
    public $representativeEmail;

    public function __construct($id, $name, $representativeEmail) {
        $this->id = $id;
        $this->name = $name;
        $this->representativeEmail = $representativeEmail;
    }

    public static function getAllCharities() {
        $conn = dbConnect();
        $stmt = $conn->query("SELECT * FROM charities");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $charities = [];
        foreach ($results as $row) {
            $charities[] = new Charity($row['id'], $row['name'], $row['representativeEmail']);
        }
        return $charities;
    }

    public static function addCharity($charity) {
        $conn = dbConnect();
        $stmt = $conn->prepare("INSERT INTO charities (name, representativeEmail) VALUES (:name, :email)");
        $stmt->execute([
            ':name' => $charity->name,
            ':email' => $charity->representativeEmail
        ]);
    }

    public static function viewCharities() {
        $charities = self::getAllCharities();
        foreach ($charities as $charity) {
            echo "ID: {$charity->id}, Name: {$charity->name}, Representative Email: {$charity->representativeEmail}\n";
        }
    }

    public static function editCharity($id, $newName, $newEmail) {
        $conn = dbConnect();
        $stmt = $conn->prepare("UPDATE charities SET name = :name, representativeEmail = :email WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':name' => $newName,
            ':email' => $newEmail
        ]);
    }

    public static function deleteCharity($id) {
        $conn = dbConnect();
        $stmt = $conn->prepare("DELETE FROM charities WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public static function idExists($id) {
        $conn = dbConnect();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM charities WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
    }
}
?>