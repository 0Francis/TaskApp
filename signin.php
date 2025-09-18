<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

session_start();
redirect_if_logged_in();

$email = $password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Valid email is required.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $name, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                header("Location: index.php");
                exit();
            } else {
                $errors['general'] = "Incorrect email or password.";
            }
        } else {
            $errors['general'] = "Incorrect email or password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign In - TaskApp</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <div class="form-container">
        <h2>Sign In</h2>
        <?php if (!empty($errors['general'])): ?>
            <div class="error"><?= $errors['general'] ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required />
            <small class="error"><?= $errors['email'] ?? '' ?></small>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
            <small class="error"><?= $errors['password'] ?? '' ?></small>

            <button type="submit">Sign In</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html>