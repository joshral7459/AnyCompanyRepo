<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnyCompany Insurance Quote Tool</title>
    <style>
        /* Your existing styles here */
        
        /* Add styles for container info */
        .container-info {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .container-info div {
            margin: 5px 0;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="user-info">
        Welcome, Employee | ID: EMP123 | <a href="#" style="color: white;">Logout</a>
    </div>

    <div class="header">
        <h1>AnyCompany Insurance Quote Tool</h1>
    </div>

    <!-- Add Container Information section -->
    <div class="container">
        <div class="container-info">
            <h2>Container Information</h2>
            <div>IP Address: <?php echo $_SERVER['SERVER_ADDR']; ?></div>
            <div>Hostname: <?php echo gethostname(); ?></div>
        </div>

        <!-- Rest of your existing content -->
        <nav class="nav-bar">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="quote.html">Quote Tool</a></li>
                <li><a href="#">Claims</a></li>
                <li><a href="#">Customers</a></li>
                <li><a href="#">Reports</a></li>
            </ul>
        </nav>

        <!-- Your existing form and other content -->
        <form id="quoteForm">
            <!-- Your existing form sections -->
        </form>

        <!-- Your existing results section -->
        <div id="quoteResults">
            <!-- Your existing results content -->
        </div>
    </div>

    <!-- Your existing script -->
    <script>
        // Your existing JavaScript
    </script>
</body>
</html>
