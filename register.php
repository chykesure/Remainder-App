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
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST["pass"], PASSWORD_DEFAULT);
    $confirmPassword = password_hash($_POST["conpass"], PASSWORD_DEFAULT);

    // Log the received data (for debugging purposes)
    error_log("Received data: Name - $name, Email - $email, Password - $password, Confirm Password - $confirmPassword");

    // Check if password and confirm password match
    if ($_POST["pass"] !== $_POST["conpass"]) {
        echo json_encode(array("status" => "error", "message" => "Password and Confirm Password do not match."));
        exit;
    }

    // Perform database operations using prepared statements
    $stmt = $mysqli->prepare("INSERT INTO user_login (name, email, password) VALUES (?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            // Registration successful
            echo json_encode(array("status" => "success", "message" => "Registration successful! Please Login."));
        } else {
            // Registration failed
            echo json_encode(array("status" => "error", "message" => "Error: Unable to execute query."));
        }

        $stmt->close();
    } else {
        // Statement preparation failed
        echo json_encode(array("status" => "error", "message" => "Error: Unable to prepare statement."));
    }
}

// Close the database connection when done
$mysqli->close();
?>
