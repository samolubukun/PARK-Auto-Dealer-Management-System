<?php
// Your database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adms_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT LeadSource, COUNT(DISTINCT CustomerID) as count, COUNT(*) as totalLeads FROM customers WHERE LeadSource IN ('NewsPaper Adverts', 'Website Visit', 'Word Of Mouth') GROUP BY LeadSource";
$result = $conn->query($sql);

// Prepare data for charts
$labels = [];
$data = [];
$totalLeads = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['LeadSource'];
        $data[] = $row['count'];
        $totalLeads = $row['totalLeads'];
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Source Analytics</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<div style="width: 50%;">
        <center><h2>Lead Source Analytics</h2></center>
        <canvas id="leadSourceChart"></canvas>
    </div>
    <div style="width: 50%;">
        <canvas id="leadSourceChart"></canvas>
    </div>

    <div style="width: 50%;">
        <canvas id="totalLeadsChart"></canvas>
    </div>

    <div style="width: 50%;">
        <canvas id="leadDistributionChart"></canvas>
    </div>

    <script>
        // Chart data for Lead Source Pie Chart
        var leadSourceData = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                data: <?php echo json_encode($data); ?>,
                backgroundColor: ['#ff9999', '#66b3ff', '#99ff99'],
            }]
        };

        // Chart options for Lead Source Pie Chart
        var leadSourceOptions = {
            title: {
                display: true,
                text: 'Lead Source Analytics'
            },
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var currentValue = dataset.data[tooltipItem.index];
                        var totalLeads = <?php echo json_encode($totalLeads); ?>;
                        return 'Leads: ' + currentValue + ' (' + Math.floor((currentValue / totalLeads) * 100) + '%)';
                    }
                }
            }
        };

        // Create Pie Chart for Lead Source
        var leadSourceCtx = document.getElementById('leadSourceChart').getContext('2d');
        var leadSourceChart = new Chart(leadSourceCtx, {
            type: 'pie',
            data: leadSourceData,
            options: leadSourceOptions
        });

        // Chart data for Total Leads Bar Chart
        var totalLeadsData = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Total Leads',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: ['#ff9999', '#66b3ff', '#99ff99'],
            }]
        };

        // Chart options for Total Leads Bar Chart
        var totalLeadsOptions = {
            title: {
                display: true,
                text: 'Total Leads by Source'
            },
            legend: {
                display: false
            }
        };

        // Create Bar Chart for Total Leads
        var totalLeadsCtx = document.getElementById('totalLeadsChart').getContext('2d');
        var totalLeadsChart = new Chart(totalLeadsCtx, {
            type: 'bar',
            data: totalLeadsData,
            options: totalLeadsOptions
        });

        // Chart data for Lead Distribution Doughnut Chart
        var leadDistributionData = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                data: <?php echo json_encode($data); ?>,
                backgroundColor: ['#ff9999', '#66b3ff', '#99ff99'],
            }]
        };

        // Chart options for Lead Distribution Doughnut Chart
        var leadDistributionOptions = {
            title: {
                display: true,
                text: 'Lead Distribution Percentage'
            },
            legend: {
                position: 'right'
            }
        };

        // Create Doughnut Chart for Lead Distribution
        var leadDistributionCtx = document.getElementById('leadDistributionChart').getContext('2d');
        var leadDistributionChart = new Chart(leadDistributionCtx, {
            type: 'doughnut',
            data: leadDistributionData,
            options: leadDistributionOptions
        });
    </script>
</body>

</html>
