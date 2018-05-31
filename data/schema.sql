DROP DATABASE IF EXISTS RUN;
CREATE DATABASE RUN;
USE RUN;

CREATE TABLE user (
  id        INT NOT NULL AUTO_INCREMENT,
  name      VARCHAR(64) NOT NULL UNIQUE,
  password  VARCHAR(128) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE file (
  id        INT NOT NULL AUTO_INCREMENT,
  name      VARCHAR(64) UNIQUE,
  tags      VARCHAR(64),
  path      VARCHAR(64),
  PRIMARY KEY (id)
);

CREATE TABLE user_file (
  user_id    INT NOT NULL,
  file_id    INT NOT NULL,
  PRIMARY KEY (user_id, file_id),
  FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
  FOREIGN KEY (file_id) REFERENCES file(id) ON DELETE CASCADE
);


#--makes a user with the password test
INSERT INTO user (name, password) VALUES ('test', '$2y$10$/wt7Nannt9mfYCEw0e/B6.PBpPCMnRuyAmHKBEI8O6Vm0R6BQkAdi');
