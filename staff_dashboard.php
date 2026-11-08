<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

$staff_name = $_SESSION['staff_name'];
$staff_role = $_SESSION['staff_role'];

$host = "localhost";
$dbuser = "root";
$dbpassword = "";
$database = "uni_afya";

$conn = mysqli_connect($host, $dbuser, $dbpassword, $database);

$reports_sql = "SELECT COUNT(*) as total FROM health_reports WHERE status='pending'";
$reports_result = mysqli_query($conn, $reports_sql);
$reports_count = mysqli_fetch_assoc($reports_result)['total'];

$students_sql = "SELECT COUNT(*) as total FROM students";
$students_result = mysqli_query($conn, $students_sql);
$students_count = mysqli_fetch_assoc($students_result)['total'];

$community_sql = "SELECT COUNT(*) as total FROM community";
$community_result = mysqli_query($conn, $community_sql);
$community_count = mysqli_fetch_assoc($community_result)['total'];

$guidance_sql = "SELECT COUNT(*) as total FROM guidance WHERE status='pending'";
$guidance_result = mysqli_query($conn, $guidance_sql);
$guidance_count = mysqli_fetch_assoc($guidance_result)['total'];

$counselling_sql = "SELECT COUNT(*) as total FROM counselling WHERE status='pending'";
$counselling_result = mysqli_query($conn, $counselling_sql);
$counselling_count = mysqli_fetch_assoc($counselling_result)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNI-AFYA | Staff Dashboard</title>
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

        .welcome-banner {
            background-color: #006400;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .welcome-banner h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .welcome-banner p {
            font-size: 16px;
            opacity: 0.9;
            text-transform: capitalize;
        }

        .dashboard-container {
            padding: 40px 30px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            width: 180px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stat-card h2 {
            font-size: 40px;
            color: #006400;
        }

        .stat-card p {
            color: #555;
            font-size: 13px;
            margin-top: 5px;
        }

        .section-title {
            color: #006400;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            width: 220px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .card h3 {
            color: #006400;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .card p {
            color: #555;
            font-size: 13px;
        }

        .card a {
            text-decoration: none;
            color: inherit;
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

    <div class="navbar">
        <h1>UNI-AFYA</h1>
        <div>
            <a href="staff_dashboard.php">Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="welcome-banner">
        <h2>Welcome, <?php echo $staff_name; ?>!</h2>
        <p>Role: <?php echo $staff_role; ?></p>
    </div>

    <div class="dashboard-container">

        <div class="stats">
            <div class="stat-card">
                <h2><?php echo $reports_count; ?></h2>
                <p>Pending Health Reports</p>
            </div>
            <div class="stat-card">
                <h2><?php echo $guidance_count; ?></h2>
                <p>Pending Guidance</p>
            </div>
            <div class="stat-card">
                <h2><?php echo $counselling_count; ?></h2>
                <p>Pending Counselling</p>
            </div>
            <div class="stat-card">
                <h2><?php echo $students_count; ?></h2>
                <p>Total Students</p>
            </div>
            <div class="stat-card">
                <h2><?php echo $community_count; ?></h2>
                <p>Community Members</p>
            </div>
        </div>

        <h3 class="section-title">Quick Actions</h3>
        <div class="cards">

            <div class="card">
                <a href="view_reports.php">
                    <div class="card-icon">🏥</div>
                    <h3>Health Reports</h3>
                    <p>View and respond to health reports.</p>
                </a>
            </div>

            <div class="card">
                <a href="view_guidance.php">
                    <div class="card-icon">🎓</div>
                    <h3>Guidance Requests</h3>
                    <p>View and manage guidance requests.</p>
                </a>
            </div>

            <div class="card">
                <a href="view_students.php">
                    <div class="card-icon">👨‍🎓</div>
                    <h3>View Students</h3>
                    <p>View all registered students.</p>
                </a>
            </div>

            <div class="card">
                <a href="view_community.php">
                    <div class="card-icon">👥</div>
                    <h3>View Community</h3>
                    <p>View all registered community members.</p>
                </a>
            </div>

            <?php if ($staff_role == 'counsellor' || $staff_role == 'admin') { ?>
            <div class="card">
                <a href="view_counselling.php">
                    <div class="card-icon">💬</div>
                    <h3>Counselling Requests</h3>
                    <p>View and manage counselling requests.</p>
                </a>
            </div>
            <?php } ?>

            <?php if ($staff_role == 'admin') { ?>
            <div class="card">
                <a href="manage_staff.php">
                    <div class="card-icon">⚙️</div>
                    <h3>Manage Staff</h3>
                    <p>Add, edit or remove staff members.</p>
                </a>
            </div>
            <?php } ?>

            <div class="card">
                <a href="reset_password.php">
                    <div class="card-icon">🔒</div>
                    <h3>Change Password</h3>
                    <p>Update your account password.</p>
                </a>
            </div>

        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

</body>
</html>