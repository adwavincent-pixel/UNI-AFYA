<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if staff is logged in
if (!isset($_SESSION['staff_id'])) {
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

// Get all community members
$sql = "SELECT * FROM community ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNI-AFYA | View Community</title>
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
            max-width: 1200px;
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

        .search-box {
            margin-bottom: 20px;
        }

        .search-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-box input:focus {
            outline: none;
            border-color: #006400;
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

        .total {
            margin-bottom: 15px;
            color: #555;
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
            <a href="staff_dashboard.php">Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <h2>👥 Community Members</h2>
    </div>

    <!-- Content -->
    <div class="container">

        <a href="staff_dashboard.php" class="back-btn">← Back to Dashboard</a>

        <!-- Search -->
        <div class="search-box">
            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search by name, email, national ID or location...">
        </div>

        <p class="total">Total Community Members: <strong><?php echo mysqli_num_rows($result); ?></strong></p>

        <table id="communityTable">
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>National ID</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Age</th>
                <th>Location</th>
            </tr>
            <?php
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $count++; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['national_id']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['location']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

    <script>
        function searchTable() {
            var input = document.getElementById('searchInput');
            var filter = input.value.toLowerCase();
            var table = document.getElementById('communityTable');
            var rows = table.getElementsByTagName('tr');

            for (var i = 1; i < rows.length; i++) {
                var row = rows[i];
                var cells = row.getElementsByTagName('td');
                var found = false;

                for (var j = 0; j < cells.length; j++) {
                    if (cells[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        }
    </script>

</body>
</html>