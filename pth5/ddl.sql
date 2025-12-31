CREATE DATABASE cse485_web;

USE cse485_web;

CREATE TABLE sinhvien
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    ten_sinh_vien VARCHAR(255) NOT NULL,
    email         VARCHAR(255) NOT NULL,
    ngay_tao      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);