<?php
/**
 * Bài tập 3 - Hệ thống Điểm danh với Database PDO
 * Model AttendanceRecord và AttendanceRepository
 */

require_once __DIR__ . '/../config/database.php';

class AttendanceRecord
{
    public int $id;
    public string $username;
    public string $password;
    public string $lastname;
    public string $firstname;
    public string $city;
    public string $email;
    public string $course1;
    public bool $isPresent;
    public ?string $attendanceDate;

    public function __construct($username = '', $password = '', $lastname = '', $firstname = '', $city = '', $email = '', $course1 = '')
    {
        $this->id = 0;
        $this->username = $username;
        $this->password = $password;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->city = $city;
        $this->email = $email;
        $this->course1 = $course1;
        $this->isPresent = false;
        $this->attendanceDate = null;
    }

    public static function fromArray(array $data): AttendanceRecord
    {
        $record = new AttendanceRecord();
        $record->id = $data['id'] ?? 0;
        $record->username = $data['username'] ?? '';
        $record->password = $data['password'] ?? '';
        $record->lastname = $data['lastname'] ?? '';
        $record->firstname = $data['firstname'] ?? '';
        $record->city = $data['city'] ?? '';
        $record->email = $data['email'] ?? '';
        $record->course1 = $data['course1'] ?? '';
        $record->isPresent = (bool)($data['is_present'] ?? false);
        $record->attendanceDate = $data['attendance_date'] ?? null;
        return $record;
    }
}

class AttendanceRepository
{
    private $conn;
    private $useDatabase;

    public function __construct()
    {
        try {
            $this->conn = Database::getConnection();
            $this->useDatabase = ($this->conn !== null);
        } catch (Exception $e) {
            $this->useDatabase = false;
            $this->conn = null;
        }
    }

    public function isUsingDatabase(): bool
    {
        return $this->useDatabase;
    }

