<?php
$conn = new mysqli("localhost", "root", "", "dynamic_form_builder");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
