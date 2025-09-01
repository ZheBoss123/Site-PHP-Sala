CREATE DATABASE IF NOT EXISTS sala_alexis CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP USER IF EXISTS 'alexisuser'@'localhost';
CREATE USER 'alexisuser'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON sala_alexis.* TO 'alexisuser'@'localhost';
FLUSH PRIVILEGES;

CREATE TABLE migrations (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) NOT NULL UNIQUE,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE user_roles (
    role_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(128) UNIQUE
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


create table abonamente (
    abonament_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    abonament_name VARCHAR(128) NOT NULL,
    total_price DECIMAL(10,2),
    purchase_date DATE NOT NULL
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE users (
   user_id INTEGER AUTO_INCREMENT PRIMARY KEY,
   first_name VARCHAR(128) NOT NULL,
   last_name VARCHAR(128),
   email VARCHAR(128) UNIQUE NOT NULL,
   password VARCHAR(128) NOT NULL,
   role_id INTEGER NOT NULL,
   abonament_id INTEGER,
   FOREIGN KEY(role_id) REFERENCES user_roles(role_id) ON DELETE RESTRICT,
   FOREIGN KEY(abonament_id) REFERENCES abonamente(abonament_id) ON DELETE RESTRICT
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE facilitati (
    facilitate_id INTEGER AUTO_INCREMENT PRIMARY KEY,
    facilitate_name VARCHAR(128)
    
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE privilegiu (
    abonament_id INTEGER NOT NULL,
    facilitate_id INTEGER NOT NULL,
    PRIMARY KEY (abonament_id, facilitate_id),
    FOREIGN KEY(abonament_id) REFERENCES abonamente(abonament_id) ON DELETE CASCADE,
    FOREIGN KEY(facilitate_id) REFERENCES facilitati(facilitate_id) ON DELETE CASCADE
    
) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO user_roles (name) VALUES ('admin');
INSERT INTO user_roles (name) VALUES ('user');
INSERT INTO user_roles (name) VALUES ('guest');


INSERT INTO facilitati (facilitate_name) VALUES ('sala');
INSERT INTO facilitati (facilitate_name) VALUES ('sauna');
INSERT INTO facilitati (facilitate_name) VALUES ('sala mma');
INSERT INTO facilitati (facilitate_name) VALUES ('spa');
INSERT INTO facilitati (facilitate_name) VALUES ('piscina');



INSERT INTO users (first_name, last_name, email, password, role_id) VALUES ('admin', 'admin', 'admin@admin.com', 'admin', 1);
INSERT INTO users (first_name, last_name, email, password, role_id) VALUES ('user', 'user', 'user@user.com', 'user', 2);

INSERT INTO abonamente (abonament_name) VALUES ('standard');
INSERT INTO abonamente (abonament_name) VALUES ('gold');
INSERT INTO abonamente (abonament_name) VALUES ('platinum');

INSERT INTO migrations (name) VALUES ('create_db_1');

INSERT INTO privilegiu (abonament_id, facilitate_id) VALUES (1,1);
INSERT INTO privilegiu (abonament_id, facilitate_id) VALUES (2,1);
INSERT INTO privilegiu (abonament_id, facilitate_id) VALUES (2,2);
INSERT INTO privilegiu (abonament_id, facilitate_id) VALUES (2,3);
INSERT INTO privilegiu (abonament_id, facilitate_id) VALUES (3,1);
INSERT INTO privilegiu (abonament_id, facilitate_id) VALUES (3,2);
INSERT INTO privilegiu (abonament_id, facilitate_id) VALUES (3,3);
INSERT INTO privilegiu (abonament_id, facilitate_id) VALUES (3,4);
INSERT INTO privilegiu (abonament_id, facilitate_id) VALUES (3,5);


CREATE TABLE roles_permissions ( role_id INT NOT NULL, permission_name VARCHAR(255) NOT NULL, PRIMARY KEY (role_id, permission_name) ); 
-- Admin (role_id = 1) 
INSERT INTO roles_permissions (role_id, permission_name) VALUES (1, 'create_user'), (1, 'edit_user'), (1, 'delete_user'), (1, 'view_user'); 
-- User obișnuit (role_id = 2)
 INSERT INTO roles_permissions (role_id, permission_name) VALUES (2, 'view_user');

 CREATE TABLE user_abonamente (
    user_id INT NOT NULL,
    abonament_id INT NOT NULL,
    purchase_date DATE NOT NULL DEFAULT CURRENT_DATE,
    PRIMARY KEY (user_id, abonament_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (abonament_id) REFERENCES abonamente(abonament_id)
);


ALTER TABLE abonamente
DROP COLUMN purchase_date;