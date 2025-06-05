<?php
require_once __DIR__ . '/../utils/Database.php';

class RestrictionModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getRestrictions()
    {
        $sql = "SELECT * FROM restricao ORDER BY restricao ASC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addRestrictionDish($restricaoId, $pratoId)
    {
        try {
            $sql = "INSERT INTO prato_restricao (id_prato, id_restricao) VALUES (:prato_id, :restricao_id)";
            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute([
                'prato_id' => $pratoId,
                'restricao_id' => $restricaoId
            ]);
        } catch (PDOException $e) {
            // Ignora erro de duplicidade
            if ($e->getCode() != 23000) {
                throw $e;
            }
            return false;
        }
    }

    public function getRestrictionsDish($pratoId)
    {
        $sql = "SELECT r.id_restricao 
                FROM restricao r
                INNER JOIN prato_restricao pr ON r.id_restricao = pr.id_restricao
                WHERE pr.id_prato = :prato_id";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['prato_id' => $pratoId]);

        // Retorna um array com apenas os IDs das restrições
        return array_map(function ($row) {
            return $row['id_restricao'];
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function setRestrictionsDish($pratoId, $restricoes)
    {
        try {
            $this->db->getConnection()->beginTransaction();

            // Remove todas as restrições existentes
            $sql = "DELETE FROM prato_restricao WHERE id_prato = :prato_id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['prato_id' => $pratoId]);

            // Adiciona as novas restrições
            if (!empty($restricoes)) {
                $sql = "INSERT INTO prato_restricao (id_prato, id_restricao) VALUES (:prato_id, :restricao_id)";
                $stmt = $this->db->getConnection()->prepare($sql);

                foreach ($restricoes as $restricaoId) {
                    $stmt->execute([
                        'prato_id' => $pratoId,
                        'restricao_id' => $restricaoId
                    ]);
                }
            }

            $this->db->getConnection()->commit();
            return true;
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }

    public function getRestrictionsRestaurant($restaurantId)
    {
        $sql = "SELECT DISTINCT r.restricao 
                FROM restricao r
                INNER JOIN prato_restricao pr ON r.id_restricao = pr.id_restricao
                INNER JOIN prato p ON pr.id_prato = p.id_prato
                WHERE p.id_restaurante = :restaurante_id
                ORDER BY r.restricao ASC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['restaurante_id' => $restaurantId]);

        // Retorna apenas um array com os nomes das restrições
        return array_map(function ($row) {
            return $row['restricao'];
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function deleteRestrictionsDish($pratoId)
    {
        $sql = "DELETE FROM prato_restricao WHERE id_prato = :prato_id";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute(['prato_id' => $pratoId]);
    }
}
