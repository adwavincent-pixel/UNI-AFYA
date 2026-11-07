<?php
session_start();

// Check if staff is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: staff_login.php");
    exit();
}

$staff_name = $_SESSION['staff_name'];
$staff_role = $_SESSION['staff_role'];

$host = "localhost";
$user = "root";
$password = "";
$database = "uni_afya";

$conn = mysqli_connect($host, $user, $password, $database);

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_id = $_POST['report_id'];
    $status = $_POST['status'];
    $response = $_POST['response'];

    $sql = "UPDATE health_reports SET status='$status', staff_response='$response', attended_by='$staff_name' WHERE id='$report_id'";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Report updated successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Get all health reports
$sql = "SELECT * FROM health_reports ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNI-AFYA | View Health Reports</title>
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

        .severity-mild {
            color: #006400;
            font-weight: bold;
        }

        .severity-moderate {
            color: #ff8c00;
            font-weight: bold;
        }

        .severity-severe {
            color: #cc0000;
            font-weight: bold;
        }

        .respond-btn {
            background-color: #006400;
            color: white;
            padding: 6px 15px;
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

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 100px;
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
        <h2>🏥 Health Reports</h2>
    </div>

    <!-- Content -->
    <div class="container">

        <a href="staff_dashboard.php" class="back-btn">← Back to Dashboard</a>

        <?php if (isset($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>

        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <table>
            <tr>
                <th>#</th>
                <th>User Type</th>
                <th>Complaint</th>
                <th>Symptoms</th>
                <th>Duration</th>
                <th>Severity</th>
                <th>Appointment Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $count++; ?></td>
                <td><?php echo ucfirst($row['user_type']); ?></td>
                <td><?php echo $row['complaint']; ?></td>
                <td><?php echo $row['symptoms']; ?></td>
                <td><?php echo $row['duration']; ?></td>
                <td class="severity-<?php echo $row['severity']; ?>"><?php echo ucfirst($row['severity']); ?></td>
                <td><?php echo $row['appointment_date']; ?></td>
                <td>
                    <span class="status-<?php echo $row['status']; ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </span>
                </td>
                <td>
                    <button class="respond-btn" onclick="openModal(<?php echo $row['id']; ?>)">Respond</button>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <!-- Response Modal -->
    <div class="modal" id="responseModal">
        <div class="modal-box">
            <h3>Respond to Health Report</h3>
            <form action="view_reports.php" method="POST">
                <input type="hidden" name="report_id" id="modal_report_id">
                <div class="form-group">
                    <label>Update Status</label>
                    <select name="status">
                        <option value="pending">Pending</option>
                        <option value="attended">Attended</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Your Response / Notes</label>
                    <textarea name="response" placeholder="Enter your medical notes or response here"></textarea>
                </div>
                <div class="modal-btns">
                    <button type="submit" class="btn-submit">Submit Response</button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 UNI-AFYA | Student Health System</p>
    </div>

    <script>
        function openModal(reportId) {
            document.getElementById('modal_report_id').value = reportId;
            document.getElementById('responseModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('responseModal').style.display = 'none';
        }
    </script>

</body>
</html>