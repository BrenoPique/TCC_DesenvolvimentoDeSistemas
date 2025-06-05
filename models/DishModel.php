<?php
require_once __DIR__ . '/../utils/Database.php';
require_once __DIR__ . '/../utils/Popup.php';
class DishModel
{
    private $db;
    private $id;
    private $name;
    private $description;
    private $price;
    private $image;
    private $partnerId;

    public function __construct()
    {
        $this->db = new Database();
        // $this->id = $_SESSION['dish_id'];
        // $this->name = $_SESSION['nome'];
        // $this->description = $_SESSION['descricao'];
        // $this->price = $_SESSION['preco'];
        // $this->image = $_SESSION['imagem'];
        // $this->partnerId = $_SESSION['id_parceiro'];
    }
    public function addDish($dishName, $dishDescription, $dishPrice)
    {
        try {
            $sql = "INSERT INTO prato (id_restaurante, nome, descricao, preco) 
                    VALUES (:id_restaurante, :nome, :descricao, :preco)";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                'id_restaurante' => $_SESSION['partner_id'],
                'nome' => $dishName,
                'descricao' => $dishDescription,
                'preco' => $dishPrice,
            ]);
            return $this->db->getConnection()->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateDish($dishId, $dishName, $dishDescription, $dishPrice)
    {
        try {
            $sql = "UPDATE prato SET nome = :nome, descricao = :descricao, preco = :preco 
                    WHERE id_prato = :id_prato AND id_restaurante = :id_restaurante";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                'nome' => $dishName,
                'descricao' => $dishDescription,
                'preco' => floatval($dishPrice), // Garante que o preço seja float
                'id_prato' => $dishId,
                'id_restaurante' => $_SESSION['partner_id']
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar prato: " . $e->getMessage());
            return false;
        }
    }

    public function deleteDish($dishId)
    {
        try {
            $this->db->getConnection()->beginTransaction();

            // Primeiro deleta todas as restrições do prato
            $sql = "DELETE FROM prato_restricao WHERE id_prato = :id_prato";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['id_prato' => $dishId]);

            // Depois deleta o prato
            $sql = "DELETE FROM prato WHERE id_prato = :id_prato AND id_restaurante = :id_restaurante";
            $stmt = $this->db->getConnection()->prepare($sql);
            $success = $stmt->execute([
                'id_prato' => $dishId,
                'id_restaurante' => $_SESSION['partner_id']
            ]);

            $this->db->getConnection()->commit();
            return $success;
        } catch (PDOException $e) {
            $this->db->getConnection()->rollBack();
            error_log("Erro ao deletar prato: " . $e->getMessage());
            return false;
        }
    }

    public function getDishes()
    {
        $sql = "SELECT * FROM prato WHERE id_restaurante = :id_restaurante";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id_restaurante' => $_SESSION['partner_id']]);
        return $stmt->fetchAll();
    }

    public function getDish($dishId)
    {
        $sql = "SELECT * FROM prato WHERE id_prato = :id_prato AND id_restaurante = :id_restaurante";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([
            'id_prato' => $dishId,
            'id_restaurante' => $_SESSION['partner_id']
        ]);
        return $stmt->fetch();
    }

    public function uploadImageDish($dishId, $dishName)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if ($_FILES["fileToUpload"]["size"] > 1048576) { // 1 MB em bytes
            Popup::showError("A imagem deve ter no máximo 1MB.");
            return false;
        }

        $targetDir = __DIR__ . "/../../assets/uploads/dish/" . $_SESSION['partner_id'] . "/"; // pasta do parceiro
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // cria a pasta se nao existir
        }
        $targetFile = $targetDir . basename($dishName . "_" . $dishId . ".jpg"); // caminho da imagem
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]); // verifica se a imagem eh valida
        if ($check !== false) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                return $targetFile; // retorna o caminho da imagem
            } else {
                Popup::showError("Erro ao mover a imagem.");
                return false; // erro ao mover a imagem
            }
        } else {
            Popup::showError("Erro ao verificar a imagem.");
            return false; // erro ao verificar a imagem
        }
    }
    public function getDishesCount($id)
    {
        $sql = "SELECT COUNT(*) FROM prato WHERE id_restaurante = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn();
    }

    public function getDishesByRestaurant($restaurantId)
    {
        $sql = "SELECT * FROM prato WHERE id_restaurante = :id_restaurante";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id_restaurante' => $restaurantId]);
        return $stmt->fetchAll();
    }
}
