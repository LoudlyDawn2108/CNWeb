-- =============================================
-- Script tạo CSDL cho Bài tập 4 - SQL Server
-- =============================================

-- Tạo Database (chạy với quyền admin)
-- CREATE DATABASE FlowerShopDB;
-- GO

USE FlowerShopDB;
GO

-- =============================================
-- BẢNG CHO BÀI TẬP 1: QUẢN LÝ HOA
-- =============================================

-- Bảng lưu trữ thông tin các loài hoa
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'flowers')
BEGIN
    CREATE TABLE flowers (
        id INT IDENTITY(1,1) PRIMARY KEY,
        name NVARCHAR(255) NOT NULL,
        description NVARCHAR(MAX) NOT NULL,
        image VARCHAR(500) DEFAULT '../images/default.jpg',
        created_at DATETIME DEFAULT GETDATE(),
        updated_at DATETIME DEFAULT GETDATE()
    );
END
GO

-- Thêm dữ liệu mẫu cho bảng flowers
INSERT INTO flowers (name, description, image) VALUES
(N'Hoa Dạ Yến Thảo', N'Dạ yến thảo là lựa chọn thích hợp cho những ai yêu thích trồng hoa làm đẹp nhà ở. Hoa có thể nở rực quanh năm, kể cả tiết trời se lạnh của mùa xuân. Dạ yến thảo được trồng ở chậu treo nơi cửa sổ, ban công, dùng để trang trí các công trình như khách sạn, nhà hàng, trung tâm thương mại... Đặc biệt, vào dịp Tết, dạ yến thảo được trồng vào chậu, trang trí trong nhà, thay thế cho hoa mai, hoa đào.', '../images/18880f5fa3.jpg'),
(N'Hoa Đồng Tiền', N'Hoa đồng tiền thích hợp với mục đích làm đẹp nhà ở và trang trí công trình. Hoa có màu sắc đa dạng, tươi sáng và nở quanh năm. Hoa đồng tiền có thể trồng trong chậu treo hoặc để bàn. Đây là loại hoa dễ trồng, dễ chăm sóc, ít sâu bệnh.', '../images/3195301467.jpg'),
(N'Hoa Giấy', N'Hoa giấy là loại cây thích hợp khí hậu nhiệt đới, có thể chịu hạn tốt. Hoa nở rực rỡ với nhiều màu sắc như tím, đỏ, hồng, vàng, cam, trắng... Hoa giấy thường được dùng để trang trí sân vườn, làm hàng rào, hoặc uốn tạo thế bonsai. Cây dễ trồng, dễ chăm sóc và có thể sống lâu năm.', '../images/3222e80544.jpg'),
(N'Hoa Cẩm Chướng', N'Cẩm chướng là loài hoa tượng trưng cho tình yêu thương, sự ngưỡng mộ và lòng biết ơn. Hoa có màu sắc đa dạng, mùi hương dịu nhẹ, thường được dùng làm hoa cắt cành hoặc trồng trong chậu. Cẩm chướng thích hợp với khí hậu ôn đới, cần ánh sáng mặt trời vừa phải.', '../images/3fc1677988.jpg'),
(N'Hoa Hồng', N'Hoa hồng được mệnh danh là nữ hoàng của các loài hoa, tượng trưng cho tình yêu, sắc đẹp và sự quyến rũ. Hoa có nhiều màu sắc và giống loài khác nhau, mùi hương thơm ngát. Hoa hồng được trồng phổ biến trong vườn nhà, công viên, và thường được dùng làm hoa cắt cành.', '../images/4bb8bbbabe.jpg'),
(N'Hoa Tulip', N'Tulip là loài hoa biểu tượng của mùa xuân, có nguồn gốc từ Hà Lan. Hoa có hình dáng đẹp, màu sắc rực rỡ và đa dạng. Tulip thường nở vào mùa xuân, thích hợp với khí hậu ôn đới lạnh. Đây là loài hoa được yêu thích để trang trí nhà cửa và làm quà tặng.', '../images/57208fe381.jpg'),
(N'Hoa Lan', N'Hoa lan là biểu tượng của sự thanh lịch, cao quý và tinh tế. Có nhiều loại lan khác nhau với màu sắc và hình dáng đa dạng. Lan thích hợp trồng trong nhà, văn phòng, và được dùng để trang trí các dịp lễ tết. Lan cần được chăm sóc cẩn thận về độ ẩm và ánh sáng.', '../images/6b5946b42d.jpg'),
(N'Hoa Cúc', N'Hoa cúc tượng trưng cho sự trường thọ, hạnh phúc và niềm vui. Có nhiều loại cúc với màu sắc đa dạng như vàng, trắng, tím, đỏ... Hoa cúc thường nở vào mùa thu, dễ trồng và chăm sóc. Đây là loài hoa phổ biến trong các dịp lễ tết ở Việt Nam.', '../images/710510961f.jpg'),
(N'Hoa Ly', N'Hoa ly có vẻ đẹp thanh tao, sang trọng và mùi hương nồng nàn đặc trưng. Hoa thường có màu trắng, hồng, vàng, cam... Ly được trồng phổ biến trong vườn và làm hoa cắt cành. Hoa ly tượng trưng cho sự thuần khiết, tình yêu và lòng tôn kính.', '../images/a9e829b23e.jpg'),
(N'Hoa Lavender', N'Lavender nổi tiếng với mùi hương dễ chịu, có tác dụng thư giãn và giảm stress. Hoa có màu tím đặc trưng, mọc thành chùm. Lavender thích hợp với khí hậu ôn đới, cần ánh sáng mặt trời nhiều. Hoa được dùng trong công nghiệp mỹ phẩm, làm trà và trang trí.', '../images/b0e973125a.jpg'),
(N'Hoa Hướng Dương', N'Hoa hướng dương tượng trưng cho sự lạc quan, niềm vui và năng lượng tích cực. Hoa có màu vàng rực rỡ, luôn hướng về phía mặt trời. Hướng dương dễ trồng, phát triển nhanh và có thể trồng để lấy hạt. Đây là loài hoa mang ý nghĩa tích cực trong cuộc sống.', '../images/cbd7393a70.jpg'),
(N'Hoa Sen', N'Hoa sen là biểu tượng của sự thanh cao, trong sáng và giác ngộ trong văn hóa Á Đông. Hoa có màu hồng hoặc trắng, mọc trong ao, đầm. Sen cần nhiều nước và ánh sáng mặt trời. Hoa sen không chỉ đẹp mà còn có giá trị ẩm thực và y học.', '../images/ea6a2872ba.jpg'),
(N'Hoa Đào', N'Hoa đào là biểu tượng của mùa xuân và Tết cổ truyền Việt Nam. Hoa có màu hồng rực rỡ, nở vào dịp Tết Nguyên Đán. Hoa đào tượng trưng cho sự may mắn, tài lộc và khởi đầu mới. Cây đào được trồng phổ biến ở miền Nam Việt Nam.', '../images/ed0c78b472.jpg'),
(N'Hoa Mai', N'Hoa mai là hoa đặc trưng của mùa xuân miền Nam Việt Nam. Hoa có màu vàng tươi, nở rộ vào dịp Tết. Mai tượng trưng cho sự phát đạt, thịnh vượng và niềm hy vọng. Cây mai thường được tạo thế và chăm sóc cẩn thận để có dáng đẹp.', '../images/f739a8bca8.jpg');
GO

