<?php
$servername = "localhost";
$username = "sadeeq";
$password = "09030037973Ab,";
$dbname = "hamzury";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$first_name = $_POST['first-name'];
$last_name = $_POST['last-name'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$interview_location = $_POST['interview_location'];
$gender = $_POST['gender'];
$school = $_POST['school'];
$department = $_POST['department'];
$course_of_study = $_POST['course_of_study'];
$registration_number = $_POST['registration_number'];
$internship_start_date = $_POST['internship_start_date'];
$internship_end_date = $_POST['internship_end_date'];
$programming_experience = $_POST['programming_experience'];
$internship_duration = $_POST['internship_duration'];
$desired_track = $_POST['desired_track'];
$cover_letter = $_POST['cover_letter'];

// Prepare an SQL statement
$stmt = $conn->prepare("INSERT INTO form_responses (first_name, last_name, email, phone_number, interview_location, gender, school, department, course_of_study, registration_number, internship_start_date, internship_end_date, programming_experience, internship_duration, desired_track, cover_letter) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssssssss", $first_name, $last_name, $email, $phone_number, $interview_location, $gender, $school, $department, $course_of_study, $registration_number, $internship_start_date, $internship_end_date, $programming_experience, $internship_duration, $desired_track, $cover_letter);

// Execute the statement
if ($stmt->execute()) {
    // If the record was created successfully, return a JSON response with success=true
    echo json_encode(['success' => true]);
} else {
    // If there was an error, return a JSON response with success=false and the error message
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>