<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "web_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are set
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = sanitizeInput($_POST["email"]);
        $password = sanitizeInput($_POST["password"]);

        // Code to authenticate user
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            echo "Login successful!";
            // Redirect to user dashboard or perform other actions
        } else {
            echo "Invalid email or password";
        }

        $stmt->close();
    } else {
        echo "Email or password not provided";
    }
}

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Close the connection
$conn->close();
?>
