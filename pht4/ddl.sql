CREATE DATABASE cse485_web;
GO

USE cse485_web;
GO

CREATE TABLE sinhvien
(
    id            INT IDENTITY (1,1) PRIMARY KEY,
    ten_sinh_vien VARCHAR(255) NOT NULL,
    email         VARCHAR(255) NOT NULL,
    ngay_tao      DATETIME2 DEFAULT GETDATE()
);