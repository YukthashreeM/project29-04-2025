<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Approve/Reject Logic
if (isset($_GET['action'], $_GET['id'])) {
    $leave_id = intval($_GET['id']);
    $status = ($_GET['action'] === 'approve') ? 'Approved' : 'Rejected';

    $stmt = $conn->prepare("UPDATE leaves SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $leave_id);
    if ($stmt->execute()) {
        header("Location: manage_leaves.php");
        exit();
    }
    $stmt->close();
}

$sql = "SELECT l.*, u.username FROM leaves l JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC";
$leaves = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Leave Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eef2f7;
        }

        header {
            background-color: #2f80ed;
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .container {
            padding: 30px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #2f80ed;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status {
            padding: 5px 12px;
            border-radius: 20px;
            color: white;
            font-weight: bold;
            display: inline-block;
        }

        .pending {
            background-color: #f1c40f;
        }

        .approved {
            background-color: #27ae60;
        }

        .rejected {
            background-color: #c0392b;
        }

        .action-buttons a {
            padding: 6px 12px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            margin-right: 8px;
            font-size: 14px;
        }

        .approve {
            background-color: #27ae60;
        }

        .reject {
            background-color: #e74c3c;
        }

        nav {
            text-align: center;
            margin-top: 30px;
        }

        nav a {
            margin: 0 15px;
            padding: 10px 20px;
            background-color: #2f80ed;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #1c5db8;
        }

        @media (max-width: 768px) {
            .action-buttons a {
                display: block;
                margin-bottom: 6px;
            }

            header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Admin - Manage Leave Requests</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<div class="container">
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $leaves->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['from_date'] ?></td>
                    <td><?= $row['to_date'] ?></td>
                    <td><?= htmlspecialchars($row['reason']) ?></td>
                    <td>
                        <span class="status <?= strtolower($row['status']) ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td class="action-buttons">
                        <?php if ($row['status'] === 'Pending'): ?>
                            <a href="?action=approve&id=<?= $row['id'] ?>" class="approve">Approve</a>
                            <a href="?action=reject&id=<?= $row['id'] ?>" class="reject">Reject</a>
                        <?php else: ?>
                            <em>No Action</em>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="apply_leave1.php">Apply Leave</a>
        <a href="manage_users1.php">Manage Users</a>
        <a href="reports.php">View Reports</a>
    </nav>
</div>

</body>
</html>
