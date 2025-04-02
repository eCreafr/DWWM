DROP TABLE IF EXISTS recipe;
CREATE TABLE recipe (
  id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(100) NOT NULL,
  description VARCHAR(2000) NOT NULL
) engine=innodb CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO recipe (id, title, description) VALUES
(1, 'Tarte tatin', 'Tu fais une tarte, tu la mets au four et puis tatin.'),
(2, 'Pudding à l\'arsenic', 'Dans un grand bol de strychnine, délayer de la morphine. Faites tiédir à la casserole, un bon verre de pétrole...');
