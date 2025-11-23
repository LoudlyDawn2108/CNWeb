<?php
// php: debug_session.php
session_start();

// show what PHP is using
echo 'session id: ' . session_id() . PHP_EOL;
echo 'session name: ' . session_name() . PHP_EOL;

// show what cookie client sent (if any)
$cookieName = session_name();
echo 'cookie value sent by client: ' . ($_COOKIE[$cookieName] ?? '(none)') . PHP_EOL;
var_dump($_SESSION);
