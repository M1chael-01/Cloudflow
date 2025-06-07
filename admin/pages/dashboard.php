<?php
require "./admin/pages/sitebar.php";
require "./database/administration/orders.php";

$json_file = './data/products_services.json';  
$order_data = [
    'labels' => ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'],
    'order_counts' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
    'total_incomes' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
    'vat_incomes' => [100, 70, 0, 0, 0, 10, 0, 0, 0, 80, 0, 0], 
    'categories' => [], 
    'average_order_value' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] 
];

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
        $order_data['categories'] = array_keys(array_slice($category_counts, 0, 6));  
    } 
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
WHERE accepted != 'yes'  
GROUP BY YEAR(date), MONTH(date), accepted 
ORDER BY YEAR(date), MONTH(date), accepted;  
";

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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="./admin/styles/dashboard.css">
</head>
<body>
<div class="content">
<nav >
            <a  class="navbar-brand px-3" href="#">Vítejte v Admin Dashboardu <i class="ri-admin-line"></i></a>
        </nav>
        <div class="container mt-4">
            <div class="row" id="row">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Produkty</h5>
                        <p class="card-text">Spravujte skladové zásoby.</p>
                        <a href="?produkty-admin"><button>Spravovat</button></a>
                    </div>
                </div>
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Objednávky</h5>
                        <p class="card-text">Řešte přijaté objednávky.</p>
                        <a href="?objednavky-admin"><button>Spravovat</button></a>
                    </div>
                </div>
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Reklamace</h5>
                        <p class="card-text">Spravujte reklamace zákazníků.</p>
                        <a href="?reklamace-admin"><button>Řešit</button></a>
                    </div>
                </div>
            </div>
            <h3>Grafy objednávek a produktů</h3>
            <div class="row">
                <div class="col-md-6">
                    <canvas width="600px"  height="370px" id="salesChart"></canvas>
                </div>
                <div class="col-md-6">
                <canvas width="700px" height="370px" id="categoryChart"></canvas>
            </div>
            </div>
        </div>
    </div>

    <script>
        // 2D Bar Chart for Orders and Total Income using Chart.js
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctxSales, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($order_data['labels']); ?>,  // Months in Czech
                datasets: [
                    {
                        label: 'Počet objednávek',
                        data: <?php echo json_encode($order_data['order_counts']); ?>,  // Order counts from DB
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        barThickness:70
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
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
            responsive: true,
            scales:{
                y:{
                    beginAtZero:true
                }
            }
        }
    });
    </script>

</body>
</html>

<?php
$conn->close();
?>
