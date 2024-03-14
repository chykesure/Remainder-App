<?php
// Replace these values with your actual database credentials
$host = "localhost";
$username = "root";
$password = "";
$database = "scheduler_db";

// Create a connection to the database
$mysqli = new mysqli($host, $username, $password, $database);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    // Perform a query to check if the email already exists
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM user_login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Respond with success if email does not exist, otherwise, return an error
    if ($count === 0) {
        echo json_encode(array("status" => "success", "message" => "Email does not exist."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Email already exists."));
    }
}

// Close the database connection when done
$mysqli->close();
?>
