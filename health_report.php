<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['student_id']) && !isset($_SESSION['community_id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "uni_afya";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get user details
if (isset($_SESSION['student_id'])) {
    $user_id = $_SESSION['student_id'];
    $user_name = $_SESSION['student_name'];
    $user_type = 'student';
    $dashboard = 'dashboard.php';
} else {
    $user_id = $_SESSION['community_id'];
    $user_name = $_SESSION['community_name'];
    $user_type = 'community';
    $dashboard = 'community_dashboard.php';
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint = $_POST['complaint'];
    $symptoms = $_POST['symptoms'];
    $duration = $_POST['duration'];
    $severity = $_POST['severity'];
    $appointment_date = $_POST['appointment_date'];

    $sql = "INSERT INTO health_reports (user_id, user_type, complaint, symptoms, duration, severity, appointment_date, status) 
            VALUES ('$user_id', '$user_type', '$complaint', '$symptoms', '$duration', '$severity', '$appointment_date', 'pending')";

    if (mysqli_query($conn, $sql)) {
        $success = "Health report submitted successfully! Your appointment is booked for $appointment_date.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNI-AFYA | Report Health Issue</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f0f4f8;
        }

        .navbar {
            background-color: #006400;
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 24px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }

        .page-header {
            background-color: #006400;
            color: white;
            padding: 20px 30px;
            text-align: center;
        }

        .page-header h2 {
            font-size: 24px;
        }

        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-size: 14px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #006400;
        }

        .btn {
            background-color: #006400;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #004d00;
        }

        .back-btn {
            background-color: #555;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .logout-btn {
            background-color: #cc0000;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .footer {
            background-color: #006400;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <div class="navbar">
        <h1>UNI-AFYA</h1>
        <div>
            <a href="<?php echo $dashboard; ?>">Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <h2>🏥 Report Health Issue</h2>
    </div>

    <!-- Form -->
    <div class="form-container">

        <a href="<?php echo $dashboard; ?>" class="back-btn">← Back to Dashboard</a>

        <?php if (isset($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>

        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <form action="health_report.php" method="POST">
            <div class="form-group">
                <label>Main Complaint</label>
                <input type="text" name="complaint" placeholder="e.g. Headache, Fever, Stomach pain" required>
            </div>
            <div class="form-group">
                <label>Symptoms</label>
                <textarea name="symptoms" placeholder="Describe your symptoms in detail"></textarea>
            </div>
            <div class="form-group">
                <label>How long have you had this problem?</label>
                <select name="duration">
                    <option value="today">Started today</option>
                    <option value="2-3 days">2 to 3 days</option>
                    <option value="1 week">About 1 week</option>
                    <option value="2 weeks">About 2 weeks</option>
                    <option value="1 month">About 1 month</option>
                    <option value="more than 1 month">More than 1 month</option>
                </select>
            </div>
            <div class="form-group">
                <label>Severity</label>
                <select name="severity">
                    <option value="mild">Mild - I can manage</option>
                    <option value="moderate">Moderate - Affecting daily activities</option>
                    <option value="severe">Severe - Need urgent attention</option>
                </select>
            </div>
            <div class="form-group">
                <label>Preferred Appointment Date</label>
                <input type="date" name="appointment_date" required>
            </div>
            <button type="submit" class="btn">Submit Health Report</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

</body>
</html>