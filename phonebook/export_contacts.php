<?php
session_start(); // Start the session
include('config.php');

// Fetch contacts from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, number, email FROM contacts WHERE user_id='$user_id'";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    $csv_filename = 'contacts.csv';

    // Create and open the CSV file
    $csv_file = fopen($csv_filename, 'w');

    // Add a header row to the CSV file
    fputcsv($csv_file, array('Name', 'Number', 'Email'));

    // Iterate through the database results and add them to the CSV file
    while ($row = $result->fetch_assoc()) {
        fputcsv($csv_file, array($row['name'], $row['number'], $row['email']));
    }

    // Close the CSV file
    fclose($csv_file);

    // Set headers for download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $csv_filename . '"');

    // Output the CSV file to the browser
    readfile($csv_filename);
} else {
    echo 'No contacts found to export.';
}
?>
