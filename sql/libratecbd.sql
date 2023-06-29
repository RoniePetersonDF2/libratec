-- -----------------------------------------------------
-- Schema libratecdb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `libratecdb`;

CREATE SCHEMA `libratecdb` DEFAULT CHARACTER SET utf8 ;
USE `libratecdb`;

-- -----------------------------------------------------
-- Table `libratecdb`.`surdo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `libratecdb`.`surdo` (
  `id_surdo` INT NOT NULL AUTO_INCREMENT,
  `nome_surdo` VARCHAR(150) NOT NULL,
  `senha_surdo` VARCHAR(45) NOT NULL,
  `cpf_surdo` VARCHAR(14) NOT NULL,
  `email_surdo` VARCHAR(100) NOT NULL,
  `dt_nasc_surdo` DATE NOT NULL,
  `laudo_med_surdo` VARCHAR(255) NOT NULL,
  `endereco_surdo` VARCHAR(255) NOT NULL,
  `celular_surdo` VARCHAR(15) NOT NULL,
  `perfil` CHAR(3) NOT NULL DEFAULT 'SUR' COMMENT 'ADM=Administrador\nEMP=Empresa\nINT=Interprete\nSUR=Surdo', 
  PRIMARY KEY (`id_surdo`),
  UNIQUE KEY `uk_cpf_surdo` (`cpf_surdo`),
  UNIQUE KEY `uk_email_surdo` (`email_surdo`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `libratecdb`.`empresa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `libratecdb`.`empresa` (
  `id_empresa` INT NOT NULL AUTO_INCREMENT,
  `nome_empresa` VARCHAR(150) NOT NULL,
  `email_empresa` VARCHAR(100) NOT NULL,
  `senha_empresa` VARCHAR(45) NOT NULL,
  `cnpj` VARCHAR(20) NOT NULL,
  `endereco_empresa` VARCHAR(255) NOT NULL,
  `telefone_empresa` VARCHAR(15) NOT NULL,
  `perfil` CHAR(3) NOT NULL DEFAULT 'EMP' COMMENT 'ADM=Administrador\nEMP=Empresa\nINT=Interprete\nSUR=Surdo', 
  PRIMARY KEY (`id_empresa`),
  UNIQUE KEY `uk_cnpj` (`cnpj`),
  UNIQUE KEY `uk_email_empresa` (`email_empresa`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `libratecdb`.`interprete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `libratecdb`.`interprete` (
  `id_interprete` INT NOT NULL AUTO_INCREMENT,
  `nome_interprete` VARCHAR(150) NOT NULL,
  `senha_interprete` VARCHAR(45) NOT NULL,
  `email_interprete` VARCHAR(100) NOT NULL,
  `endereco_interprete` VARCHAR(255) NOT NULL,
  `celular_interprete` VARCHAR(15) NOT NULL,
  `certificado` VARCHAR(255) NOT NULL,
  `perfil` CHAR(3) NOT NULL DEFAULT 'INT' COMMENT 'ADM=Administrador\nEMP=Empresa\nINT=Interprete\nSUR=Surdo',
  PRIMARY KEY (`id_interprete`),
  UNIQUE KEY `uk_email_interprete` (`email_interprete`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `libratecdb`.`vagas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `libratecdb`.`vagas` (
  `id_vagas` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(255) NOT NULL,
  `id_empresa` INT NOT NULL,
  PRIMARY KEY (`id_vagas`),
  FOREIGN KEY (`id_empresa`)
    REFERENCES `empresa`(`id_empresa`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `libratecdb`.`surdo_has_interprete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `libratecdb`.`surdo_has_interprete` (
  `id_surdo` INT NOT NULL,
  `id_interprete` INT NOT NULL,
  PRIMARY KEY (`id_surdo`, `id_interprete`),
  INDEX `fk_surdo_has_interprete_interprete1_idx` (`id_interprete` ASC),
  INDEX `fk_surdo_has_interprete_surdo_idx` (`id_surdo` ASC),
  CONSTRAINT `fk_surdo_has_interprete_surdo`
    FOREIGN KEY (`id_surdo`)
    REFERENCES `libratecdb`.`surdo` (`id_surdo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_surdo_has_interprete_interprete1`
    FOREIGN KEY (`id_interprete`)
    REFERENCES `libratecdb`.`interprete` (`id_interprete`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `libratecdb`.`empresa_has_interprete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `libratecdb`.`empresa_has_interprete` (
  `id_empresa` INT NOT NULL,
  `id_interprete` INT NOT NULL,
  PRIMARY KEY (`id_empresa`, `id_interprete`),
  INDEX `fk_empresa_has_interprete_interprete1_idx` (`id_interprete` ASC),
  INDEX `fk_empresa_has_interprete_empresa1_idx` (`id_empresa` ASC),
  CONSTRAINT `fk_empresa_has_interprete_empresa1`
    FOREIGN KEY (`id_empresa`)
    REFERENCES `libratecdb`.`empresa` (`id_empresa`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_empresa_has_interprete_interprete1`
    FOREIGN KEY (`id_interprete`)
    REFERENCES `libratecdb`.`interprete` (`id_interprete`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


INSERT INTO `surdo`(`nome_surdo`, `cpf_surdo`, `email_surdo`, `senha_surdo`, `dt_nasc_surdo`, `laudo_med_surdo`,  `endereco_surdo`,`celular_surdo`, `perfil`) 
VALUES ('admin',  '111.111.111-11', 'admin@email.com', md5('123'), '2000-11-01', 'admin.png',  'endereco', '(11)11111-1111', 'ADM'),
('Chico',  '123.456.789-11', 'chico@email.com', md5('12345'), '2000-07-10', 'laudo.png',  'qnp 10', '(61)99859-2531', 'SUR');

INSERT INTO `empresa`(`nome_empresa`, `cnpj`, `email_empresa`, `senha_empresa`, `endereco_empresa`,`telefone_empresa`)
VALUES ('Interpreter', '12.456.777/0001-45', 'interpreter@email.com', md5('1234567'), 'qnn 14','(61)99407-0806'); 

INSERT INTO `interprete`(`nome_interprete`,`senha_interprete`, `email_interprete`,  `endereco_interprete`,  `celular_interprete`, `certificado`) 
VALUES ('Jose', md5('123456'),'jose@email.com',  'eqnp 20',  '(61)99999-9999', 'Trabalho como intérprete a 3 anos.'); 

INSERT INTO `vagas`(`descricao`, `id_empresa`) 
VALUES ('15ª edição do evento ocorre de forma gratuita no sábado (27) e domingo (28), a partir das 14h, no Bom Retiro.', 1);
