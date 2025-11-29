-- =============================================
-- Script tạo CSDL cho Bài tập 4 - MySQL
-- (Alternative nếu không dùng SQL Server)
-- =============================================

-- Tạo Database
CREATE DATABASE IF NOT EXISTS flower_shop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE flower_shop_db;

-- =============================================
-- BẢNG CHO BÀI TẬP 1: QUẢN LÝ HOA
-- =============================================

CREATE TABLE IF NOT EXISTS flowers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(500) DEFAULT '../images/default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu cho bảng flowers
INSERT INTO flowers (name, description, image) VALUES
('Hoa Dạ Yến Thảo', 'Dạ yến thảo là lựa chọn thích hợp cho những ai yêu thích trồng hoa làm đẹp nhà ở. Hoa có thể nở rực quanh năm, kể cả tiết trời se lạnh của mùa xuân.', '../images/18880f5fa3.jpg'),
('Hoa Đồng Tiền', 'Hoa đồng tiền thích hợp với mục đích làm đẹp nhà ở và trang trí công trình. Hoa có màu sắc đa dạng, tươi sáng và nở quanh năm.', '../images/3195301467.jpg'),
('Hoa Giấy', 'Hoa giấy là loại cây thích hợp khí hậu nhiệt đới, có thể chịu hạn tốt. Hoa nở rực rỡ với nhiều màu sắc như tím, đỏ, hồng, vàng, cam, trắng...', '../images/3222e80544.jpg'),
('Hoa Cẩm Chướng', 'Cẩm chướng là loài hoa tượng trưng cho tình yêu thương, sự ngưỡng mộ và lòng biết ơn.', '../images/3fc1677988.jpg'),
('Hoa Hồng', 'Hoa hồng được mệnh danh là nữ hoàng của các loài hoa, tượng trưng cho tình yêu, sắc đẹp và sự quyến rũ.', '../images/4bb8bbbabe.jpg'),
('Hoa Tulip', 'Tulip là loài hoa biểu tượng của mùa xuân, có nguồn gốc từ Hà Lan.', '../images/57208fe381.jpg'),
('Hoa Lan', 'Hoa lan là biểu tượng của sự thanh lịch, cao quý và tinh tế.', '../images/6b5946b42d.jpg'),
('Hoa Cúc', 'Hoa cúc tượng trưng cho sự trường thọ, hạnh phúc và niềm vui.', '../images/710510961f.jpg'),
('Hoa Ly', 'Hoa ly có vẻ đẹp thanh tao, sang trọng và mùi hương nồng nàn đặc trưng.', '../images/a9e829b23e.jpg'),
('Hoa Lavender', 'Lavender nổi tiếng với mùi hương dễ chịu, có tác dụng thư giãn và giảm stress.', '../images/b0e973125a.jpg'),
('Hoa Hướng Dương', 'Hoa hướng dương tượng trưng cho sự lạc quan, niềm vui và năng lượng tích cực.', '../images/cbd7393a70.jpg'),
('Hoa Sen', 'Hoa sen là biểu tượng của sự thanh cao, trong sáng và giác ngộ trong văn hóa Á Đông.', '../images/ea6a2872ba.jpg'),
('Hoa Đào', 'Hoa đào là biểu tượng của mùa xuân và Tết cổ truyền Việt Nam.', '../images/ed0c78b472.jpg'),
('Hoa Mai', 'Hoa mai là hoa đặc trưng của mùa xuân miền Nam Việt Nam.', '../images/f739a8bca8.jpg');

-- =============================================
-- BẢNG CHO BÀI TẬP 2: HỆ THỐNG TRẮC NGHIỆM
-- =============================================

CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    is_multiple_choice TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS question_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    option_letter CHAR(1) NOT NULL,
    option_text TEXT NOT NULL,
    is_correct TINYINT(1) DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS quiz_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(255),
    total_questions INT NOT NULL,
    correct_answers INT NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- BẢNG CHO BÀI TẬP 3: ĐIỂM DANH
-- =============================================

CREATE TABLE IF NOT EXISTS attendance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    city VARCHAR(100),
    email VARCHAR(255),
    course1 VARCHAR(100),
    is_present TINYINT(1) DEFAULT 0,
    attendance_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS attendance_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    record_id INT NOT NULL,
    is_present TINYINT(1) DEFAULT 0,
    attendance_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (record_id) REFERENCES attendance_records(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- BẢNG QUẢN LÝ FILE UPLOAD
-- =============================================

CREATE TABLE IF NOT EXISTS uploaded_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size INT NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
