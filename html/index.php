<?php
$capacity = '';
if (strpos($_SERVER['REQUEST_URI'], '/lo-capacity') !== false) {
    $capacity = 'lo-capacity';
} elseif (strpos($_SERVER['REQUEST_URI'], '/hi-capacity') !== false) {
    $capacity = 'hi-capacity';
}
// Use $capacity variable as needed in your script
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>AnyCompany Insurance - Employee Portal</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
        }

        /* Header styles */
        .user-info {
            background-color: #2a5577;
            color: white;
            padding: 0.5rem;
            text-align: right;
        }

        .user-info a {
            color: white;
            text-decoration: none;
        }

        .header {
            background-color: #1a4567;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        /* Navigation styles */
        .nav-bar {
            background-color: #2a5577;
            padding: 1rem;
        }

        .nav-bar ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 2rem;
        }

        .nav-bar a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .nav-bar a:hover {
            background-color: #1a4567;
        }

        /* Main content styles */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .dashboard-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .quick-actions {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1a4567;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #2a5577;
        }

        /* Statistics styles */
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #1a4567;
            margin: 10px 0;
        }

        .stat-label {
            color: #666;
        }

        /* Recent activity table styles */
        .activity-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .activity-table th,
        .activity-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .activity-table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="user-info">
        Welcome, Employee | ID: EMP123 | <a href="#">Logout</a>
    </div>

    <div class="header">
        <h1>AnyCompany Insurance Employee Portal</h1>
    </div>

    <nav class="nav-bar">
        <ul>
            <li><a href="/<?php echo $capacity ? $capacity . '/' : ''; ?>">Home</a></li>
            <li><a href="/<?php echo $capacity ? $capacity . '/' : ''; ?>quote.php">Quote Tool</a></li>
            <li><a href="#">Claims</a></li>
            <li><a href="#">Customers</a></li>
            <li><a href="#">Reports</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="quick-actions">
            <h2>Quick Actions</h2>
                <a href="/<?php echo $capacity ? $capacity . '/' : ''; ?>quote.php" class="button">New Quote</a>
                <a href="#" class="button">Process Claim</a>
                <a href="#" class="button">Customer Search</a>
                <a href="#" class="button">Generate Report</a>
            </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Daily Statistics</h3>
                <div class="stat-number">15</div>
                <div class="stat-label">Quotes Generated Today</div>
                <div class="stat-number">8</div>
                <div class="stat-label">Policies Sold Today</div>
            </div>

        <div class="dashboard-card">
            <h3>Daily Statistics</h3>
            <div class="stat-number">
                <div class="stat-number">10</div>
            </div>
            <div class="stat-label">Quotes Generated Today</div>
            <div class="stat-number">
                <div class="stat-number">15</div>
            </div>

            <div class="stat-label">Policies Sold Today</div>
        </div>

            <div class="dashboard-card">
                <h3>Recent Activity</h3>
                <table class="activity-table">
                    <tr>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>10:45 AM</td>
                        <td>Quote #12345 Generated</td>
                    </tr>
                    <tr>
                        <td>09:30 AM</td>
                        <td>Policy #98765 Issued</td>
                    </tr>
                    <tr>
                        <td>09:15 AM</td>
                        <td>Customer Profile Updated</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
