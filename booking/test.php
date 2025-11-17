<?php
// Save this as: C:\xampp\htdocs\sports-management-system\booking\test.php

echo "<h1>Success!</h1>";
echo "<p>PHP is working correctly.</p>";
echo "<p>Current file path: " . __FILE__ . "</p>";
echo "<p>Document root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script name: " . $_SERVER['SCRIPT_NAME'] . "</p>";

// Test database connection
echo "<h2>Testing Database Connection:</h2>";

$conn = new mysqli('127.0.0.1', 'root', '', 'sport_management');

if ($conn->connect_error) {
    echo "<p style='color:red;'>Connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color:green;'>Database connected successfully!</p>";
    
    // Check if bookings table exists
    $result = $conn->query("SHOW TABLES LIKE 'bookings'");
    if ($result->num_rows > 0) {
        echo "<p style='color:green;'>Bookings table exists!</p>";
    } else {
        echo "<p style='color:red;'>Bookings table NOT found!</p>";
    }
}
?>