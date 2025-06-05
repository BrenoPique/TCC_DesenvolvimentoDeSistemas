<?php
require_once __DIR__ . '/../utils/Database.php';
require_once __DIR__ . '/../utils/GeocodingService.php';

class PartnerModel
{
    private $db;
    private $geocoding;
    private $id;
    private $name;
    private $email;
    private $password;
    private $logo;
    private $cliques = 0;
    private $cep;
    private $rua;
    private $numero;
    private $complemento;
    private $bairro;
    private $cidade;
    private $estado;
    private $horarioFuncionamento;


    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->geocoding = new GeocodingService();
        if ($this->isLoggedIn()) {
            $this->id = $_SESSION['partner_id'];
            // $this->name = $_SESSION['nome_p'];
            // $this->email = $_SESSION['email_p'];
        }
    }

    private function getAddressString($data)
    {
        return "{$data['rua']}, {$data['numero']}, {$data['bairro']}, {$data['cidade']}, {$data['estado']}";
    }

    public function insert($name, $email, $password, $cep, $rua, $numero, $complemento, $bairro, $cidade, $estado)
    {
        try {
            // Obter coordenadas do endereço
            $address = $this->getAddressString(compact('rua', 'numero', 'bairro', 'cidade', 'estado'));
            $coordinates = $this->geocoding->getCoordinates($address);

            $sql = "INSERT INTO restaurante (nome, email, senha, rua, numero, complemento, bairro, cidade, estado, cep, latitude, longitude) 
                    VALUES (:nome, :email, :senha, :rua, :numero, :complemento, :bairro, :cidade, :estado, :cep, :latitude, :longitude)";
            $stmt = $this->db->prepare($sql);

            // Adicionar as coordenadas aos dados
            $data = [
                'nome' => $name,
                'email' => $email,
                'senha' => password_hash($password, PASSWORD_DEFAULT),
                'rua' => $rua,
                'numero' => $numero,
                'complemento' => $complemento,
                'bairro' => $bairro,
                'cidade' => $cidade,
                'estado' => $estado,
                'cep' => $cep,
                'latitude' => $coordinates['latitude'],
                'longitude' => $coordinates['longitude']
            ];

            $stmt->execute($data);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar parceiro: " . $e->getMessage());
            return false;
        }
    }

    public function login($email, $password)
    {
        $sql = "SELECT * FROM restaurante WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $partner = $stmt->fetch();

        if ($partner && password_verify($password, $partner['senha'])) {
            return $partner;
        }
        return false;
    }

    public function searchByID($id)
    {
        $sql = "SELECT id_restaurante, nome, email, senha, rua, numero, complemento, bairro, cidade, estado, cep, horario_funcionamento FROM restaurante WHERE id_restaurante = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }


    public function isLoggedIn()
    {
        return isset($_SESSION['partner_id']);
    }

    public function logout()
    {
        session_destroy();
        header("Location: /");
        exit;
    }

    public function getViews($id)
    {
        $sql = "SELECT clique FROM restaurante WHERE id_restaurante = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn();
    }


    public function incrementViews($restaurantId)
    {
        $sql = "CALL sp_adicionar_cliques_restaurante(:id)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([":id" => $restaurantId]);
        return $result;
    }

    public function verifyPassword($id, $password)
    {
        $sql = "SELECT senha FROM restaurante WHERE id_restaurante = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $hashedPassword = $stmt->fetchColumn();

        return $hashedPassword && password_verify($password, $hashedPassword);
    }

    public function updatePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE restaurante SET senha = :senha WHERE id_restaurante = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'senha' => $hashedPassword,
            'id' => $id
        ]);
    }

    public function updateProfile($name, $email, $cep, $rua, $numero, $complemento, $bairro, $cidade, $estado, $horarioFuncionamento)
    {
        $sql = "UPDATE restaurante SET 
                nome = :nome, 
                email = :email, 
                rua = :rua, 
                numero = :numero, 
                complemento = :complemento,
                bairro = :bairro, 
                cidade = :cidade, 
                estado = :estado, 
                cep = :cep, 
                horario_funcionamento = :horario_funcionamento 
                WHERE id_restaurante = :id_restaurante";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nome' => $name,
            'email' => $email,
            'rua' => $rua,
            'numero' => $numero,
            'complemento' => $complemento,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'cep' => $cep,
            'horario_funcionamento' => $horarioFuncionamento,
            'id_restaurante' => $_SESSION['partner_id']
        ]);
    }

    public function getAllPartners()
    {
        $sql = "SELECT r.*, 
                COALESCE(AVG(a.nota), 0) as rating,
                COUNT(a.id_avaliacao) as total_avaliacoes
                FROM restaurante r
                LEFT JOIN avaliacao a ON r.id_restaurante = a.id_restaurante
                GROUP BY r.id_restaurante";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartnersByRestrictions($restrictions)
    {
        $placeholders = str_repeat('?,', count($restrictions) - 1) . '?';

        $sql = "SELECT DISTINCT r.*, 
                COALESCE(AVG(a.nota), 0) as rating,
                COUNT(DISTINCT a.id_avaliacao) as total_avaliacoes
                FROM restaurante r
                INNER JOIN prato p ON p.id_restaurante = r.id_restaurante
                INNER JOIN prato_restricao pr ON pr.id_prato = p.id_prato
                LEFT JOIN avaliacao a ON r.id_restaurante = a.id_restaurante
                WHERE pr.id_restricao IN ($placeholders)
                GROUP BY r.id_restaurante";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($restrictions);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPartnerById($id)
    {
        $sql = "SELECT r.*, 
                COALESCE(AVG(a.nota), 0) as rating,
                COUNT(a.id_avaliacao) as total_avaliacoes
                FROM restaurante r
                LEFT JOIN avaliacao a ON r.id_restaurante = a.id_restaurante
                WHERE r.id_restaurante = :id_restaurante
                GROUP BY r.id_restaurante";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_restaurante' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
