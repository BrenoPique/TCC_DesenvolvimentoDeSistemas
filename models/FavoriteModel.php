<?php
require_once __DIR__ . '/../utils/Database.php';

class FavoriteModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function isFavorite($restaurantId)
    {
        if (!isset($_SESSION['user_id'])) return false;

        try {
            $sql = "SELECT COUNT(*) FROM favorito 
                    WHERE id_usuario = :id_usuario 
                    AND id_restaurante = :id_restaurante";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                'id_usuario' => $_SESSION['user_id'],
                'id_restaurante' => $restaurantId
            ]);

            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao verificar favorito: " . $e->getMessage());
            return false;
        }
    }

    public function addFavorite($restaurantId)
    {
        if (!isset($_SESSION['user_id'])) return false;

        try {
            // Primeiro verifica se já não é favorito
            if ($this->isFavorite($restaurantId)) return true;

            $sql = "INSERT INTO favorito (id_usuario, id_restaurante) 
                    VALUES (:id_usuario, :id_restaurante)";
            $stmt = $this->db->getConnection()->prepare($sql);

            return $stmt->execute([
                'id_usuario' => $_SESSION['user_id'],
                'id_restaurante' => $restaurantId
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao adicionar favorito: " . $e->getMessage());
            return false;
        }
    }

    public function removeFavorite($restaurantId)
    {
        if (!isset($_SESSION['user_id'])) return false;

        try {
            $sql = "DELETE FROM favorito 
                    WHERE id_usuario = :id_usuario 
                    AND id_restaurante = :id_restaurante";
            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute([
                'id_usuario' => $_SESSION['user_id'],
                'id_restaurante' => $restaurantId
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao remover favorito: " . $e->getMessage());
            return false;
        }
    }

    public function getFavoritesUser()
    {
        $sql = "SELECT * FROM favorito WHERE id_usuario = :id_usuario";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id_usuario' => $_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFavoriteCount($partnerId)
    {
        $sql = "SELECT COUNT(*) as count FROM favorito WHERE id_restaurante = :id_restaurante";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id_restaurante' => $partnerId]);
        return $stmt->fetchColumn();
    }

    public function getFavoritesPartner($partnerId)
    {
        $sql = "SELECT * FROM favorito WHERE id_restaurante = :id_restaurante";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id_restaurante' => $partnerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
