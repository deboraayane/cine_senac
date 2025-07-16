CREATE DATABASE IF NOT EXISTS cine_senac;
USE cine_senac;


CREATE TABLE usuario (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  cpf VARCHAR(45) NOT NULL UNIQUE,
  nome VARCHAR(100) NOT NULL,
  telefone VARCHAR (15),
  email VARCHAR(100) UNIQUE,
  senha_hash VARCHAR(255),
  tipo_usuario ENUM ('cliente','admin') DEFAULT 'cliente'
);

CREATE TABLE acesso (
  id_acesso INT AUTO_INCREMENT PRIMARY KEY,
  hora_inicio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  hora_fim DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  data_acesso DATE NOT NULL
);

CREATE TABLE filme (
  id_filme INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(100) NOT NULL,
  classificacao_indicativa VARCHAR(5) NOT NULL,
  genero VARCHAR (20) NOT NULL,
  sub_genero VARCHAR (20) NOT NULL,
  duracao INT NOT NULL,
  sinopse VARCHAR (500) NOT NULL,
  poster VARCHAR (100) NOT NULL,
  trailer VARCHAR (100) NOT NULL
);

CREATE TABLE sala (
  id_sala INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(10) NOT NULL
);

CREATE TABLE sessao (
  id_sessao INT AUTO_INCREMENT PRIMARY KEY,
  id_filme INT NOT NULL,
  id_sala INT NOT NULL,
  animacao ENUM('3d','2d') DEFAULT '2d',
  horario DATETIME NOT NULL,
  FOREIGN KEY (id_filme) REFERENCES filme(id_filme),
  FOREIGN KEY (id_sala) REFERENCES sala(id_sala)
);

CREATE TABLE assento (
  id_assento INT AUTO_INCREMENT PRIMARY KEY,
  numero VARCHAR(5) NOT NULL,
  id_sala INT NOT NULL,
  FOREIGN KEY (id_sala) REFERENCES sala(id_sala)
);

CREATE TABLE ingresso (
  id_ingresso INT AUTO_INCREMENT PRIMARY KEY,
  id_sessao INT NOT NULL,
  id_assento INT NOT NULL,
  valor DECIMAL(6,2) CHECK (valor >= 0),
  tipo ENUM('inteira', 'meia') DEFAULT 'meia',
  estado ENUM('disponivel', 'vendido', 'reservado') DEFAULT 'disponivel',
  FOREIGN KEY (id_sessao) REFERENCES sessao(id_sessao) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_assento) REFERENCES assento(id_assento) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE venda (
  id_venda INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  data_venda DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  valor_total DECIMAL(8,2) NOT NULL CHECK (valor_total >= 0),
  forma_pagamento ENUM('cartao', 'boleto', 'dinheiro', 'pix') DEFAULT 'cartao',
  id_filme INT 	NOT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_filme) REFERENCES filme(id_filme)
);

CREATE TABLE venda_ingresso (
  id_venda INT NOT NULL,
  id_ingresso INT NOT NULL,
  PRIMARY KEY (id_venda, id_ingresso),
  FOREIGN KEY (id_venda) REFERENCES venda(id_venda) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_ingresso) REFERENCES ingresso(id_ingresso) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE usuario_acesso (
  id_usuario INT,
  id_acesso INT,
  PRIMARY KEY (id_usuario, id_acesso),
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_acesso) REFERENCES acesso(id_acesso) ON DELETE CASCADE ON UPDATE CASCADE
);


-----------------------------------
INSERT INTO sala (nome) VALUES ('Sala 1');

INSERT INTO assento (numero, id_sala) VALUES ('A1', 1), ('A2', 1);

INSERT INTO filme (titulo, duracao_min) VALUES ('Matrix', 120);

INSERT INTO sessao (id_filme, id_sala, horario) VALUES (1, 1, '2025-06-15 20:00:00');

INSERT INTO ingresso (id_sessao, id_assento, valor, tipo) 
VALUES (1, 1, 25.00, 'inteira'),
       (1, 2, 12.50, 'meia');











-------------------------------------

SELECT u.nome, i.tipo, i.valor, s.horario 
FROM usuario u
JOIN venda v ON u.id_usuario = v.id_usuario
JOIN venda_ingresso vi ON v.id_venda = vi.id_venda
JOIN ingresso i ON i.id_ingresso = vi.id_ingresso
JOIN sessao s ON s.id_sessao = i.id_sessao;

SELECT s.id_sessao, f.titulo, sa.nome, s.horario
FROM sessao s
JOIN filme f ON f.id_filme = s.id_filme
JOIN sala sa ON sa.id_sala = s.id_sala;

SELECT u.nome, a.hora_inicio, a.hora_fim
FROM usuario u
JOIN usuario_acesso ua ON u.id_usuario = ua.id_usuario
JOIN acesso a ON a.id_acesso = ua.id_acesso;

------------------------------

DELIMITER //

CREATE TRIGGER antes_venda_ingresso
BEFORE INSERT ON venda_ingresso
FOR EACH ROW
BEGIN
  DECLARE ingresso_status ENUM('disponivel', 'vendido', 'reservado');
  
  SELECT status INTO ingresso_status FROM ingresso WHERE id_ingresso = NEW.id_ingresso;
  
  IF ingresso_status != 'disponivel' THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ingresso não está disponível para venda';
  END IF;
END;
//

CREATE TRIGGER depois_venda_ingresso
AFTER INSERT ON venda_ingresso
FOR EACH ROW
BEGIN
  UPDATE ingresso
  SET status = 'vendido'
  WHERE id_ingresso = NEW.id_ingresso;
END;
//

CREATE TRIGGER dpsdelete_venda_ingresso
AFTER DELETE ON venda_ingresso
FOR EACH ROW
BEGIN
  UPDATE ingresso
  SET status = 'disponivel'
  WHERE id_ingresso = OLD.id_ingresso;
END;
//

DELIMITER ;
