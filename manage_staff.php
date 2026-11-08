<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['staff_id']) || $_SESSION['staff_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$staff_name = $_SESSION['staff_name'];

$host = "localhost";
$dbuser = "root";
$dbpassword = "";
$database = "uni_afya";

$conn = mysqli_connect($host, $dbuser, $dbpassword, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle add staff
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {

    if ($_POST['action'] == 'add') {
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $role = $_POST['role'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $status = 'active';

        $sql = "INSERT INTO staff (fullname, email, phone, role, password, status) 
                VALUES ('$fullname', '$email', '$phone', '$role', '$password', '$status')";

        if (mysqli_query($conn, $sql)) {
            $success = "Staff member added successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }

    if ($_POST['action'] == 'delete') {
        $staff_id = $_POST['staff_id'];
        $sql = "UPDATE staff SET status='inactive' WHERE id='$staff_id'";
        if (mysqli_query($conn, $sql)) {
            $success = "Staff member deactivated successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }

    if ($_POST['action'] == 'activate') {
        $staff_id = $_POST['staff_id'];
        $sql = "UPDATE staff SET status='active' WHERE id='$staff_id'";
        if (mysqli_query($conn, $sql)) {
            $success = "Staff member activated successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Get all staff
$sql = "SELECT * FROM staff ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNI-AFYA | Manage Staff</title>
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

        .add-btn {
            background-color: #006400;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
            float: right;
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

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            clear: both;
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

        .status-active {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .deactivate-btn {
            background-color: #cc0000;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .activate-btn {
            background-color: #006400;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            width: 500px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        .modal-box h3 {
            color: #006400;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
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
            font-size: 14px;
        }

        .modal-btns {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-submit {
            background-color: #006400;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            flex: 1;
        }

        .btn-cancel {
            background-color: #cc0000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            flex: 1;
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

    <div class="page-header">
        <h2>⚙️ Manage Staff</h2>
    </div>

    <div class="container">

        <a href="staff_dashboard.php" class="back-btn">← Back to Dashboard</a>
        <button class="add-btn" onclick="openModal()">+ Add New Staff</button>

        <?php if (isset($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>

        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <table>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $count++; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo ucfirst($row['role']); ?></td>
                <td>
                    <span class="status-<?php echo $row['status']; ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </span>
                </td>
                <td>
                    <?php if ($row['status'] == 'active') { ?>
                    <form action="manage_staff.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="staff_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="deactivate-btn">Deactivate</button>
                    </form>
                    <?php } else { ?>
                    <form action="manage_staff.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="activate">
                        <input type="hidden" name="staff_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="activate-btn">Activate</button>
                    </form>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Add Staff Modal -->
    <div class="modal" id="addModal">
        <div class="modal-box">
            <h3>Add New Staff Member</h3>
            <form action="manage_staff.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" placeholder="Enter full name" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter email" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="Enter phone number" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="">Select role</option>
                        <option value="doctor">Doctor</option>
                        <option value="nurse">Nurse</option>
                        <option value="counsellor">Counsellor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
                <div class="modal-btns">
                    <button type="submit" class="btn-submit">Add Staff</button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

    <script>
        function openModal() {
            document.getElementById('addModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
        }
    </script>

</body>
</html>