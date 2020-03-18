

CREATE TABLE usuario (
  id int(6) unsigned NOT NULL AUTO_INCREMENT,
  token varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  name varchar(100) NOT NULL,
  password varchar(100) DEFAULT NULL,
  PRIMARY KEY (id)
)

CREATE TABLE IF NOT EXISTS drinks (
    id INT AUTO_INCREMENT,
    drink INT DEFAULT 1,
    idusuario INT UNSIGNED,
    PRIMARY KEY (id),
    FOREIGN KEY (idusuario)
        REFERENCES usuario (id)
);

