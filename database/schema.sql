
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS subcategories;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS people;

CREATE TABLE people (
  id INT AUTO_INCREMENT,
  name VARCHAR(50),

  PRIMARY KEY(id)
);

INSERT INTO people VALUES(0, 'Ivar');
INSERT INTO people VALUES(0, 'Daphne');


CREATE TABLE categories (
  id INT AUTO_INCREMENT,
  parent_id INT NULL,
  name VARCHAR(50),

  PRIMARY KEY(id),
  FOREIGN KEY(parent_id) REFERENCES categories(id) ON DELETE RESTRICT
);

INSERT INTO categories VALUES(0, NULL, '');

CREATE TABLE transactions (
  id INT AUTO_INCREMENT,
  person_id INT NOT NULL, 
  category_id INT NOT NULL,

  timestamp DATE,
  description VARCHAR(100),
  amount INTEGER,

  PRIMARY KEY(id),
  FOREIGN KEY(person_id) REFERENCES people(id) ON DELETE RESTRICT,
  FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE RESTRICT
);
