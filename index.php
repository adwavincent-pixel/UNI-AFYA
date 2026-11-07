<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNI-AFYA | Student Health System</title>
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

        .hero {
            text-align: center;
            padding: 80px 20px;
            background-color: #ffffff;
        }

        .hero h2 {
            font-size: 36px;
            color: #006400;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        .btn {
            background-color: #006400;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            margin: 10px;
        }

        .btn:hover {
            background-color: #004d00;
        }

        .services {
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 50px 20px;
            flex-wrap: wrap;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            width: 250px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card h3 {
            color: #006400;
            margin-bottom: 10px;
        }

        .card p {
            color: #555;
            font-size: 14px;
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
            <a href="#">Home</a>
            <a href="#">Login</a>
            <a href="#">Register</a>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <h2>Welcome to UNI-AFYA</h2>
        <p>Your Student Health, Guidance and Counselling System</p>
        <a href="#" class="btn">Get Started</a>
        <a href="#" class="btn">Login</a>
    </div>

    <!-- Services -->
    <div class="services">
        <div class="card">
            <h3>Health Services</h3>
            <p>Report health issues and book appointments with the school nurse or doctor.</p>
        </div>
        <div class="card">
            <h3>Guidance</h3>
            <p>Get academic and career guidance from qualified school counsellors.</p>
        </div>
        <div class="card">
            <h3>Counselling</h3>
            <p>Book confidential counselling sessions for personal and mental health support.</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

</body>
</html>