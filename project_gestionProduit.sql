CREATE DATABASE IF NOT EXISTS gestionProduit;
USE gestionProduit;

-- CNX
CREATE TABLE IF NOT EXISTS cnx(
    email VARCHAR(20) PRIMARY KEY,
    motPasse VARCHAR(100) NOT NULL
) ENGINE = InnoDB;

-- CATEGORIE
CREATE TABLE IF NOT EXISTS Categorie (
    idCat    INT PRIMARY KEY AUTO_INCREMENT,
    nomCat    VARCHAR(50) NOT NULL
) ENGINE = InnoDB;

-- PRODUIT
CREATE TABLE IF NOT EXISTS Produit (
    idPro    INT PRIMARY KEY AUTO_INCREMENT,
    nom    VARCHAR(50) NOT NULL,
    photo VARCHAR(255),
    description    VARCHAR(200),
    prix    DECIMAL(10,2) NOT NULL,
    quantite    INT NOT NULL,
	idCat		INT NOT NULL,
    FOREIGN KEY (idCat) REFERENCES Categorie(idCat)
) ENGINE = InnoDB;

INSERT INTO cnx (email, motPasse)
VALUES ('safaedouae@gmail.com', '12345');

INSERT INTO Categorie (nomCat)
VALUES
('Informatique'),
('Gaming'),
('Accessoires'),
('Téléphones');


