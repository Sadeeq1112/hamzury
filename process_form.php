<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to log messages
function log_message($message) {
    error_log($message);  // Server-side logging
}

// Database connection details
$servername = "localhost";
$username = "sadeeq";
$password = "09030037973Ab,";
$dbname = "sadeeq";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    log_message("Connection failed: " . $conn->connect_error);
    die("error");
}

log_message("Database connection successful");

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    log_message("Form submitted");

    // Sanitize and validate input
    $full_name = sanitize_input($_POST["full_name"]);
    $dob = sanitize_input($_POST["dob"]); // New field
    $age = filter_var($_POST["age"], FILTER_VALIDATE_INT);
    $phone = sanitize_input($_POST["phone"]);
    $address = sanitize_input($_POST["address"]);
    $guarantor_name = sanitize_input($_POST["guarantor_name"]);
    $guarantor_email = filter_var($_POST["guarantor_email"], FILTER_VALIDATE_EMAIL);
    $guarantor_phone = sanitize_input($_POST["guarantor_phone"]);
    $guarantor_address = sanitize_input($_POST["guarantor_address"]);
    $relationship = sanitize_input($_POST["relationship"]);

    log_message("Input sanitized");

    // Validate photo upload
    $target_dir = __DIR__ . "/uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    log_message("Target file: " . $target_file);

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if($check !== false) {
        log_message("File is an image - " . $check["mime"] . ".");
        $uploadOk = 1;
    } else {
        log_message("File is not an image.");
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["photo"]["size"] > 500000) {
        log_message("Sorry, your file is too large.");
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        log_message("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        $uploadOk = 0;
    }

    // If everything is ok, try to upload file and insert data
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            log_message("The file ". basename( $_FILES["photo"]["name"]). " has been uploaded.");
            
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO students (full_name, dob, age, photo, phone, address, guarantor_name, guarantor_email, guarantor_phone, guarantor_address, relationship) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt === false) {
                log_message("Prepare failed: " . $conn->error);
                echo "error";
            } else {
                $stmt->bind_param("ssissssssss", $full_name, $dob, $age, $target_file, $phone, $address, $guarantor_name, $guarantor_email, $guarantor_phone, $guarantor_address, $relationship);

                // Execute the statement
                if ($stmt->execute()) {
                    log_message("New record created successfully");
                    echo "success";
                } else {
                    log_message("Error: " . $stmt->error);
                    echo "error";
                }

                $stmt->close();
            }
        } else {
            log_message("Sorry, there was an error uploading your file.");
            echo "error";
        }
    } else {
        echo "error";
    }

    $conn->close();
    log_message("Database connection closed");
} else {
    echo "error";
}
?>