<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "uni_afya";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $student_id = $_POST['student_id'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $phone = $_POST['phone'];
    $year = $_POST['year'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if passwords match
    if ($_POST['password'] != $_POST['confirm_password']) {
        $error = "Passwords do not match";
    } else {
        // Save to database
        $sql = "INSERT INTO students (fullname, student_id, email, course, phone, year, password) 
                VALUES ('$fullname', '$student_id', '$email', '$course', '$phone', '$year', '$password')";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Account created successfully! You can now login.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNI-AFYA | Register</title>
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

        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 30px;
        }

        .register-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            width: 500px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .register-box h2 {
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

        .login-link {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .login-link a {
            color: #006400;
            text-decoration: none;
        }

        .footer {
            background-color: #006400;
            color: white;
            text-align: center;
            padding: 20px;
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

    <!-- Register Form -->
    <div class="register-container">
        <div class="register-box">
            <h2>Create Account</h2>

            <?php if (isset($success)) { ?>
                <div class="success"><?php echo $success; ?></div>
            <?php } ?>

            <?php if (isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label>Student ID</label>
                    <input type="text" name="student_id" placeholder="Enter your student ID" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label>Course</label>
                    <input type="text" name="course" placeholder="Enter your course" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="Enter your phone number" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Year of Study</label>
                    <select name="year">
                        <option value="1">Year 1</option>
                        <option value="2">Year 2</option>
                        <option value="3">Year 3</option>
                        <option value="4">Year 4</option>
                        <option value="5">Year 5</option>
                        <option value="6">Year 6</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create a password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                <button type="submit" class="btn">Create Account</button>
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

</body>
</html>