CREATE DATABASE saicar;

USE saicar;

/* utilizador */
CREATE TABLE users (
    id SMALLINT NOT NULL AUTO_INCREMENT,
    username VARCHAR(32) NOT NULL unique,
    password_hash BINARY(144) NOT NULL,
    permissions TINYINT NOT NULL,
    PRIMARY KEY(id)
);

/* sensores e atuadores */
CREATE TABLE dispositivos (
    id TINYINT NOT NULL AUTO_INCREMENT,
    descricao VARCHAR(32) NOT NULL unique,
    valor VARCHAR(16) NOT NULL,
    hora VARCHAR(64) NOT NULL,
    PRIMARY KEY(id)
);

/* sensores e atuadores */
CREATE TABLE logs (
    id TINYINT NOT NULL AUTO_INCREMENT,
    id_disp TINYINT NOT NULL,
    valor VARCHAR(16) NOT NULL,
    hora VARCHAR(64) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(id_disp) REFERENCES dispositivos(id)
);



DELIMITER //

/*
Efetuar o login de um utilizador
Output:
	code:
		0 - login com sucesso
		1 - password incorreta
		2 - não existe esse utilizador
*/
CREATE PROCEDURE LoginUser(pUsername VARCHAR(32), pPassword VARCHAR(50), OUT code TINYINT)
BEGIN
    SET code=2;

    IF (SELECT COUNT(*) FROM users WHERE username=pUsername) = 1 THEN
        SET code=1;
        IF (SELECT password_hash FROM users WHERE username=pUsername) = SHA2(pPassword,512) THEN
            SET code=0;
        END IF;
    END IF;
END//

/*
Registar um utilizador
Output:
	code:
		0 - registo com sucesso
		1 - esse utilizador já existe
*/
CREATE PROCEDURE RegisterUser(pUsername VARCHAR(32), pPassword VARCHAR(50), OUT code TINYINT)
BEGIN
    SET code=1;
	IF (SELECT COUNT(*) FROM users WHERE username=pUsername) = 0 THEN
		INSERT INTO users (username, password_hash, permissions)
			VALUES(pUsername, SHA2(pPassword,512), 1);
        SET code=0;
    END IF;
	
END//