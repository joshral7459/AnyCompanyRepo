<?php
require '/var/www/html/vendor/autoload.php';

use Aws\Ecs\EcsClient;
use Aws\Exception\AwsException;

function getAvailabilityZone() {
    $metadataUri = getenv('ECS_CONTAINER_METADATA_URI_V4');
    if (!$metadataUri) {
        return 'Not available';
    }

    try {
        $metadata = @file_get_contents($metadataUri . '/task');
        if ($metadata === false) {
            return 'Not available';
        }
        $metadataArray = json_decode($metadata, true);
        return $metadataArray['AvailabilityZone'] ?? 'Not available';
    } catch (Exception $e) {
        return 'Not available';
    }
}
function getTargetGroup() {
    try {
        putenv('AWS_SUPPRESS_PHP_DEPRECATION_WARNING=true');
        $client = new EcsClient([
            'version' => 'latest',
            'region'  => 'us-east-1'  // Make sure this matches your region
        ]);

        // Get the cluster name from an environment variable
        $clusterName = getenv('ECS_CLUSTER_NAME');
        error_log("Cluster Name: " . $clusterName);
        if (!$clusterName) {
            return 'Cluster name not available';
        }

        // Get the task ARN from the task metadata
        $taskArn = getTaskArn();
        error_log("Task ARN: " . $taskArn);
        if (!$taskArn) {
            return 'Task ARN not available';
        }

        // Describe the task
        $task = $client->describeTasks([
            'cluster' => $clusterName,
            'tasks' => [$taskArn]
        ]);

        error_log("Task Description: " . json_encode($task));

        if (isset($task['tasks'][0]['group'])) {
            $serviceArn = $task['tasks'][0]['group'];
            error_log("Service ARN: " . $serviceArn);
            // If the group starts with "service:", it's a service name
            if (strpos($serviceArn, 'service:') === 0) {
                $serviceName = substr($serviceArn, 8);
                error_log("Service Name: " . $serviceName);
                $service = $client->describeServices([
                    'cluster' => $clusterName,
                    'services' => [$serviceName]
                ]);

                error_log("Service Description: " . json_encode($service));

                if (isset($service['services'][0]['loadBalancers'])) {
                    foreach ($service['services'][0]['loadBalancers'] as $loadBalancer) {
                        if (isset($loadBalancer['targetGroupArn'])) {
                            $fullArn = $loadBalancer['targetGroupArn'];
                            error_log("Target Group ARN: " . $fullArn);
                            if (strpos($fullArn, 'Lo-Capacity') !== false) {
                                return 'Lo-Capacity';
                            } elseif (strpos($fullArn, 'Hi-Capacity') !== false) {
                                return 'Hi-Capacity';
                            }
                        }
                    }
                }
            }
        }
    } catch (AwsException $e) {
        error_log("AWS Exception: " . $e->getMessage());
        return 'Error: ' . $e->getMessage();
    } catch (Exception $e) {
        error_log("General Exception: " . $e->getMessage());
        return 'Error: ' . $e->getMessage();
    }

    return 'Target Group not available';
}

