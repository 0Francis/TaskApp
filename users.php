<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['DB_HOST'];
$username   = $_ENV['DB_USER'];
$password   = $_ENV['DB_PASS'];
$port       = $_ENV['DB_PORT']; 
$dbname     = $_ENV['DB_NAME'];

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
echo "✅ Connected successfully to the '" . $dbname . "' database.<br><br>";

// --- Fetch Users ---
$sql = "SELECT id, name, email, reg_date FROM app_users ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Users</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #333; }
        ol { list-style-type: decimal; }
        li { margin-bottom: 8px; }
        .empty { color: gray; }
    </style>
</head>
<body>
    <h1>List of Registered Users</h1>

    <ol>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li><strong>" . htmlspecialchars($row["name"]) . "</strong> (" 
                     . htmlspecialchars($row["email"]) . ") - Registered: " 
                     . htmlspecialchars($row["reg_date"]) . "</li>";
            }
        } else {
            echo "<li class='empty'>No users found.</li>";
        }
        $conn->close();
        ?>
    </ol>
</body>
</html>
