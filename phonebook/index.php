<?php
session_start();
include ('config.php');

if (isset($_SESSION['login_message'])) {
    echo '<script>alert("' . $_SESSION['login_message'] . '");</script>';
    unset($_SESSION['login_message']); // Remove the message to avoid displaying it again
}
// Function to hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Function to verify passwords
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

// Signup
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = hashPassword($_POST['password']);

    // Check if the username already exists
    $checkSql = "SELECT * FROM users WHERE username='$username'";
    $checkResult = $db->query($checkSql);

    if ($checkResult === false) {
        die("Error checking username: " . mysqli_error($db));
    }

    if ($checkResult->num_rows > 0) {
        $_SESSION['signup_message'] = "Username already exists. Please choose a different username.";
    } else {
        // Insert the new user if the username doesn't exist
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        $result = $db->query($sql);

        if ($result === false) {
            die("Error inserting user: " . mysqli_error($db));
        }

        if ($result) {
            $_SESSION['signup_message'] = "Signup successful. Login below";
        } else {
            $_SESSION['signup_message'] = "Signup Failed";
        }
    }
    header("Location: index.php"); // Redirect back to the signup page with a message
    exit(); // Terminate script execution
}



// Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (verifyPassword($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id']; // Set user_id in the session
            $_SESSION['username'] = $row['username']; // Set username in the session
        } else {
            $error_message = "Invalid username or password.";
            echo "<script>alert('Invalid username or password.')</script>"; // Send the error message to JavaScript
        }
    } else {
        $error_message = "Invalid username or password.";
        echo "<script>alert('Invalid username or password.')</script>"; // Send the error message to JavaScript
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
    <title>Phonebook App</title>
    <style>
      .gradient-box {
      background-image: linear-gradient(90deg, #020024 0%, #090979 35%, #00d4ff 100%);
    }
    /* Define the default button style */
    .my-button {
        padding: 10px 20px;
        background-color: #fff; /* Change the background color to white */
        color: #0074D9; /* Change the text color to a different color */
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease; /* Add a smooth transition */
    }

    /* Define the hover effect */
    .my-button:hover {
        background-color: #0056b3; /* Change the background color on hover */
        color: #fff; /* Change the text color on hover */
    }
    .red-link {
        color: #FF4136;
    }
    </style>

</head>
<body class="gradient-box">
<?php if (isset($_SESSION['user_id'])) { ?>
    <center><h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
    <!-- View Contacts -->
    <h2>Your Contacts</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Number</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM contacts WHERE user_id='$user_id'";
        $result = $db->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['number'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td><button><a href='edit_contact.php?id=" . $row['id'] . "'>Edit</a></button> | <button><a href='delete_contact.php?id=" . $row['id'] . "'>Delete</a></button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- Add Contact Form -->
    <h2>Add Contact</h2>
    <form method="post" action="add_contact.php">
        <label>Name:</label>
        <input type="text" name="name" required><br><br>
        <label>Number:</label>
        <input type="text" name="number" required><br><br>
        <label>Email:</label>
        <input type="email" name="email"><br><br>
        <button class="my-button" type="submit" name="add" value="Add Contact">Add Contact</button>
    </form>
    <br>
    <button class="my-button"><a href="export_contacts.php">Export Contacts to CSV</a></button><br><br>
    <button class="my-button"><a href="import_contacts.php">Import Contacts from CSV</a></button><br><br>



    <!-- Logout -->
    <button class="my-button"><a href="logout.php">Logout</a></button>
<?php } else { ?>

  <center><h2>Welcome To Phonebook</h2></center><br>
    <!-- Signup Form -->
  <center><h2>Signup</h2>
    <form method="post" action="index.php">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <button class="my-button" type="submit" name="signup" value="Signup">Signup</button>
    </form><br>
    <!-- Login Form -->
    <h2>Login</h2>
    <form method="post" action="index.php">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <button class="my-button" type="submit" name="login" value="Login">Login</button>
    </form>
<?php } ?>
</center>
</body>
</html>
