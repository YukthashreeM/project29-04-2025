<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: login.php");
    exit();
}
$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eef2f7;
        }

        header {
            background-color: #2f80ed;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .user-info {
            font-size: 14px;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .container {
            padding: 30px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        nav {
            margin-top: 20px;
        }

        nav a {
            margin-right: 15px;
            background-color: #2f80ed;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #1c5db8;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            nav a {
                display: block;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<header>
    <div>
        <h1>Employee Dashboard</h1>
        <div class="user-info">Logged in as: <strong><?= $username ?></strong></div>
    </div>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<div class="container">
    <div class="card">
        <p>Welcome, <strong><?= $username ?></strong>! This is your dashboard.</p>

        <nav>
            <a href="apply_leave.php">Apply for Leave</a>
            <a href="cab.php">Cab</a>
            <a href="settings.php">Settings</a>


        </nav>
    </div>
</div>

</body>
</html>
