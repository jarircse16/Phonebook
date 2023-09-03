<?php
session_start();
include ('config.php');


if (isset($_GET['id'])) {
    $contact_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Check if the contact belongs to the logged-in user
    $sql = "SELECT * FROM contacts WHERE id='$contact_id' AND user_id='$user_id'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        // Display the edit form
        $row = $result->fetch_assoc();

        if (isset($_POST['edit'])) {
            $name = $_POST['name'];
            $number = $_POST['number'];
            $email = $_POST['email'];

            $update_sql = "UPDATE contacts SET name='$name', number='$number', email='$email' WHERE id='$contact_id'";
            $update_result = $db->query($update_sql);

            if ($update_result) {
                header("Location: index.php");
                exit;
            } else {
                echo "Failed to edit contact.";
            }
        }
    } else {
        echo "Contact not found.";
    }
} else {
    echo "Contact ID not provided.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Contact</title>
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
<center><h2>Edit Contact</h2>
<form method="post" action="edit_contact.php?id=<?php echo $contact_id; ?>">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br><br>
    <label>Number:</label>
    <input type="text" name="number" value="<?php echo $row['number']; ?>" required><br><br>
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $row['email']; ?>"><br><br>
    <button class="my-button" type="submit" name="edit" value="Edit Contact">Edit Contact</button><br><br>
    <button class="my-button"><a href="index.php">Back</button>
</form>
</body>
</html>
