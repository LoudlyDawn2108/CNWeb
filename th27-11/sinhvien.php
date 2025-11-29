<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<?php
require_once "database.php";
$db = new Database();
$svm = new SinhVienManager($db);
$students = $svm->getAllSinhVien();
?>
<table>
    <thead>
    <tr>
        <th>MSSV</th>
        <th>Họ tên</th>
        <th>Ngày sinh</th>
        <th>Nơi sinh</th>
        <th>Địa chỉ SV</th>
        <th>Lớp trưởng</th>
        <th>Mã lớp</th>
        <th>Điểm TK</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($students as $student): ?>
        <tr>
            <td><?php echo htmlspecialchars($student->getMasv()); ?></td>
            <td><?php echo htmlspecialchars($student->getHosv() . ' ' . $student->getTensv()); ?></td>
            <td><?php echo htmlspecialchars($student->getNssv()); ?></td>
            <td><?php echo htmlspecialchars($student->getDcsv()); ?></td>
            <td><?php echo $student->isLoptr() ? 'Yes' : 'No'; ?></td>
            <td><?php echo htmlspecialchars($student->getMalop()); ?></td>
            <td><?php echo htmlspecialchars($student->getDiemTk()); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>