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

// Get health reports
$health_sql = "SELECT * FROM health_reports WHERE user_id='$student_id' AND user_type='student' ORDER BY id DESC";
$health_result = mysqli_query($conn, $health_sql);

// Get guidance requests
$guidance_sql = "SELECT * FROM guidance WHERE student_id='$student_id' ORDER BY id DESC";
$guidance_result = mysqli_query($conn, $guidance_sql);

// Get counselling requests
$counselling_sql = "SELECT * FROM counselling WHERE student_id='$student_id' ORDER BY id DESC";
$counselling_result = mysqli_query($conn, $counselling_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNI-AFYA | My History</title>
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

        .container {
            padding: 30px;
            max-width: 1100px;
            margin: 0 auto;
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

        .section {
            margin-bottom: 40px;
        }

        .section h3 {
            color: #006400;
            margin-bottom: 15px;
            font-size: 20px;
            border-bottom: 2px solid #006400;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        th {
            background-color: #006400;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #333;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-attended {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .no-records {
            background-color: white;
            padding: 20px;
            text-align: center;
            color: #555;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        <h2>📋 My History</h2>
    </div>

    <!-- Content -->
    <div class="container">

        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

        <!-- Health Reports -->
        <div class="section">
            <h3>🏥 My Health Reports</h3>
            <?php if (mysqli_num_rows($health_result) > 0) { ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>Complaint</th>
                    <th>Severity</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                </tr>
                <?php
                $count = 1;
                while ($row = mysqli_fetch_assoc($health_result)) {
                ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo $row['complaint']; ?></td>
                    <td><?php echo ucfirst($row['severity']); ?></td>
                    <td><?php echo $row['appointment_date']; ?></td>
                    <td>
                        <span class="status-<?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <?php } else { ?>
                <div class="no-records">No health reports found.</div>
            <?php } ?>
        </div>

        <!-- Guidance Requests -->
        <div class="section">
            <h3>🎓 My Guidance Requests</h3>
            <?php if (mysqli_num_rows($guidance_result) > 0) { ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Preferred Date</th>
                    <th>Status</th>
                </tr>
                <?php
                $count = 1;
                while ($row = mysqli_fetch_assoc($guidance_result)) {
                ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo ucfirst($row['guidance_type']); ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['preferred_date']; ?></td>
                    <td>
                        <span class="status-<?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <?php } else { ?>
                <div class="no-records">No guidance requests found.</div>
            <?php } ?>
        </div>

        <!-- Counselling Requests -->
        <div class="section">
            <h3>💬 My Counselling Requests</h3>
            <?php if (mysqli_num_rows($counselling_result) > 0) { ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>Issue Type</th>
                    <th>Preferred Date</th>
                    <th>Preferred Time</th>
                    <th>Anonymous</th>
                    <th>Status</th>
                </tr>
                <?php
                $count = 1;
                while ($row = mysqli_fetch_assoc($counselling_result)) {
                ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo ucfirst($row['issue_type']); ?></td>
                    <td><?php echo $row['preferred_date']; ?></td>
                    <td><?php echo $row['preferred_time']; ?></td>
                    <td><?php echo ucfirst($row['anonymous']); ?></td>
                    <td>
                        <span class="status-<?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <?php } else { ?>
                <div class="no-records">No counselling requests found.</div>
            <?php } ?>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

</body>
</html>