function getTaskArn() {
    $metadataUri = getenv('ECS_CONTAINER_METADATA_URI_V4');
    if (!$metadataUri) {
        return null;
    }

    try {
        $metadata = @file_get_contents($metadataUri . '/task');
        if ($metadata === false) {
            return null;
        }
        $metadataArray = json_decode($metadata, true);
        return $metadataArray['TaskARN'] ?? null;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AnyCompany Insurance Quote Tool</title>
    <style>
        /* Base styles */
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
        .header {
            background-color: #1a4567;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        .user-info {
            background-color: #2a5577;
            color: white;
            padding: 0.5rem;
            text-align: right;
        }

        /* Main container styles */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Form styles */
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .radio-group {
            margin: 10px 0;
        }

        .radio-group label {
            display: inline;
            margin-right: 15px;
            font-weight: normal;
        }

        /* Button styles */
        .button-group {
            margin-top: 20px;
            text-align: center;
        }

        button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .calculate-btn {
            background-color: #4CAF50;
            color: white;
        }

        .reset-btn {
            background-color: #f44336;
            color: white;
        }

        .save-btn {
            background-color: #2196F3;
            color: white;
        }

        /* Results section */
        #quoteResults {
            margin-top: 20px;
            padding: 20px;
            background-color: #e8f5e9;
            border-radius: 5px;
            display: none;
        }

        .result-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

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
        .info-value {
            color: blue;
            font-weight: bold;
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

    <nav class="nav-bar">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="quote.php">Quote Tool</a></li>
            <li><a href="#">Claims</a></li>
            <li><a href="#">Customers</a></li>
            <li><a href="#">Reports</a></li>
        </ul>
    </nav>

<div class="container">
    <div class="container-info">
        <h2>Container Information</h2>
        <div class="info-item">
            <span class="info-label">IP Address:</span>
            <span class="info-value"><?php echo $_SERVER['SERVER_ADDR']; ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Availability Zone:</span>
            <span class="info-value"><?php echo getAvailabilityZone(); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">Target Group:</span>
            <span class="info-value"><?php echo getTargetGroup(); ?></span>
        </div>
    </div>

    <form id="quoteForm">
            <div class="form-section">
                <h2>Customer Information</h2>
                <div class="form-group">
                    <label for="customerName">Customer Name</label>
                    <input type="text" id="customerName" required>
                </div>
                <div class="form-group">
                    <label for="customerDOB">Date of Birth</label>
                    <input type="date" id="customerDOB" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <div class="radio-group">
                        <input type="radio" id="male" name="gender" value="male">
                        <label for="male">Male</label>
                        <input type="radio" id="female" name="gender" value="female">
                        <label for="female">Female</label>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2>Policy Details</h2>
                <div class="form-group">
                    <label for="policyType">Policy Type</label>
                    <select id="policyType" required>
                        <option value="">Select Policy Type</option>
                        <option value="life">Life Insurance</option>
                        <option value="health">Health Insurance</option>
                        <option value="auto">Auto Insurance</option>
                        <option value="home">Home Insurance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="coverageAmount">Coverage Amount</label>
                    <input type="number" id="coverageAmount" min="0" required>
                </div>
                <div class="form-group">
                    <label for="term">Term (Years)</label>
                    <input type="number" id="term" min="1" max="30" required>
                </div>
            </div>

            <div class="form-section">
                <h2>Risk Assessment</h2>
                <div class="form-group">
                    <label for="riskLevel">Risk Level</label>
                    <select id="riskLevel" required>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="notes">Additional Notes</label>
                    <textarea id="notes" rows="4" style="width: 100%; padding: 8px;"></textarea>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="calculate-btn" onclick="calculateQuote()">Calculate Quote</button>
                <button type="reset" class="reset-btn">Reset Form</button>
                <button type="button" class="save-btn">Save Quote</button>
            </div>
        </form>

    <div id="quoteResults">
            <h2>Quote Summary</h2>
            <div class="result-row">
                <span>Monthly Premium:</span>
                <span id="monthlyPremium">$0.00</span>
            </div>
            <div class="result-row">
                <span>Annual Premium:</span>
                <span id="annualPremium">$0.00</span>
            </div>
            <div class="result-row">
                <span>Total Coverage:</span>
                <span id="totalCoverage">$0.00</span>
            </div>
        </div>
    </div>


<script>
    function calculateQuote() {
        try {
            // This is a simple example calculation
            const coverageAmount = document.getElementById('coverageAmount').value;
            const riskLevel = document.getElementById('riskLevel').value;
            const term = document.getElementById('term').value;

            let riskMultiplier;
            switch(riskLevel) {
                case 'low':
                    riskMultiplier = 0.01;
                    break;
                case 'medium':
                    riskMultiplier = 0.015;
                    break;
                case 'high':
                    riskMultiplier = 0.02;
                    break;
                default:
                    riskMultiplier = 0.01;
            }

            // Calculate premiums
            const annualPremium = coverageAmount * riskMultiplier;
            const monthlyPremium = annualPremium / 12;

            // Display results
            document.getElementById('monthlyPremium').textContent = `$${monthlyPremium.toFixed(2)}`;
            document.getElementById('annualPremium').textContent = `$${annualPremium.toFixed(2)}`;
            document.getElementById('totalCoverage').textContent = `$${coverageAmount}`;

            // Show results section
            document.getElementById('quoteResults').style.display = 'block';
        } catch (error) {
            console.error('Error calculating quote:', error);
            alert('An error occurred while calculating the quote');
        }
    }
</script>
</body>
</html>
