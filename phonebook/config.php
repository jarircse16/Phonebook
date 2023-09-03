<?php

$db = new mysqli('localhost', 'root', '', 'phonebook');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

 ?>