    /**
     * Lấy tất cả bản ghi điểm danh
     */
    public function getAll(): array
    {
        if (!$this->useDatabase) {
            return [];
        }

        try {
            $sql = "SELECT * FROM attendance_records ORDER BY id ASC";
            $stmt = $this->conn->query($sql);
            $results = $stmt->fetchAll();

            $records = [];
            foreach ($results as $row) {
                $records[] = AttendanceRecord::fromArray($row);
            }

            return $records;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Lấy bản ghi theo ID
     */
    public function getById(int $id): ?AttendanceRecord
    {
        if (!$this->useDatabase) {
            return null;
        }

        try {
            $sql = "SELECT * FROM attendance_records WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();

            if ($row) {
                return AttendanceRecord::fromArray($row);
            }
            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Thêm bản ghi mới
     */
    public function add(AttendanceRecord $record): ?int
    {
        if (!$this->useDatabase) {
            return null;
        }

        try {
            $sql = "INSERT INTO attendance_records (username, password, lastname, firstname, city, email, course1) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $record->username,
                $record->password,
                $record->lastname,
                $record->firstname,
                $record->city,
                $record->email,
                $record->course1
            ]);
            return (int)$this->conn->lastInsertId();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Cập nhật trạng thái điểm danh
     */
    public function updateAttendance(int $id, bool $isPresent, string $date = null): bool
    {
        if (!$this->useDatabase) {
            return false;
        }

        try {
            $date = $date ?? date('Y-m-d');
            $sql = "UPDATE attendance_records SET is_present = ?, attendance_date = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$isPresent ? 1 : 0, $date, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Cập nhật nhiều bản ghi điểm danh cùng lúc
     */
    public function updateMultipleAttendance(array $presentIds, string $date = null): bool
    {
        if (!$this->useDatabase) {
            return false;
        }

        try {
            $date = $date ?? date('Y-m-d');
            
            $this->conn->beginTransaction();

            // Reset tất cả về không có mặt
            $resetSql = "UPDATE attendance_records SET is_present = 0, attendance_date = ?";
            $resetStmt = $this->conn->prepare($resetSql);
            $resetStmt->execute([$date]);

            // Cập nhật những người có mặt
            if (!empty($presentIds)) {
                $placeholders = implode(',', array_fill(0, count($presentIds), '?'));
                $updateSql = "UPDATE attendance_records SET is_present = 1, attendance_date = ? WHERE id IN ($placeholders)";
                $updateStmt = $this->conn->prepare($updateSql);
                $params = array_merge([$date], $presentIds);
                $updateStmt->execute($params);
            }

            // Lưu lịch sử điểm danh
            $allRecords = $this->getAll();
            foreach ($allRecords as $record) {
                $isPresent = in_array($record->id, $presentIds) ? 1 : 0;
                $historySql = "INSERT INTO attendance_history (record_id, is_present, attendance_date) VALUES (?, ?, ?)";
                $historyStmt = $this->conn->prepare($historySql);
                $historyStmt->execute([$record->id, $isPresent, $date]);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /**
     * Xóa tất cả bản ghi
     */
    public function deleteAll(): bool
    {
        if (!$this->useDatabase) {
            return false;
        }

        try {
            $this->conn->exec("DELETE FROM attendance_history");
            $this->conn->exec("DELETE FROM attendance_records");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Đếm số bản ghi
     */
    public function count(): int
    {
        if (!$this->useDatabase) {
            return 0;
        }

        try {
            $stmt = $this->conn->query("SELECT COUNT(*) FROM attendance_records");
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Lấy lịch sử điểm danh
     */
    public function getHistory(string $date = null): array
    {
        if (!$this->useDatabase) {
            return [];
        }

        try {
            $sql = "SELECT ah.*, ar.username, ar.lastname, ar.firstname 
                    FROM attendance_history ah 
                    JOIN attendance_records ar ON ah.record_id = ar.id";
            
            if ($date) {
                $sql .= " WHERE ah.attendance_date = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$date]);
            } else {
                $sql .= " ORDER BY ah.attendance_date DESC, ar.id ASC";
                $stmt = $this->conn->query($sql);
            }

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Import dữ liệu từ file CSV
     */
    public function importFromCSV(string $filePath): array
    {
        $records = $this->parseCSVFile($filePath);

        if ($this->useDatabase && !empty($records)) {
            // Xóa dữ liệu cũ
            $this->deleteAll();

            // Thêm dữ liệu mới
            foreach ($records as $record) {
                $this->add($record);
            }

            // Refresh để lấy ID mới
            return $this->getAll();
        }

        return $records;
    }

    /**
     * Parse file CSV
     */
    private function parseCSVFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        $records = [];
        $handle = fopen($filePath, 'r');
        
        if (!$handle) {
            return [];
        }

        $isFirstLine = true;
        while (($data = fgetcsv($handle)) !== false) {
            // Skip header
            if ($isFirstLine) {
                $isFirstLine = false;
                continue;
            }

            if (count($data) >= 7) {
                $record = new AttendanceRecord(
                    trim($data[0]), // username
                    trim($data[1]), // password
                    trim($data[2]), // lastname
                    trim($data[3]), // firstname
                    trim($data[4]), // city
                    trim($data[5]), // email
                    trim($data[6])  // course1
                );
                $records[] = $record;
            }
        }

        fclose($handle);
        return $records;
    }
}

class AttendenceManager
{
    private AttendanceRepository $repo;

    public function __construct()
    {
        $this->repo = new AttendanceRepository();
    }

    public function isUsingDatabase(): bool
    {
        return $this->repo->isUsingDatabase();
    }

    public function getRecords(): array
    {
        // Thử lấy từ database trước
        if ($this->repo->isUsingDatabase()) {
            $records = $this->repo->getAll();
            if (!empty($records)) {
                return $records;
            }
        }

        // Fallback: Lấy từ session hoặc file
        if (!empty($_SESSION['attendance_records'])) {
            return $_SESSION['attendance_records'];
        }

        $_SESSION['attendance_records'] = $this->loadAttendanceFromFile();
        return $_SESSION['attendance_records'];
    }

    private function loadAttendanceFromFile(): array
    {
        $filePath = "../65HTTT_Danh_sach_diem_danh.csv";
        if (!file_exists($filePath)) {
            return [];
        }

        $file = fopen($filePath, "r");
        if (!$file) {
            return [];
        }

        $data = fread($file, filesize($filePath));
        fclose($file);
        $lines = explode("\n", $data);

        $records = [];
        $isFirstLine = true;
        $id = 1;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Skip header line
            if ($isFirstLine) {
                $isFirstLine = false;
                continue;
            }

            $parts = explode(",", $line);
            if (count($parts) >= 7) {
                $record = new AttendanceRecord(
                    $parts[0],
                    $parts[1],
                    $parts[2],
                    $parts[3],
                    $parts[4],
                    $parts[5],
                    $parts[6]
                );
                $record->id = $id++;
                $records[] = $record;
            }
        }

        return $records;
    }

    /**
     * Import từ file CSV upload
     */
    public function importFromUploadedFile(array $uploadedFile): array
    {
        $result = ['success' => false, 'message' => '', 'count' => 0];

        if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
            $result['message'] = 'Lỗi upload file!';
            return $result;
        }

        // Kiểm tra loại file
        $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));

        if ($fileExtension !== 'csv') {
            $result['message'] = 'Chỉ chấp nhận file .csv!';
            return $result;
        }

        // Import từ file
        $records = $this->repo->importFromCSV($uploadedFile['tmp_name']);

        if (empty($records)) {
            $result['message'] = 'Không tìm thấy dữ liệu hợp lệ trong file!';
            return $result;
        }

        // Lưu vào session nếu không có database
        if (!$this->repo->isUsingDatabase()) {
            $_SESSION['attendance_records'] = $records;
        }

        $result['success'] = true;
        $result['message'] = 'Import thành công!';
        $result['count'] = count($records);

        return $result;
    }

    /**
     * Cập nhật điểm danh
     */
    public function updateAttendance(array $presentIds): bool
    {
        if ($this->repo->isUsingDatabase()) {
            return $this->repo->updateMultipleAttendance($presentIds);
        }

        // Fallback: Cập nhật session
        if (isset($_SESSION['attendance_records'])) {
            foreach ($_SESSION['attendance_records'] as &$record) {
                $record->isPresent = in_array($record->id, $presentIds);
                $record->attendanceDate = date('Y-m-d');
            }
            return true;
        }

        return false;
    }

    /**
     * Lấy lịch sử điểm danh
     */
    public function getHistory(string $date = null): array
    {
        return $this->repo->getHistory($date);
    }
}