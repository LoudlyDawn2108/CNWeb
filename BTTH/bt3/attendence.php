<?php
// username,password,lastname,firstname,city,email,course1
class AttendanceRecord
{
    public string $username;
    public string $password;
    public string $lastname;
    public string $firstname;
    public string $city;
    public string $email;
    public string $course1;

    public function __construct($username, $password, $lastname, $firstname, $city, $email, $course1)
    {
        $this->username = $username;
        $this->password = $password;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->city = $city;
        $this->email = $email;
        $this->course1 = $course1;
    }
}

class AttendenceManager
{
    public function getRecords(): array
    {
        if (!empty($_SESSION['attendance_records'])) {
            return $_SESSION['attendance_records'];
        }

        $_SESSION['attendance_records'] = $this->loadAttendanceFromFile();
        return $_SESSION['attendance_records'];
    }

    private function loadAttendanceFromFile(): array
    {
        $file = fopen("../65HTTT_Danh_sach_diem_danh.csv", "r") or die("Unable to open file!");
        $data = fread($file, filesize("../65HTTT_Danh_sach_diem_danh.csv"));
        fclose($file);
        $lines = explode("\n", $data);

        $records = [];
        $isFirstLine = true;
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
            if (count($parts) === 7) {
                $record = new AttendanceRecord(
                    $parts[0],
                    $parts[1],
                    $parts[2],
                    $parts[3],
                    $parts[4],
                    $parts[5],
                    $parts[6]
                );
                $records[] = $record;
            }
        }

        return $records;
    }
}