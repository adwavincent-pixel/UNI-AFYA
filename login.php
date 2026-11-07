<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "uni_afya";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check students table
    $sql = "SELECT * FROM students WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $student = mysqli_fetch_assoc($result);

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_name'] = $student['fullname'];
        $_SESSION['user_type'] = 'student';
        header("Location: dashboard.php");
        exit();
    }

    // Check community table
    $sql2 = "SELECT * FROM community WHERE email='$email'";
    $result2 = mysqli_query($conn, $sql2);
    $community = mysqli_fetch_assoc($result2);

    if ($community && password_verify($password, $community['password'])) {
        $_SESSION['community_id'] = $community['id'];
        $_SESSION['community_name'] = $community['fullname'];
        $_SESSION['user_type'] = 'community';
        header("Location: community_dashboard.php");
        exit();
    }

    $error = "Invalid email or password";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNI-AFYA | Login</title>
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

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
        }

        .login-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .login-box h2 {
            color: #006400;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input:focus {
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

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .register-link {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .register-link a {
            color: #006400;
            text-decoration: none;
        }

        .footer {
            background-color: #006400;
            color: white;
            text-align: center;
            padding: 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <div class="navbar">
        <h1>UNI-AFYA</h1>
        <div>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
    </div>

    <!-- Login Form -->
    <div class="login-container">
        <div class="login-box">
            <h2>Login to UNI-AFYA</h2>

            <?php if (isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

</body>
</html>