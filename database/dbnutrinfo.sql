create table if not exists restaurante
(
id_restaurante int primary key  auto_increment,
nome varchar (50) not null,
rua varchar (100),
numero varchar(6),
complemento varchar (50),
bairro varchar (80),
cidade varchar (50),
estado char(2),
cep char (8),
horario_funcionamento varchar (50),
email varchar (100) not null,
senha varchar (60) not null,
clique INT UNSIGNED NOT NULL DEFAULT 0,
latitude decimal(10,8) DEFAULT NULL,
longitude decimal(11,8) DEFAULT NULL,
media_avaliacao decimal(3,2) DEFAULT NULL
);

create table if not exists usuario
(
id_usuario int primary key auto_increment,
nome varchar (50) not null,
email varchar (100) not null unique,
senha varchar (60) not null,
premium boolean not null default false
);

create table if not exists restricao
(
id_restricao int primary key auto_increment,
restricao varchar (100)
);

create table if not exists prato 
(
id_prato int primary key auto_increment,
id_restaurante int not null,
nome varchar (50) not null,
descricao varchar (255) not null,
preco decimal (5,2),
FOREIGN KEY (id_restaurante) REFERENCES restaurante (id_restaurante)
);

create table if not exists favorito
(
id_favorito int primary	key auto_increment,
id_usuario int not null,
id_restaurante int not null,
FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
foreign key (id_restaurante) references restaurante (id_restaurante)
);

create table if not exists avaliacao
(
id_avaliacao int primary key auto_increment,
nota int not null,
comentario varchar(255),
data_avaliacao date,
id_usuario int not null,
id_restaurante int not null,
FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
foreign key (id_restaurante) references restaurante (id_restaurante)
);

create table if not exists usuario_restricao
(
id_usuario_restricao int primary key auto_increment,
id_restricao int not null,
id_usuario int not null,
FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
FOREIGN KEY (id_restricao) REFERENCES restricao (id_restricao)
);

create table if not exists prato_restricao
(
id_prato_restricao int primary key auto_increment,
id_restricao int not null,
id_prato int not null,
FOREIGN KEY (id_prato) REFERENCES prato (id_prato),
FOREIGN KEY (id_restricao) REFERENCES restricao (id_restricao)
);

-- stored procedures

DELIMITER $$

CREATE PROCEDURE sp_favoritar_restaurante(
    IN in_id_usuario INT,
    IN in_id_restaurante INT
)
BEGIN
    DECLARE favoritado INT;

    SELECT COUNT(*) INTO favoritado
    FROM favorito
    WHERE id_usuario = in_id_usuario
      AND id_restaurante = in_id_restaurante;

    IF favoritado = 0 THEN
        INSERT INTO favorito (id_usuario, id_restaurante)
        VALUES (in_id_usuario, in_id_restaurante);
    END IF;
END$$

CREATE PROCEDURE sp_avaliar_restaurante(
  IN in_id_usuario INT,
  IN in_id_restaurante INT,
  IN in_nota INT,
  IN in_comentario VARCHAR(255)
)
BEGIN
  DECLARE existe INT;

  SELECT COUNT(*) INTO existe
  FROM avaliacao
  WHERE id_usuario = in_id_usuario AND id_restaurante = in_id_restaurante;

  IF existe > 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Restaurante já avaliado por este usuário.';
  ELSE
    INSERT INTO avaliacao(nota, comentario, data_avaliacao, id_usuario, id_restaurante)
    VALUES (in_nota, in_comentario, CURDATE(), in_id_usuario, in_id_restaurante);
  END IF;
END$$

CREATE PROCEDURE sp_cadastrar_restaurante(
IN in_nome VARCHAR(50),
  IN in_email VARCHAR(100),
  IN in_senha VARCHAR(60)
)
BEGIN
DECLARE existe INT;

  SELECT COUNT(*) INTO existe FROM restaurante WHERE email = in_email;

  IF existe > 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Email já está em uso.';
  ELSE
    INSERT INTO restaurante(nome, email, senha) VALUES (in_nome, in_email, in_senha);
  END IF;
END$$

CREATE PROCEDURE sp_adicionar_cliques_restaurante(
  IN in_id_restaurante INT
)
BEGIN
  DECLARE existe INT;

  SELECT COUNT(*) INTO existe
  FROM restaurante
  WHERE id_restaurante = in_id_restaurante;

  IF existe = 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Restaurante não encontrado.';
  ELSE
    UPDATE restaurante
    SET clique = clique + 1
    WHERE id_restaurante = in_id_restaurante;
  END IF;
