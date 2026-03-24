<?php
class AdModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Lấy danh sách quảng cáo theo loại
     * @param string $type ('banner' hoặc 'promo_image')
     */
    public function getAdsByType($type) {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM advertisements WHERE type = ? ORDER BY id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm quảng cáo mới vào Database
     */
    public function addAd($data) {
        try {
            $conn = $this->db->getConnection();
            $sql = "INSERT INTO advertisements (title, image_url, type) VALUES (:title, :image_url, :type)";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([
                ':title' => $data['title'],
                ':image_url' => $data['image_url'],
                ':type' => $data['type']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Xóa quảng cáo theo ID
     */
    public function deleteAd($id) {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("DELETE FROM advertisements WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}