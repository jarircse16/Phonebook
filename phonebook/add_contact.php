<?php
session_start();
include ('config.php');


if (isset($_POST['add'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];

    $sql = "INSERT INTO contacts (user_id, name, number, email) VALUES ('$user_id', '$name', '$number', '$email')";
    $result = $db->query($sql);

    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        echo "Failed to add contact.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Contact</title>
</head>
<body>
<h2>Add Contact</h2>
<form method="post" action="add_contact.php">
    <label>Name:</label>
    <input type="text" name="name" required><br>
    <label>Number:</label>
    <input type="text" name="number" required><br>
    <label>Email:</label>
    <input type="email" name="email"><br>
    <input type="submit" name="add" value="Add Contact">
</form>
</body>
</html>
