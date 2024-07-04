create database db_parkinglaminas
default character set utf8
default collate utf8_general_ci;

use db_parkinglaminas;

CREATE TABLE access_levels (
    access_level_id INT AUTO_INCREMENT PRIMARY KEY,
    level_name VARCHAR(50) NOT NULL UNIQUE
);
INSERT INTO access_levels 
	values(default, 'admin'),
    (default, 'usuario');
SELECT * FROM access_levels;

CREATE TABLE plans (
    plan_id INT AUTO_INCREMENT PRIMARY KEY,
    plan_name VARCHAR(50) NOT NULL UNIQUE,
    plan_type ENUM('pre-pago', 'pos-pago') NOT NULL,
    rate DECIMAL(10, 2) NOT NULL
);
INSERT INTO plans VALUES (
	default, 'Pré-pago', 'pre-pago', 6.50),
    (default, 'Pós-pago', 'pos-pago', 8.00);
SELECT * FROM plans;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    access_level_id INT NOT NULL,
    plan_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (access_level_id) REFERENCES access_levels(access_level_id),
    FOREIGN KEY (plan_id) REFERENCES plans(plan_id)
);
INSERT INTO users (username, password_hash, access_level_id, plan_id) VALUES
	('admin', SHA2('adminpassword', 256), 1, 1),
	(),
	('user2', SHA2('passworduser2', 256), 2, 1);
ALTER TABLE users ADD email VARCHAR(80) NOT NULL;
SELECT * FROM users;


CREATE TABLE parking_spots (
    spot_id INT AUTO_INCREMENT PRIMARY KEY,
    spot_number VARCHAR(10) NOT NULL UNIQUE,
    is_available BOOLEAN DEFAULT TRUE
);

CREATE TABLE psusage (
    usage_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    spot_id INT NOT NULL,
    check_in_time TIMESTAMP NOT NULL,
    check_out_time TIMESTAMP NULL,
    amount DECIMAL(10, 2) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (spot_id) REFERENCES parking_spots(spot_id)
);
ALTER TABLE psusage ADD COLUMN plan_id INT;


CREATE TABLE vehicle_flow (
    flow_id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_plate VARCHAR(10) NOT NULL,
    check_in_time TIMESTAMP NOT NULL,
    check_out_time TIMESTAMP NULL,
    spot_id INT,
    FOREIGN KEY (spot_id) REFERENCES parking_spots(spot_id)
);

CREATE TABLE financial_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    report_date DATE NOT NULL,
    total_income DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE USER 'admin'@'localhost' identified WITH mysql_native_password by 'admin';
grant all privileges on db_parkinglaminas.* to 'admin'@'localhost' with grant option;

