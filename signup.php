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

// Initialize variables and set to empty values
$name = $email = $user_password = $phone = $address = $city = $state = $country = $postal_code = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = sanitizeInput($_POST["name"]);
    $email = sanitizeInput($_POST["email"]);
    $user_password = sanitizeInput($_POST["password"]);
    $phone = sanitizeInput($_POST["phone"]);
    $address = sanitizeInput($_POST["address"]);
    $city = sanitizeInput($_POST["city"]);
    $state = sanitizeInput($_POST["state"]);
    $country = sanitizeInput($_POST["country"]);
    $postal_code = sanitizeInput($_POST["postal_code"]);

    // Perform validations
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (strlen($user_password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }
    if (!preg_match("/^[0-9]{7,15}$/", $phone)) {
        $errors[] = "Invalid phone number format";
    }
    if (!preg_match("/^[a-zA-Z0-9]{3,10}$/", $postal_code)) {
        $errors[] = "Invalid postal code format";
    }

    // If there are no errors, insert data into the database
    if (empty($errors)) {
        $hashed_password = password_hash($user_password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (name, email, password, phone, address, city, state, country, postal_code) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $name, $email, $hashed_password, $phone, $address, $city, $state, $country, $postal_code);

        if ($stmt->execute()) {
            echo "Signup successful! Redirecting to login page...";
            echo "<script>setTimeout(function() { window.location.href = 'login.html'; }, 2000);</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$conn->close();
?>
