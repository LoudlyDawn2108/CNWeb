<?php
/**
 * Database Configuration - SQL Server với PDO
 * Cấu hình kết nối CSDL cho Bài tập 4
 */

class Database {
    // Cấu hình SQL Server
    private static $host = 'localhost';
    private static $port = '1433';
    private static $dbname = 'FlowerShopDB';
    private static $username = 'sa';
    private static $password = 'YourPassword123!';
    
    private static $conn = null;
    
    /**
     * Lấy kết nối PDO đến SQL Server
     * @return PDO|null
     */
    public static function getConnection() {
        if (self::$conn === null) {
            try {
                // Connection string cho SQL Server
                $dsn = "sqlsrv:Server=" . self::$host . "," . self::$port . ";Database=" . self::$dbname;
                
                // Tùy chọn PDO
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8
                ];
                
                self::$conn = new PDO($dsn, self::$username, self::$password, $options);
                
            } catch (PDOException $e) {
                // Fallback: Thử kết nối với driver ODBC nếu sqlsrv không khả dụng
                try {
                    $dsn = "odbc:Driver={ODBC Driver 17 for SQL Server};Server=" . self::$host . "," . self::$port . ";Database=" . self::$dbname;
                    
                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ];
                    
                    self::$conn = new PDO($dsn, self::$username, self::$password, $options);
                    
                } catch (PDOException $e2) {
                    die("Lỗi kết nối database: " . $e->getMessage() . " | " . $e2->getMessage());
                }
            }
        }
        return self::$conn;
    }
    
    /**
     * Đóng kết nối
     */
    public static function closeConnection() {
        self::$conn = null;
    }
    
    /**
     * Kiểm tra kết nối
     * @return bool
     */
    public static function testConnection() {
        try {
            $conn = self::getConnection();
            return $conn !== null;
        } catch (Exception $e) {
            return false;
        }
    }
}

/**
 * Alternative: MySQL Configuration (nếu không dùng SQL Server)
 * Uncomment và sử dụng class này nếu muốn dùng MySQL
 */
/*
class Database {
    private static $host = 'localhost';
    private static $dbname = 'flower_shop_db';
    private static $username = 'root';
    private static $password = '';
    
    private static $conn = null;
    
    public static function getConnection() {
        if (self::$conn === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8mb4";
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];
                
                self::$conn = new PDO($dsn, self::$username, self::$password, $options);
                
            } catch (PDOException $e) {
                die("Lỗi kết nối database: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
    
    public static function closeConnection() {
        self::$conn = null;
    }
    
    public static function testConnection() {
        try {
            $conn = self::getConnection();
            return $conn !== null;
        } catch (Exception $e) {
            return false;
        }
    }
}
*/
?>
