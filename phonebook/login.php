<?php
session_start();
include('config.php');

// Function to hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Function to verify passwords
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

// Function to display a message and set the flag
function displayMessage($messageKey, $message) {
    $_SESSION[$messageKey] = $message;
    $_SESSION[$messageKey . '_displayed'] = true;
}

// Function to check if a message has been displayed
function isMessageDisplayed($messageKey) {
    return isset($_SESSION[$messageKey . '_displayed']) && $_SESSION[$messageKey . '_displayed'] === true;
}

// Check if the login message has been displayed before
$loginMessageKey = 'login_message';
if (!isMessageDisplayed($loginMessageKey)) {
    $loginMessage = "";

    // Login
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $db->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (verifyPassword($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                // Display login message and set the flag
                displayMessage($loginMessageKey, "Login successful.");
                // Redirect to index.php
                header("Location: index.php");
                exit;
            } else {
                $loginMessage = "Invalid username or password.";
            }
        } else {
            $loginMessage = "Invalid username or password.";
        }
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Login</h2>
<form method="post" action="login.php">
    <label>Username:</label>
    <input type="text" name="username" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <input type="submit" name="login" value="Login">
</form>
<h1><a href="index.php">Sign up</a></h1>
</body>
</html>
