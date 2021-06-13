CREATE DATABASE saicar;

USE saicar;

/* utilizador */
CREATE TABLE users (
    id SMALLINT NOT NULL AUTO_INCREMENT,
    username VARCHAR(32) NOT NULL unique,
    password_hash VARCHAR(128) NOT NULL,
    perms TINYINT NOT NULL,
    PRIMARY KEY(id)
);

/* sensores e atuadores */
CREATE TABLE dispositivos (
    id TINYINT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(32) NOT NULL unique,
    descricao VARCHAR(64) NOT NULL,
    valor VARCHAR(16) NOT NULL,
    hora VARCHAR(64) NOT NULL,
    e_atuador BIT NOT NULL,
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
CREATE PROCEDURE RegisterUser(pUsername VARCHAR(32), pPassword VARCHAR(50), pPerms TINYINT, OUT code TINYINT)
BEGIN
    SET code=1;
	IF (SELECT COUNT(*) FROM users WHERE username=pUsername) = 0 THEN
		INSERT INTO users (username, password_hash, perms)
			VALUES(pUsername, SHA2(pPassword,512), pPerms);
        SET code=0;
    END IF;
	
END//



DELIMITER ;

CALL RegisterUser("goncalo", "paulino123", 3, @code);
CALL RegisterUser("rafael", "tavares321", 3, @code);
CALL RegisterUser("vigilante", "vigilante", 2, @code);
CALL RegisterUser("user", "user", 1, @code);

INSERT INTO `dispositivos`VALUES (NULL, 'aquecimento', 'Aquecimento', 'OFF', '2021/06/11 19:12', 1);
INSERT INTO `dispositivos`VALUES (NULL, 'ar_condicionado', 'Ar Condicionado', 'BAIXO', '2021/06/11 19:12', 1);
INSERT INTO `dispositivos` VALUES (NULL, 'aspersor', 'Aquecimento', 'OFF', '2021/06/11 19:12', 1);
INSERT INTO `dispositivos` VALUES (NULL, 'co', 'Monóxido de Carbono', '0', '2021/06/11 19:12', 0);
INSERT INTO `dispositivos` VALUES (NULL, 'co2', 'Poluição (CO<sub>2</sub>)', '360', '2021/06/11 19:12', 0);
INSERT INTO `dispositivos` VALUES (NULL, 'fogo', 'Fogo', 'NÃO', '2021/06/11 19:12', 0);
INSERT INTO `dispositivos` VALUES (NULL, 'humidade', 'Humidade', '79.0', '2021/06/11 19:12', 0);
INSERT INTO `dispositivos` VALUES (NULL, 'lotacao', 'Lotação', '20', '2021/06/11 19:12', 0);
INSERT INTO `dispositivos` VALUES (NULL, 'luz', 'Luz', 'OFF', '2021/06/11 19:12', 1);
INSERT INTO `dispositivos` VALUES (NULL, 'portao', 'Portão', 'ABERTO', '2021/06/11 19:12', 1);
INSERT INTO `dispositivos` VALUES (NULL, 'temperatura', 'Temperatura', '-0.49', '2021/06/11 19:12', 0);