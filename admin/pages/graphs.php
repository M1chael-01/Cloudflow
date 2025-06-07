<?php
require "./admin/pages/sitebar.php";
require "./database/administration/orders.php";
require "./database/administration/admin.php";

$admins = admins();

// Fetch the number of admins
function selectAdmin() {
    global $admins; // Ensure $admins is available globally
    // SQL to get the admins (assuming you are querying admins from the database)
    $sql = "SELECT COUNT(*) AS admin_count FROM admin";  // Use COUNT directly in the query
    $result = $admins->query($sql);

    // Check if the query was successful
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['admin_count'];  // Return the count of admins
    } else {
        return 0;  // Return 0 if no admins are found or an error occurs
    }
}
$adminCount = selectAdmin();

$json_file = './data/products_services.json';  

$order_data = [
    'labels' => ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'],
    'order_counts' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
    'total_incomes' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
    'vat_incomes' => [100, 70, 0, 0, 0, 10, 0, 0, 0, 80, 0, 0], 
    'categories' => [], 
    'average_order_value' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] 
];

// Read and decode the JSON file
if (file_exists($json_file)) {
    $json_data = file_get_contents($json_file);
    $products = json_decode($json_data);

    // Ensure the products data is available
    if (isset($products->products)) {
        // Count the occurrences of each category
        $category_counts = array();
        foreach ($products->products as $product) {
            if (!empty($product->category)) {
                $category_counts[$product->category] = isset($category_counts[$product->category]) ? $category_counts[$product->category] + 1 : 1;
            }
        }
        // Populate the order_data['categories'] with the top 6 categories
        $order_data['categories'] = array_keys(array_slice($category_counts, 0, 6));  // Take top 6 categories
    } else {
        echo "No products found in the JSON file.";
    }
} else {
    echo "The JSON file does not exist.";
}

// Database connection
$conn = orders();

// Check for database connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch the number of accepted and rejected orders
$sql = "SELECT 
            COUNT(id) AS order_count,
            SUM(billing) AS total_income,   
            AVG(billing) AS avg_order_value, 
            MONTH(date) AS month,
            YEAR(date) AS year,
            accepted
        FROM one_order
        GROUP BY MONTH(date), YEAR(date), accepted
        ORDER BY YEAR(date), MONTH(date), accepted";

// Execute the query
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Arrays to hold the count of accepted vs. rejected orders for each month
$accepted_counts = array_fill(0, 12, 0);  // Initialize an array for accepted counts
$rejected_counts = array_fill(0, 12, 0);  // Initialize an array for rejected counts

// Loop through the orders and populate the order data arrays
if ($result->num_rows > 0) {
    while ($order = $result->fetch_assoc()) {
        $month = $order['month'] - 1; // Adjust for zero-indexed months

        if ($order['accepted'] === 'yes') {
            $accepted_counts[$month] += (int)$order['order_count'];  // Add to accepted count
        } else {
            $rejected_counts[$month] += (int)$order['order_count'];  // Add to rejected count
        }

        // Continue populating the order_data arrays with total income, VAT, etc.
        $total_price = (float)$order['total_income'];
        $vat = $total_price * 0.21 / 1.21;

        $order_data['order_counts'][$month] += (int)$order['order_count'];
        $order_data['total_incomes'][$month] += $total_price;
        $order_data['vat_incomes'][$month] += $vat;
        $order_data['average_order_value'][$month] = (float)$order['avg_order_value'];
    }
}

?>

<head>
    <title>Statistika</title>
    <link rel="stylesheet" href="admin/styles/graphs.css">
</head>
<body>

<div class="content">
    <nav>
        <a class="navbar-brand px-3">Zde můžete vidět různé grafy <i class="ri-bar-chart-2-line"></i> </a>
    </nav>

    <div class="container mt-4">
        <h3>Grafy Počtu Objednávek, Příjmů, DPH a Další</h3>

        <div class="row">
            <div class="col-md-6">
                <canvas width="600px" height="370px" id="salesChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas width="700px" height="370px" id="categoryChart"></canvas>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <canvas width="700px" height="370px" id="acceptedRejectedChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas width="700px" height="370px" id="adminChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctxSales = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctxSales, {
    type: 'bar',  // Set the chart type to 'bar'
    data: {
        labels: <?php echo json_encode($order_data['labels']); ?>,  // Labels for the x-axis
        datasets: [
            {
                label: 'Počet objednávek',
                data: <?php echo json_encode($order_data['order_counts']); ?>,  // Data for order counts
                backgroundColor: 'rgba(75, 192, 192, 0.6)',  // Color for bars
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
            },
           
        ]
    },
    options: {
        responsive: true,
        indexAxis: 'y',  
        scales: {
            x: {
                stacked: true, 
            },
            y: {
                stacked: true,  
                beginAtZero: true  
            }
        }
    }
});


    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(ctxCategory, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($order_data['categories']); ?>,  
            datasets: [{
                label: 'Kategorie produktů',
                data: <?php echo json_encode(array_values($category_counts)); ?>,  
                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });

    const ctxAcceptedRejected = document.getElementById('acceptedRejectedChart').getContext('2d');
    const acceptedRejectedChart = new Chart(ctxAcceptedRejected, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($order_data['labels']); ?>,
            datasets: [{
                label: 'Přijaté objednávky',
                data: <?php echo json_encode($accepted_counts); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Odmítnuté objednávky',
                data: <?php echo json_encode($rejected_counts); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    xAxis: {
        type: 'time',
      }
                }
            }
        }
    });

    const ctxAdmin = document.getElementById('adminChart').getContext('2d');
const adminChart = new Chart(ctxAdmin, {
    type: 'bubble',
    data: {
        labels: ["Aktuální počet"],  
        datasets: [{
            label: 'Počet Adminů',  // Data for admin chart
            data: [{
                x: 1,  // The x-axis position (you can adjust this if you want it to show in a specific month)
                y: <?php echo $adminCount; ?>,  // The y-axis position (admin count value)
                r: 20  // Radius of the bubble (you can adjust this size)
            }],
            backgroundColor: 'rgba(255,212,220,0.8)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                min: 0,
                max: 12,  
                ticks: {
                    stepSize: 1,
                    callback: function(value) {
                        return value === 1 ? '' : '';  
                    }
                }
            },
            y: {
                beginAtZero: true  
            }
        }
    }
});

</script>

</body>
</html>
