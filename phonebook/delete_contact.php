<?php
session_start();
include('config.php');

if (isset($_GET['id'])) {
    $contact_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Check if the contact belongs to the logged-in user
    $sql = "SELECT * FROM contacts WHERE id='$contact_id' AND user_id='$user_id'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        // Delete the contact
        $delete_sql = "DELETE FROM contacts WHERE id='$contact_id'";
        $delete_result = $db->query($delete_sql);

        if ($delete_result) {
            header("Location: index.php");
            exit;
        } else {
            echo "Failed to delete contact.";
        }
    } else {
        echo "Contact not found.";
    }
} else {
    echo "Contact ID not provided.";
}
?>
