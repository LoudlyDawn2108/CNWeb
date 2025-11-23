<?php

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit;
}

$loggedInUser = $_SESSION['username'];
echo "<h1>Chào mừng trở lại, $loggedInUser!</h1>";
echo "<p>Bạn đã đăng nhập thành công.</p>";
// TODO 5: (Tạm thời) Tạo 1 link để "Đăng xuất" (chỉ là quay về login.html)
echo '<a href="login.html">Đăng xuất (Tạm thời)</a>';
