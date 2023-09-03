<?php

$db = new mysqli('sql207.byethost17.com', 'b17_34928775', 'xD123@xD', 'b17_34928775_phonebook');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

 ?>
