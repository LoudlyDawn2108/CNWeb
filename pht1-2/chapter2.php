<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHT Chuong 2 - PHP Can Ban</title>
</head>
<body>

<h1>Ket qua PHP Can Ban</h1>

<?php
// Khai bao bien
$ho_ten = "Nguyen Hong Phuc";
$diem_tb = 10;
$co_di_hoc_chuyen_can = true;
// In ra
echo "Ho ten: $ho_ten";
echo "<br>";
echo "Diem: $diem_tb";
echo "<br>";

echo "Xep loai: ";
if ($diem_tb >= 8 && $co_di_hoc_chuyen_can) {
    echo "Gioi";
} elseif ($diem_tb >= 6.5 && $co_di_hoc_chuyen_can) {
    echo "Kha";
} elseif ($diem_tb >= 5 && $co_di_hoc_chuyen_can) {
    echo "Trung binh";
} else {
    echo "Yeu (Can co gang them!)";
}
echo "<br>";

function chaoMung()
{
    echo "Chuc mung ban da hoan thanh PHT Chuong 2!";
}
chaoMung();

?>

</body>
</html>