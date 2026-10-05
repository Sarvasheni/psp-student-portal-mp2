<?php
require_once __DIR__ . '/../../config/database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function updateProfilePicture($userId, $fileName) {
        $stmt = $this->db->prepare("UPDATE students SET profile_picture = :profile_picture WHERE id = :id");
        return $stmt->execute([
            ':profile_picture' => $fileName,
            ':id' => $userId
        ]);
    }
}
?>