-- =============================================
-- BẢNG CHO BÀI TẬP 2: HỆ THỐNG TRẮC NGHIỆM
-- =============================================

-- Bảng lưu trữ câu hỏi
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'questions')
BEGIN
    CREATE TABLE questions (
        id INT IDENTITY(1,1) PRIMARY KEY,
        question NVARCHAR(MAX) NOT NULL,
        is_multiple_choice BIT DEFAULT 0,
        created_at DATETIME DEFAULT GETDATE()
    );
END
GO

-- Bảng lưu trữ các đáp án
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'question_options')
BEGIN
    CREATE TABLE question_options (
        id INT IDENTITY(1,1) PRIMARY KEY,
        question_id INT NOT NULL,
        option_letter CHAR(1) NOT NULL,
        option_text NVARCHAR(MAX) NOT NULL,
        is_correct BIT DEFAULT 0,
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
    );
END
GO

-- Bảng lưu lịch sử bài thi
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'quiz_results')
BEGIN
    CREATE TABLE quiz_results (
        id INT IDENTITY(1,1) PRIMARY KEY,
        student_name NVARCHAR(255),
        total_questions INT NOT NULL,
        correct_answers INT NOT NULL,
        score DECIMAL(5,2) NOT NULL,
        submitted_at DATETIME DEFAULT GETDATE()
    );
END
GO

-- =============================================
-- BẢNG CHO BÀI TẬP 3: ĐIỂM DANH
-- =============================================

-- Bảng lưu danh sách điểm danh
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'attendance_records')
BEGIN
    CREATE TABLE attendance_records (
        id INT IDENTITY(1,1) PRIMARY KEY,
        username NVARCHAR(100) NOT NULL,
        password NVARCHAR(255) NOT NULL,
        lastname NVARCHAR(100) NOT NULL,
        firstname NVARCHAR(100) NOT NULL,
        city NVARCHAR(100),
        email NVARCHAR(255),
        course1 NVARCHAR(100),
        is_present BIT DEFAULT 0,
        attendance_date DATE,
        created_at DATETIME DEFAULT GETDATE()
    );
END
GO

-- Bảng lưu lịch sử điểm danh
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'attendance_history')
BEGIN
    CREATE TABLE attendance_history (
        id INT IDENTITY(1,1) PRIMARY KEY,
        record_id INT NOT NULL,
        is_present BIT DEFAULT 0,
        attendance_date DATE NOT NULL,
        created_at DATETIME DEFAULT GETDATE(),
        FOREIGN KEY (record_id) REFERENCES attendance_records(id) ON DELETE CASCADE
    );
END
GO

-- =============================================
-- BẢNG QUẢN LÝ FILE UPLOAD
-- =============================================

IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'uploaded_files')
BEGIN
    CREATE TABLE uploaded_files (
        id INT IDENTITY(1,1) PRIMARY KEY,
        file_name NVARCHAR(255) NOT NULL,
        original_name NVARCHAR(255) NOT NULL,
        file_type VARCHAR(50) NOT NULL,
        file_size INT NOT NULL,
        file_path VARCHAR(500) NOT NULL,
        uploaded_at DATETIME DEFAULT GETDATE()
    );
END
GO

-- =============================================
-- TRIGGER CẬP NHẬT THỜI GIAN
-- =============================================

-- Trigger cập nhật updated_at cho bảng flowers
IF EXISTS (SELECT * FROM sys.triggers WHERE name = 'trg_flowers_update')
    DROP TRIGGER trg_flowers_update;
GO

CREATE TRIGGER trg_flowers_update
ON flowers
AFTER UPDATE
AS
BEGIN
    UPDATE flowers
    SET updated_at = GETDATE()
    FROM flowers f
    INNER JOIN inserted i ON f.id = i.id;
END
GO

PRINT N'Tạo CSDL thành công!';
GO
