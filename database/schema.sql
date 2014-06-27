
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
INSERT INTO categories VALUES(0, 1, 'Transportation');
INSERT INTO categories VALUES(0, 1, 'Utilities');

CREATE TABLE transactions (
  id INT AUTO_INCREMENT,
  person_id INT NOT NULL, 
  category_id INT NOT NULL,

  timestamp DATE,
  description VARCHAR(100),
  amount DECIMAL,

  PRIMARY KEY(id),
  FOREIGN KEY(person_id) REFERENCES people(id) ON DELETE RESTRICT,
  FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

INSERT INTO transactions VALUES(0, 1, 2, "2014/06/27", "Plane ticket", 200);
INSERT INTO transactions VALUES(0, 1, 3, "2014/06/25", "Electricity", 20);
INSERT INTO transactions VALUES(0, 2, 3, "2014/06/22", "Water", 20);
INSERT INTO transactions VALUES(0, 2, 2, "2014/06/25", "Metro ticket", 20);
