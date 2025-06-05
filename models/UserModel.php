<?php
require_once __DIR__ . '/../utils/Database.php';

class UserModel
{
    private $db;
    private $id;
    private $name;
    private $email;
    private $password;
    private $premium;
    private $restrictions = [];
    private $favorites = [];
    private $reviews = [];

    public function __construct()
    {
        $this->db = new Database();
        if ($this->isLoggedIn()) {
            $this->id = $_SESSION['user_id'];
            $this->name = $_SESSION['nome'];
            $this->email = $_SESSION['email'];
            $this->premium = $_SESSION['premium'];
            // $this->restrictions = $this->searchRestrictions($this->id);
        }
    }

    

    public function insert($name, $email, $password)
    {
        $sql = "INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            'nome' => $name,
            'email' => $email,
            'senha' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }


    public function login($email, $password)
    {
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['senha'])) {
            return $user;
        }
        return false;
    }

    public function emailExists($email): bool
    {
        $stmt = $this->db->getConnection()->prepare("SELECT 1 FROM usuario WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->rowCount() > 0;
    }

    public function update(int $id, array $fields)
    {
        if (empty($fields)) {
            return false;
        }

        $setParts = [];
        $params = [];

        foreach ($fields as $column => $value) {
            if ($column === 'senha') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }

            $setParts[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        $sql = "UPDATE usuario SET " . implode(', ', $setParts) . " WHERE id_usuario = :id_usuario";
        $params[':id_usuario'] = $id;

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    public function searchByID($id)
    {
        $sql = "SELECT id_usuario, nome, email, senha, premium FROM usuario WHERE id_usuario = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function updatePremium($id, $status)
    {
        $sql = "UPDATE usuario SET premium = :status WHERE id_usuario = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
    }

    public function searchRestrictions($id)
    {
        $sql = "SELECT r.* FROM restricao as r 
                JOIN usuario_restricao as ur ON r.id_restricao = ur.id_restricao 
                WHERE ur.id_usuario = :id_usuario";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id_usuario' => $id]);
        return $stmt->fetchAll();
    }

    public function verifyPassword($userId, $password)
    {
        $sql = "SELECT senha FROM usuario WHERE id_usuario = :id_usuario";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id_usuario' => $userId]);
        $user = $stmt->fetch();
        return $user && password_verify($password, $user['senha']);
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function isPremium()
    {
        return isset($_SESSION['premium']) && $_SESSION['premium'] === true;
    }
}
