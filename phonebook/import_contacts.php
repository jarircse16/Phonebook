<?php
session_start();
include('config.php');

if (isset($_POST['import'])) {
    $user_id = $_SESSION['user_id'];
    $csv_file = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($csv_file, 'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $name = $data[0];
            $number = $data[1];
            $email = $data[2];

            // Insert the contact into the database
            $sql = "INSERT INTO contacts (user_id, name, number, email) VALUES ('$user_id', '$name', '$number', '$email')";
            $db->query($sql);
        }

        fclose($handle);
        echo 'Contacts imported successfully.';
    } else {
        echo 'Error reading the CSV file.';
    }
}
?>

<form method="post" action="" enctype="multipart/form-data">
    <label>Choose a CSV file to import:</label>
    <input type="file" name="csv_file" required>
    <input type="submit" name="import" value="Import Contacts">
</form>

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
<button class="my-button"><a href="index.php">Go Back</a></button>
</body>
</html>
