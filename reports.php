<?php
session_start();
require_once "config.php"; // Your database connection file

// Redirect if not logged in or not an Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Fetch total leaves
$totalLeavesQuery = "SELECT COUNT(*) AS total FROM leaves";
$totalLeavesResult = $conn->query($totalLeavesQuery);
$totalLeaves = $totalLeavesResult->fetch_assoc()['total'];

// Fetch approved leaves
$approvedLeavesQuery = "SELECT COUNT(*) AS approved FROM leaves WHERE status = 'Approved'";
$approvedLeavesResult = $conn->query($approvedLeavesQuery);
$approvedLeaves = $approvedLeavesResult->fetch_assoc()['approved'];

// Fetch rejected leaves
$rejectedLeavesQuery = "SELECT COUNT(*) AS rejected FROM leaves WHERE status = 'Rejected'";
$rejectedLeavesResult = $conn->query($rejectedLeavesQuery);
$rejectedLeaves = $rejectedLeavesResult->fetch_assoc()['rejected'];

// Fetch pending leaves
$pendingLeavesQuery = "SELECT COUNT(*) AS pending FROM leaves WHERE status = 'Pending'";
$pendingLeavesResult = $conn->query($pendingLeavesQuery);
$pendingLeaves = $pendingLeavesResult->fetch_assoc()['pending'];

// Fetch leave requests for detailed report
$leaveDetailsQuery = "SELECT l.*, u.username FROM leaves l JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC";
$leaves = $conn->query($leaveDetailsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Reports</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .stats-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }

        .stats-box {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            width: 200px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stats-box h2 {
            margin: 0;
        }

        .stats-box p {
            font-size: 1.5em;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-links a {
            margin-right: 10px;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .action-links a.approve {
            background-color: #28a745;
        }

        .action-links a.reject {
            background-color: #dc3545;
        }

        .action-links a:hover {
            opacity: 0.8;
        }

        nav {
            margin-top: 20px;
            text-align: center;
        }

        nav a {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            margin: 0 10px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #0056b3;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Leave Reports</h1>

    <!-- Logout Button -->
    <a href="logout.php" class="logout-btn">Logout</a>
    
    <a href="admin.php" class="btn">← Back to Dashboard</a>
    <hr>

    <!-- Leave Stats Summary -->
    <div class="stats-container">
        <div class="stats-box">
            <h2>Total Leaves</h2>
            <p><?= $totalLeaves ?></p>
        </div>
        <div class="stats-box">
            <h2>Approved</h2>
            <p><?= $approvedLeaves ?></p>
        </div>
        <div class="stats-box">
            <h2>Rejected</h2>
            <p><?= $rejectedLeaves ?></p>
        </div>
        <div class="stats-box">
            <h2>Pending</h2>
            <p><?= $pendingLeaves ?></p>
        </div>
    </div>

    <!-- Detailed Leave Requests -->
    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>From</th>
            <th>To</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $leaves->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['from_date'] ?></td>
                <td><?= $row['to_date'] ?></td>
                <td><?= htmlspecialchars($row['reason']) ?></td>
                <td>
                    <span class="status <?= strtolower($row['status']) ?>"><?= $row['status'] ?></span>
                </td>
                <td class="action-links">
                    <?php if ($row['status'] === 'Pending'): ?>
                        <a href="?action=approve&id=<?= $row['id'] ?>" class="approve">Approve</a>
                        <a href="?action=reject&id=<?= $row['id'] ?>" class="reject">Reject</a>
                    <?php else: ?>
                        <em>No action</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <nav>
        <a href="apply_leave.php">Apply Leave</a>
        <a href="manage_users1.php">Manage Users</a>
        <a href="manage_leaves.php">Manage Leaves</a>
    </nav>
</body>
</html>
