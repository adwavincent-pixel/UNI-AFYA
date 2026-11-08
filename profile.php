<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['student_id']) && !isset($_SESSION['community_id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$dbuser = "root";
$dbpassword = "";
$database = "uni_afya";

$conn = mysqli_connect($host, $dbuser, $dbpassword, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get user details
if (isset($_SESSION['student_id'])) {
    $user_id = $_SESSION['student_id'];
    $user_type = 'student';
    $dashboard = 'dashboard.php';

    $sql = "SELECT * FROM students WHERE id='$user_id'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
} else {
    $user_id = $_SESSION['community_id'];
    $user_type = 'community';
    $dashboard = 'community_dashboard.php';

    $sql = "SELECT * FROM community WHERE id='$user_id'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if ($user_type == 'student') {
        $course = mysqli_real_escape_string($conn, $_POST['course']);
        $year = $_POST['year'];
        $sql = "UPDATE students SET fullname='$fullname', phone='$phone', email='$email', course='$course', year='$year' WHERE id='$user_id'";
    } else {
        $age = $_POST['age'];
        $location = mysqli_real_escape_string($conn, $_POST['location']);
        $sql = "UPDATE community SET fullname='$fullname', phone='$phone', email='$email', age='$age', location='$location' WHERE id='$user_id'";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['student_name'] = $fullname;
        $success = "Profile updated successfully!";
        // Refresh user data
        if ($user_type == 'student') {
            $sql2 = "SELECT * FROM students WHERE id='$user_id'";
        } else {
            $sql2 = "SELECT * FROM community WHERE id='$user_id'";
        }
        $result2 = mysqli_query($conn, $sql2);
        $user = mysqli_fetch_assoc($result2);
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
    <title>UNI-AFYA | My Profile</title>
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

        .profile-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .profile-card {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .profile-avatar {
            text-align: center;
            margin-bottom: 30px;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            background-color: #006400;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            margin-bottom: 10px;
        }

        .profile-avatar h3 {
            color: #006400;
            font-size: 20px;
        }

        .profile-avatar p {
            color: #555;
            font-size: 14px;
            text-transform: capitalize;
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
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #006400;
        }

        .form-group input[readonly] {
            background-color: #f9f9f9;
            color: #888;
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

    <div class="navbar">
        <h1>UNI-AFYA</h1>
        <div>
            <a href="<?php echo $dashboard; ?>">Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="page-header">
        <h2>👤 My Profile</h2>
    </div>

    <div class="profile-container">

        <a href="<?php echo $dashboard; ?>" class="back-btn">← Back to Dashboard</a>

        <div class="profile-card">

            <div class="profile-avatar">
                <div class="avatar-circle">
                    <?php echo strtoupper(substr($user['fullname'], 0, 1)); ?>
                </div>
                <h3><?php echo $user['fullname']; ?></h3>
                <p><?php echo $user_type; ?> Account</p>
            </div>

            <?php if (isset($success)) { ?>
                <div class="success"><?php echo $success; ?></div>
            <?php } ?>

            <?php if (isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>

            <form action="profile.php" method="POST">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
                </div>

                <?php if ($user_type == 'student') { ?>
                <div class="form-group">
                    <label>Student ID</label>
                    <input type="text" value="<?php echo $user['student_id']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Course</label>
                    <input type="text" name="course" value="<?php echo $user['course']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Year of Study</label>
                    <select name="year">
                        <?php for ($i = 1; $i <= 6; $i++) { ?>
                        <option value="<?php echo $i; ?>" <?php if ($user['year'] == $i) echo 'selected'; ?>>
                            Year <?php echo $i; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <?php } else { ?>
                <div class="form-group">
                    <label>National ID</label>
                    <input type="text" value="<?php echo $user['national_id']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" value="<?php echo $user['age']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" value="<?php echo $user['location']; ?>" required>
                </div>
                <?php } ?>

                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

</body>
</html>