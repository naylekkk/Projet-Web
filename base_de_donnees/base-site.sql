CREATE DATABASE IF NOT EXISTS l2info;

ALTER DATABASE l2info
	CHARACTER SET utf8
	COLLATE utf8_general_ci;

USE l2info;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    descriptif TEXT,
    mots_cles TEXT,
    chemin_fichier VARCHAR(255) NOT NULL,
    date_enregistrement DATETIME DEFAULT CURRENT_TIMESTAMP,
    auteur_id INT NOT NULL,
    FOREIGN KEY (auteur_id) REFERENCES users(id) ON DELETE CASCADE
)CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS commentaires (
	commentaire TEXT NOT NULL,	
	auteur_id INT NOT NULL,
    	FOREIGN KEY (auteur_id) REFERENCES users(id) ON DELETE CASCADE,
	image_id INT NOT NULL,
	FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE,
	destinataire_id INT NOT NULL,
    	FOREIGN KEY (destinataire_id) REFERENCES users(id) ON DELETE CASCADE,
    	date_commentaire DATETIME DEFAULT CURRENT_TIMESTAMP
)CHARACTER SET utf8 COLLATE utf8_general_ci;

