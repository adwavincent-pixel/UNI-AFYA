<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];

$host = "localhost";
$dbuser = "root";
$dbpassword = "";
$database = "uni_afya";

$conn = mysqli_connect($host, $dbuser, $dbpassword, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue_type = mysqli_real_escape_string($conn, $_POST['issue_type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $preferred_date = mysqli_real_escape_string($conn, $_POST['preferred_date']);
    $preferred_time = mysqli_real_escape_string($conn, $_POST['preferred_time']);
    $anonymous = isset($_POST['anonymous']) ? 'yes' : 'no';

    $sql = "INSERT INTO counselling (student_id, student_name, issue_type, description, preferred_date, preferred_time, anonymous, status) 
            VALUES ('$student_id', '$student_name', '$issue_type', '$description', '$preferred_date', '$preferred_time', '$anonymous', 'pending')";

    if (mysqli_query($conn, $sql)) {
        $success = "Counselling request submitted successfully! Your session will be confirmed soon.";
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
    <title>UNI-AFYA | Counselling</title>
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
            height: 120px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #006400;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input {
            width: auto;
        }

        .checkbox-group label {
            margin: 0;
            font-weight: normal;
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

        .info-box {
            background-color: #e8f5e9;
            border-left: 4px solid #006400;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }

        .info-box p {
            color: #333;
            font-size: 14px;
        }

        .privacy-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }

        .privacy-box p {
            color: #333;
            font-size: 14px;
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
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <h2>💬 Counselling Request</h2>
    </div>

    <!-- Form -->
    <div class="form-container">

        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

        <div class="info-box">
            <p>Request a confidential counselling session with our qualified counsellors.
            Your information is kept private and secure.</p>
        </div>

        <div class="privacy-box">
            <p>🔒 <strong>Privacy Notice:</strong> You can choose to remain anonymous.
            Your counsellor will only see your student ID, not your name.</p>
        </div>

        <?php if (isset($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>

        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <form action="counselling.php" method="POST">
            <div class="form-group">
                <label>Type of Issue</label>
                <select name="issue_type" required>
                    <option value="">Select issue type</option>
                    <option value="stress">Stress and Anxiety</option>
                    <option value="depression">Depression</option>
                    <option value="relationships">Relationship Issues</option>
                    <option value="family">Family Problems</option>
                    <option value="academic">Academic Pressure</option>
                    <option value="financial">Financial Stress</option>
                    <option value="grief">Grief and Loss</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Describe your situation</label>
                <textarea name="description" placeholder="Share what you are going through. This is confidential." required></textarea>
            </div>
            <div class="form-group">
                <label>Preferred Date</label>
                <input type="date" name="preferred_date" required>
            </div>
            <div class="form-group">
                <label>Preferred Time</label>
                <select name="preferred_time" required>
                    <option value="">Select preferred time</option>
                    <option value="8:00 AM - 10:00 AM">8:00 AM - 10:00 AM</option>
                    <option value="10:00 AM - 12:00 PM">10:00 AM - 12:00 PM</option>
                    <option value="2:00 PM - 4:00 PM">2:00 PM - 4:00 PM</option>
                    <option value="4:00 PM - 6:00 PM">4:00 PM - 6:00 PM</option>
                </select>
            </div>
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="anonymous" id="anonymous">
                    <label for="anonymous">I want to remain anonymous</label>
                </div>
            </div>
            <button type="submit" class="btn">Submit Counselling Request</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

</body>
</html>