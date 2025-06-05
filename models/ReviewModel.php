<?php
require_once __DIR__ . '/../utils/Database.php';
class ReviewModel
{
    private $db;
    private $userId;
    private $reviewText;
    private $rating;
    private $partnerId;
    private $reviewId;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function addReview($partnerId, $userId, $reviewText, $rating)
    {
        try {
            $sql = "CALL sp_avaliar_restaurante(:id_usuario, :id_restaurante, :nota, :comentario)";
            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute([
                'id_usuario' => $userId,
                'id_restaurante' => $partnerId,
                'nota' => $rating,
                'comentario' => $reviewText
            ]);
        } catch (PDOException $e) {
            // Se o erro for do stored procedure (usuário já avaliou)
            if ($e->getCode() == '45000') {
                return false;
            }
            throw $e;
        }
    }

    public function getReviews($partnerId)
    {
        $sql = "SELECT * FROM avaliacao WHERE id_restaurante = :id_restaurante";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id_restaurante' => $partnerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteReview($reviewId)
    {
        $sql = "DELETE FROM avaliacao WHERE id_avaliacao = :id_avaliacao";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute(['id_avaliacao' => $reviewId]);
    }

    public function hasUserReviewed($partnerId, $userId)
    {
        $sql = "SELECT COUNT(*) as count FROM avaliacao 
                WHERE id_restaurante = :id_restaurante 
                AND id_usuario = :id_usuario";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([
            'id_restaurante' => $partnerId,
            'id_usuario' => $userId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function getReviewsWithUserInfo($restaurantId)
    {
        $sql = "SELECT a.*, u.nome as nome_usuario, u.email
                FROM avaliacao a
                INNER JOIN usuario u ON a.id_usuario = u.id_usuario
                WHERE a.id_restaurante = :id_restaurante
                ORDER BY a.data_avaliacao DESC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id_restaurante' => $restaurantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRestaurantRating($restaurantId)
    {
        $sql = "SELECT 
                COALESCE(AVG(nota), 0) as media,
                COUNT(*) as total
                FROM avaliacao 
                WHERE id_restaurante = :id_restaurante";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id_restaurante' => $restaurantId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
