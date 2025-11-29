<?php

class Database
{
    private string $host = "localhoat";
    private string $db = 'QLSV';
    private string $user = 'sa';
    private string $pass = 'YourStrong!Passw0rd';
    private string $charset = 'utf8mb4';
    public ?PDO $pdo = null;

    public function __construct()
    {
        $dsn = "mssql:host=$this->host;dbname=$this->db;charset=$this->charset";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}


/*
 * create table dbo.SINHVIEN
(
    Masv    varchar(10) not null
        primary key,
    Hosv    varchar(50),
    Tensv   varchar(20),
    Nssv    date,
    Dcsv    varchar(100),
    Loptr   bit,
    Malop   varchar(10)
        references dbo.LOP,
    Diem_TK float
)
 */
class SinhVien {
    private string $masv;
    private string $hosv;
    private string $tensv;
    private string $nssv;
    private string $dcsv;
    private bool $loptr;
    private string $malop;
    private float $diem_tk;

    public function __construct(string $masv, string $hosv, string $tensv, string $nssv, string $dcsv, bool $loptr, string $malop, float $diem_tk)
    {
        $this->masv = $masv;
        $this->hosv = $hosv;
        $this->tensv = $tensv;
        $this->nssv = $nssv;
        $this->dcsv = $dcsv;
        $this->loptr = $loptr;
        $this->malop = $malop;
        $this->diem_tk = $diem_tk;
    }

    public function getMasv(): string
    {
        return $this->masv;
    }

    public function setMasv(string $masv): void
    {
        $this->masv = $masv;
    }

    public function getHosv(): string
    {
        return $this->hosv;
    }

    public function setHosv(string $hosv): void
    {
        $this->hosv = $hosv;
    }

    public function getTensv(): string
    {
        return $this->tensv;
    }

    public function setTensv(string $tensv): void
    {
        $this->tensv = $tensv;
    }

    public function getNssv(): string
    {
        return $this->nssv;
    }

    public function setNssv(string $nssv): void
    {
        $this->nssv = $nssv;
    }

    public function getDcsv(): string
    {
        return $this->dcsv;
    }

    public function setDcsv(string $dcsv): void
    {
        $this->dcsv = $dcsv;
    }

    public function isLoptr(): bool
    {
        return $this->loptr;
    }

    public function setLoptr(bool $loptr): void
    {
        $this->loptr = $loptr;
    }

    public function getMalop(): string
    {
        return $this->malop;
    }

    public function setMalop(string $malop): void
    {
        $this->malop = $malop;
    }

    public function getDiemTk(): float
    {
        return $this->diem_tk;
    }

    public function setDiemTk(float $diem_tk): void
    {
        $this->diem_tk = $diem_tk;
    }
}

class SinhVienManager {
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAllSinhVien(): array
    {
        $stmt = $this->db->pdo->query("SELECT * FROM SINHVIEN");
        $sinhviens = [];
        while ($row = $stmt->fetch()) {
            $sinhvien = new SinhVien(
                $row['Masv'],
                $row['Hosv'],
                $row['Tensv'],
                $row['Nssv'],
                $row['Dcsv'],
                (bool)$row['Loptr'],
                $row['Malop'],
                (float)$row['Diem_TK']
            );
            $sinhviens[] = $sinhvien;
        }
        return $sinhviens;
    }

    public function updateSinhVien(SinhVien $sinhvien): bool
    {
        $stmt = $this->db->pdo->prepare("UPDATE SINHVIEN SET Hosv = ?, Tensv = ?, Nssv = ?, Dcsv = ?, Loptr = ?, Malop = ?, Diem_TK = ? WHERE Masv = ?");
        return $stmt->execute([
            $sinhvien->getHosv(),
            $sinhvien->getTensv(),
            $sinhvien->getNssv(),
            $sinhvien->getDcsv(),
            $sinhvien->isLoptr(),
            $sinhvien->getMalop(),
            $sinhvien->getDiemTk(),
            $sinhvien->getMasv()
        ]);
    }

    public function deleteSinhVien(SinhVien $sinhvien): bool
    {
        $stmt = $this->db->pdo->prepare("DELETE FROM SINHVIEN WHERE Masv = ?");
        return $stmt->execute([$sinhvien->getMasv()]);
    }
}