END$$

DELIMITER ;

-- triggers

DELIMITER $$

CREATE TRIGGER trg_deletar_restricoes_prato
BEFORE DELETE ON prato
FOR EACH ROW
BEGIN
  DELETE FROM prato_restricao
  WHERE id_prato = OLD.id_prato;
END$$

CREATE TRIGGER trg_atualizar_media_avaliacao
AFTER INSERT ON avaliacao
FOR EACH ROW
BEGIN
    UPDATE restaurante r
    SET r.media_avaliacao = (
        SELECT AVG(nota)
        FROM avaliacao
        WHERE id_restaurante = NEW.id_restaurante
    )
    WHERE r.id_restaurante = NEW.id_restaurante;
END$$

DELIMITER ;


-- inserção de restricoes

INSERT INTO restricao (restricao) VALUES
('Sem Glúten'),
('Sem Lactose'),
('Sem Amendoim'),
('Vegano'),
('Vegetariano'),
('Sem Açúcar');

-- inserção de restaurantes de exemplo (com nomes e ruas que parecem reais)

INSERT INTO restaurante 
(nome, rua, numero, complemento, bairro, cidade, estado, cep, horario_funcionamento, email, senha, latitude, longitude)
VALUES
('Cantina da Nonna', 'Rua Itália', '45', NULL, 'Bela Vista', 'São Paulo', 'SP', '01330000', '11:00 - 23:00', 'nonna@cantina.com', '$2y$10$DplX07B8D4WXc6/6iHViKuIWXr2rP8vJQu2f8sT5nBNIVjXQ6VFfm', -23.5505, -46.6333),
('Boteco do Zé', 'Av. Brigadeiro Faria Lima', '1510', NULL, 'Itaim Bibi', 'São Paulo', 'SP', '04538132', '16:00 - 02:00', 'contato@botecodoze.com.br', '$2y$10$DplX07B8D4WXc6/6iHViKuIWXr2rP8vJQu2f8sT5nBNIVjXQ6VFfm', -23.5585, -46.6646),
('Sushi Yamato', 'Rua Augusta', '500', '2º andar', 'Consolação', 'São Paulo', 'SP', '01305000', '12:00 - 22:00', 'reservas@sushiyamato.com', '$2y$10$DplX07B8D4WXc6/6iHViKuIWXr2rP8vJQu2f8sT5nBNIVjXQ6VFfm', -23.5515, -46.6847),
('Churrascaria Gaúcha', 'Av. das Nações Unidas', '12233', NULL, 'Brooklin', 'São Paulo', 'SP', '04578000', '12:00 - 23:30', 'contato@gaucha.com.br', '$2y$10$DplX07B8D4WXc6/6iHViKuIWXr2rP8vJQu2f8sT5nBNIVjXQ6VFfm', -23.5605, -46.6948),
('La Boulangerie', 'Rua Oscar Freire', '789', NULL, 'Jardins', 'São Paulo', 'SP', '01426001', '07:00 - 20:00', 'bonjour@laboulangerie.com.br', '$2y$10$DplX07B8D4WXc6/6iHViKuIWXr2rP8vJQu2f8sT5nBNIVjXQ6VFfm', -23.5625, -46.6689);

-- inserção de pratos de exemplo
INSERT INTO prato (id_restaurante, nome, descricao, preco) VALUES
-- Restaurante 1: Cantina da Nonna (italiano)
(1, 'Spaghetti à Bolonhesa', 'Spaghetti à Bolonhesa preparado com ingredientes frescos e selecionados.', 49.90),
(1, 'Lasanha Quatro Queijos', 'Lasanha Quatro Queijos preparada com ingredientes frescos e selecionados.', 55.00),
(1, 'Risoto de Funghi', 'Risoto de Funghi preparado com ingredientes frescos e selecionados.', 62.50),
(1, 'Penne ao Pesto', 'Penne ao Pesto preparado com ingredientes frescos e selecionados.', 45.00),
(1, 'Pizza Margherita', 'Pizza Margherita preparada com ingredientes frescos e selecionados.', 42.00),
(1, 'Gnocchi ao Sugo', 'Gnocchi ao Sugo preparado com ingredientes frescos e selecionados.', 48.00),
(1, 'Fettuccine Alfredo', 'Fettuccine Alfredo preparado com ingredientes frescos e selecionados.', 50.00),
(1, 'Bruschetta Tradicional', 'Bruschetta Tradicional preparada com ingredientes frescos e selecionados.', 29.00),
(1, 'Ravioli de Ricota', 'Ravioli de Ricota preparado com ingredientes frescos e selecionados.', 47.00),
(1, 'Tiramisù', 'Tiramisù preparado com ingredientes frescos e selecionados.', 32.00),

