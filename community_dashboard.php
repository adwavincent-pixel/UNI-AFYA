<?php
session_start();

// Check if community member is logged in
if (!isset($_SESSION['community_id'])) {
    header("Location: login.php");
    exit();
}

$community_name = $_SESSION['community_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNI-AFYA | Community Dashboard</title>
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
        }

        .dashboard-container {
            padding: 40px 30px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .dashboard-container h3 {
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

        .notice {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            text-align: center;
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
            <a href="community_dashboard.php">Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <h2>Welcome, <?php echo $community_name; ?>!</h2>
        <p>Community Health Services</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="dashboard-container">

        <div class="notice">
            ℹ️ As a community member you have access to health services only.
        </div>

        <h3>Available Services</h3>
        <div class="cards">

            <div class="card">
                <a href="health_report.php">
                    <div class="card-icon">🏥</div>
                    <h3>Report Health Issue</h3>
                    <p>Report a health problem and book an appointment with the nurse or doctor.</p>
                </a>
            </div>

            <div class="card">
                <a href="appointments.php">
                    <div class="card-icon">📅</div>
                    <h3>My Appointments</h3>
                    <p>View and manage your upcoming hospital appointments.</p>
                </a>
            </div>

            <div class="card">
                <a href="my_history.php">
                    <div class="card-icon">📋</div>
                    <h3>My History</h3>
                    <p>View your past health reports and appointments.</p>
                </a>
            </div>

            <div class="card">
                <a href="profile.php">
                    <div class="card-icon">👤</div>
                    <h3>My Profile</h3>
                    <p>View and update your personal information.</p>
                </a>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

</body>
</html>