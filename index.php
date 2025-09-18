<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

session_start();
require_login();
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - TaskApp</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
        <p>This is your dashboard.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>