-- Restaurante 2: Boteco do Zé (brasileiro)
(2, 'Feijoada Completa', 'Feijoada Completa preparada com ingredientes frescos e selecionados.', 52.00),
(2, 'Moqueca Baiana', 'Moqueca Baiana preparada com ingredientes frescos e selecionados.', 61.00),
(2, 'Picanha na Chapa', 'Picanha na Chapa preparada com ingredientes frescos e selecionados.', 68.00),
(2, 'Arroz Tropeiro', 'Arroz Tropeiro preparada com ingredientes frescos e selecionados.', 39.00),
(2, 'Baião de Dois', 'Baião de Dois preparada com ingredientes frescos e selecionados.', 43.00),
(2, 'Frango com Quiabo', 'Frango com Quiabo preparado com ingredientes frescos e selecionados.', 45.00),
(2, 'Escondidinho de Carne Seca', 'Escondidinho de Carne Seca preparado com ingredientes frescos e selecionados.', 40.00),
(2, 'Farofa Especial', 'Farofa Especial preparada com ingredientes frescos e selecionados.', 25.00),
(2, 'Salpicão', 'Salpicão preparado com ingredientes frescos e selecionados.', 28.00),
(2, 'Doce de Leite com Queijo', 'Doce de Leite com Queijo preparado com ingredientes frescos e selecionados.', 22.00),

-- Restaurante 3: Sushi Yamato (japonês)
(3, 'Sushi Misto', 'Sushi Misto preparado com ingredientes frescos e selecionados.', 55.00),
(3, 'Sashimi de Salmão', 'Sashimi de Salmão preparado com ingredientes frescos e selecionados.', 60.00),
(3, 'Temaki de Atum', 'Temaki de Atum preparado com ingredientes frescos e selecionados.', 38.00),
(3, 'Uramaki Califórnia', 'Uramaki Califórnia preparado com ingredientes frescos e selecionados.', 36.00),
(3, 'Yakissoba', 'Yakissoba preparado com ingredientes frescos e selecionados.', 42.00),
(3, 'Guioza', 'Guioza preparado com ingredientes frescos e selecionados.', 30.00),
(3, 'Hot Roll', 'Hot Roll preparado com ingredientes frescos e selecionados.', 34.00),
(3, 'Sunomono', 'Sunomono preparado com ingredientes frescos e selecionados.', 18.00),
(3, 'Missoshiru', 'Missoshiru preparado com ingredientes frescos e selecionados.', 20.00),
(3, 'Tempurá de Camarão', 'Tempurá de Camarão preparado com ingredientes frescos e selecionados.', 48.00),

-- Restaurante 4: Churrascaria Gaúcha (brasileiro)
(4, 'Feijoada Completa', 'Feijoada Completa preparada com ingredientes frescos e selecionados.', 58.00),
(4, 'Moqueca Baiana', 'Moqueca Baiana preparada com ingredientes frescos e selecionados.', 64.00),
(4, 'Picanha na Chapa', 'Picanha na Chapa preparada com ingredientes frescos e selecionados.', 72.00),
(4, 'Arroz Tropeiro', 'Arroz Tropeiro preparada com ingredientes frescos e selecionados.', 38.00),
(4, 'Baião de Dois', 'Baião de Dois preparada com ingredientes frescos e selecionados.', 40.00),
(4, 'Frango com Quiabo', 'Frango com Quiabo preparado com ingredientes frescos e selecionados.', 44.00),
(4, 'Escondidinho de Carne Seca', 'Escondidinho de Carne Seca preparado com ingredientes frescos e selecionados.', 42.00),
(4, 'Farofa Especial', 'Farofa Especial preparada com ingredientes frescos e selecionados.', 28.00),
(4, 'Salpicão', 'Salpicão preparado com ingredientes frescos e selecionados.', 27.00),
(4, 'Doce de Leite com Queijo', 'Doce de Leite com Queijo preparado com ingredientes frescos e selecionados.', 24.00),

