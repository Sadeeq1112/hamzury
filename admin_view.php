<?php
// Place this at the very top of admin_view.php

session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If not logged in, check if login form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
        // Check credentials (replace with your own secure authentication method)
        if ($_POST['username'] === 'admin' && $_POST['password'] === '5544332211') {
            $_SESSION['admin_logged_in'] = true;
        } else {
            echo "Invalid credentials";
            exit();
        }
    } else {
        // If not logged in and no form submitted, show login form
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login</title>
            <style>
                body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f0f0f0; }
                form { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                input { display: block; margin: 10px 0; padding: 5px; width: 200px; }
                input[type="submit"] { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
            </style>
        </head>
        <body>
            <form method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login">
            </form>
        </body>
        </html>
        <?php
        exit();
    }
}

// Rest of your admin_view.php code goes here


// Database connection details
$servername = "localhost";
$username = "hamzuryc_admin";
$password = "09030037973Ab,";
$dbname = "hamzuryc_sadeeq";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to export data as CSV
function export_csv($data) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="student_data.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Output the column headings
    fputcsv($output, array('ID', 'Full Name', 'Age', 'Phone', 'Address', 'Guarantor Name', 'Guarantor Email', 'Guarantor Phone', 'Guarantor Address', 'Relationship', 'Registration Date'));
    
    // Output the data
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit();
}

// Fetch data from database
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Check if export button is clicked
if(isset($_POST['export'])) {
    export_csv($data);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Data - Admin View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .export-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .export-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Data - Admin </h2>
        <form method="post">
            <input type="submit" name="export" value="Export to CSV" class="export-btn">
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Age</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Guarantor Name</th>
                <th>Guarantor Email</th>
                <th>Guarantor Phone</th>
                <th>Guarantor Address</th>
                <th>Relationship</th>
                <th>Registration Date</th>
            </tr>
            <?php foreach ($data as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['age']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['guarantor_name']); ?></td>
                <td><?php echo htmlspecialchars($row['guarantor_email']); ?></td>
                <td><?php echo htmlspecialchars($row['guarantor_phone']); ?></td>
                <td><?php echo htmlspecialchars($row['guarantor_address']); ?></td>
                <td><?php echo htmlspecialchars($row['relationship']); ?></td>
                <td><?php echo htmlspecialchars($row['registration_date']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>