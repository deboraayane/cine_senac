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
  trailer VARCHAR (100) NOT NULL,
  destaque BOOLEAN DEFAULT FALSE,
  posicao INT DEFAULT NULL	
);

INSERT INTO filme (titulo, classificacao_indicativa, genero, sub_genero, duracao, sinopse, poster, trailer, destaque, posicao) VALUES
('Duna: Parte Dois', '14', 'Ficção Científica', 'Aventura', 166, 'Paul Atreides se une a Chani e aos Fremen em sua busca por vingança contra os conspiradores que destruíram sua família. Enfrentando uma escolha entre o amor de sua vida e o destino do universo conhecido, ele deve evitar um futuro terrível que só ele pode prever.', 'duna2_poster.jpg', 'https://www.youtube.com/embed/Way9Dexny3w', TRUE, 1),

('Divertida Mente 2', 'L', 'Animação', 'Comédia', 96, 'Riley agora é uma adolescente e novas emoções chegam à sua mente. Alegria, Tristeza, Raiva, Medo e Nojinho se veem diante de um desafio maior do que nunca: lidar com a Ansiedade, a Inveja, o Tédio e a Vergonha.', 'divertidamente2_poster.jpg', 'https://youtu.be/yAZxx8t9zig?si=NhzCq7J_knM1hTxg', TRUE, 2),

('Deadpool & Wolverine', '16', 'Ação', 'Comédia', 127, 'Wolverine, se recuperando de seus ferimentos, cruza o caminho de um Deadpool tagarela. Eles se unem para enfrentar um inimigo em comum, em uma aventura cheia de humor e ação.', 'deadpool_wolverine_poster.jpg', 'https://youtu.be/dEbe0rS4Z2A?si=MD_AVJaFzLy0W_TC', TRUE, 3),

('O Senhor dos Anéis: A Sociedade do Anel', '12', 'Fantasia', 'Aventura', 178, 'Um jovem hobbit herda um anel mágico e perigoso, e deve embarcar em uma jornada épica para destruí-lo e salvar a Terra Média das forças do mal.', 'senhor_aneis_sociedade_anel_poster.jpg', 'https://youtu.be/oPwvcWPh3LM?si=jaQYooCm0NjNYcDE', FALSE, NULL),

('Interestelar', '10', 'Ficção Científica', 'Drama', 169, 'Em um futuro distópico, um grupo de exploradores espaciais viaja através de um buraco de minhoca em busca de um novo lar para a humanidade, enquanto a Terra se torna inabitável.', 'interestelar_poster.jpg', 'https://www.youtube.com/embed/zSWdZVtXT7E', FALSE, NULL),

('A Origem', '14', 'Ficção Científica', 'Ação', 148, 'Um ladrão que rouba segredos corporativos através do uso de tecnologia de compartilhamento de sonhos é encarregado da tarefa inversa: plantar uma ideia na mente de um CEO.', 'a_origem_poster.jpg', 'https://www.youtube.com/embed/YoHD9XEInc0', FALSE, NULL),

('Matrix', '14', 'Ficção Científica', 'Ação', 136, 'Um programador de computador descobre que a realidade é uma simulação criada por máquinas inteligentes, e se junta a um grupo de rebeldes para lutar contra elas.', 'matrix_poster.jpg', 'https://www.youtube.com/embed/vKQi3bBA1y8', FALSE, NULL);




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

INSERT INTO usuario (cpf, nome, telefone, email, senha_hash, tipo_usuario) VALUES ('123.456.789-10', 'admin', '(71) 99999-9999', 'admin@admin', '$2y$10$lTEXzLvAazmo6S905hSWz.jwg16q2Db9UTiTgOiLc7oTt7D7cO3BW', 'admin');
