CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contraseña VARCHAR(12) NOT NULL
);
SELECT * FROM usuarios;

ALTER TABLE usuarios
ADD COLUMN foto_perfil VARCHAR(255) DEFAULT 'default.jpg',
ADD COLUMN tema VARCHAR(50) DEFAULT 'tema1',
ADD COLUMN frase TEXT;
ALTER TABLE usuarios ADD COLUMN rol ENUM('promotor', 'administrador') NOT NULL DEFAULT 'promotor';