-- Restaurante 5: La Boulangerie (variado)
(5, 'Burrito de Frango', 'Burrito de Frango preparado com ingredientes frescos e selecionados.', 40.00),
(5, 'Curry de Legumes', 'Curry de Legumes preparado com ingredientes frescos e selecionados.', 35.00),
(5, 'Crepe de Presunto e Queijo', 'Crepe de Presunto e Queijo preparado com ingredientes frescos e selecionados.', 38.00),
(5, 'Kebab de Cordeiro', 'Kebab de Cordeiro preparado com ingredientes frescos e selecionados.', 50.00),
(5, 'Taco Al Pastor', 'Taco Al Pastor preparado com ingredientes frescos e selecionados.', 44.00),
(5, 'Ceviche Peruano', 'Ceviche Peruano preparado com ingredientes frescos e selecionados.', 42.00),
(5, 'Nhoque Recheado', 'Nhoque Recheado preparado com ingredientes frescos e selecionados.', 46.00),
(5, 'Espaguete Carbonara', 'Espaguete Carbonara preparado com ingredientes frescos e selecionados.', 48.00),
(5, 'Panqueca Americana', 'Panqueca Americana preparado com ingredientes frescos e selecionados.', 30.00),
(5, 'Pão de Alho', 'Pão de Alho preparado com ingredientes frescos e selecionados.', 15.00);

-- insert das restricoes
INSERT INTO prato_restricao (id_prato, id_restricao) VALUES
-- Pratos Italianos (1-10)
(1, 1), (1, 2), -- Spaghetti
(2, 2), -- Lasanha
(3, 2), (3, 5), -- Risoto Funghi
(4, 1), (4, 4), (4, 5), -- Penne Pesto
(5, 2), (5, 5), -- Pizza
(6, 1), -- Gnocchi
(7, 2), -- Fettuccine
(8, 1), (8, 4), (8, 5), -- Bruschetta
(9, 2), -- Ravioli
(10, 2), -- Tiramisù

-- Pratos Brasileiros (11-20)
(11, 1), -- Feijoada
(12, 1), (12, 2), -- Moqueca
(13, 1), (13, 2), -- Picanha
(14, 1), (14, 4), (14, 5), -- Arroz Tropeiro
(15, 1), (15, 2), -- Baião
(16, 1), (16, 2), -- Frango Quiabo
(17, 2), -- Escondidinho
(18, 1), (18, 4), (18, 5), -- Farofa
(19, 2), -- Salpicão
(20, 2), -- Doce de Leite

-- Pratos Japoneses (21-30)
(21, 1), (21, 2), -- Sushi
(22, 1), (22, 2), -- Sashimi
(23, 1), (23, 2), -- Temaki
(24, 1), (24, 2), -- Uramaki
(25, 1), -- Yakissoba
(26, 1), -- Guioza
(27, 1), -- Hot Roll
(28, 1), (28, 4), (28, 6), -- Sunomono
(29, 1), (29, 4), (29, 6), -- Missoshiru
(30, 1), -- Tempurá

-- Mais Pratos Brasileiros (31-40)
(31, 1), (31, 2), -- Feijoada
(32, 1), (32, 2), -- Moqueca
(33, 1), (33, 2), -- Picanha
(34, 1), (34, 4), (34, 2), -- Arroz
(35, 1), (35, 2), -- Baião
(36, 1), (36, 2), -- Frango
(37, 2), -- Escondidinho
(38, 1), (38, 4), (38, 2), -- Farofa
(39, 2), -- Salpicão
(40, 2), -- Doce

-- Pratos Variados (41-50)
(41, 1), (41, 2), -- Burrito
(42, 1), (42, 4), (42, 3), -- Curry Legumes
(43, 2), -- Crepe
(44, 1), (44, 2), -- Kebab
(45, 1), (45, 2), -- Taco
(46, 1), (46, 2), (46, 6), -- Ceviche
(47, 1), (47, 2), -- Nhoque
(48, 2), -- Espaguete
(49, 1), (49, 2), -- Panqueca
(50, 1), (50, 2); -- Pão de Alho

-- inserção de usuários de exemplo
