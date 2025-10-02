<?php
include 'includes/header.php';
require_once "includes/classes/admin-class.php";

$admins = new Admins($dbh);

// Check user role from session
$user_role = $_SESSION['user_role'] ?? 'admin';

if ($user_role == 'employer') {
    // Employer Dashboard
    $location = $_SESSION['user_location'];
    $customers = $admins->fetchCustomersByLocation($location);
    $products = $admins->fetchProductsByCustomerLocation($location);
?>
<div class="container-fluid">
    <h3>Employer Dashboard - Location: <?php echo htmlspecialchars($location); ?></h3>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Customers in Your Location</div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($customers): ?>
                                <?php foreach ($customers as $customer): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($customer->full_name); ?></td>
                                        <td><?php echo htmlspecialchars($customer->address); ?></td>
                                        <td><?php echo htmlspecialchars($customer->contact); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No customers found for this location.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Products Availed by Customers in Your Location</div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Package Name</th>
                                <th>Fee</th>
                                <th>Number of Customers</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($products): ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product->name); ?></td>
                                        <td><?php echo htmlspecialchars($product->fee); ?></td>
                                        <td><?php echo htmlspecialchars($product->customer_count); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No products found for this location.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
    // Admin Dashboard
?>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
        Balance &amp; Cash Collection Chart:
        </div>
        <div class="panel-body">
            <canvas id="myChart">
					
				</canvas>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
        Monthly Bill Collection : 2016
        </div>
        <div class="panel-body">
            <canvas id="chart2">
					
				</canvas>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script type="text/javascript">
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
            datasets: [{
                label: 'Cash Collection',
                data: [12, 19, 3, 17, 6, 3, 20],
                backgroundColor: "rgba(153,255,51,0.6)"
            }, {
                label: 'Balance',
                data: [2, 29, 5, 5, 2, 3, 10],
                backgroundColor: "rgba(245,0,0,0.6)"
            }]
        }
    });
    var ctx = document.getElementById('chart2').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Monthly Bill Collection',
                data: [50000, 60000, 30000, 45000, 48000, 38000, 80000, 50000, 0],
                backgroundColor: "rgba(0,255,51,0.6)"
            }]
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: "chart.php",
            method: "GET",
            dataType: 'JSON',
            success: function(data) {
                console.log(data);
                var raw = [];
                var qty = [];

                for (var i in data) {
                    raw.push(data[i].name);
                    qty.push(data[i].quantity);
                }
                console.log(raw);
                console.log(qty);
                var chartdata = {
                    labels: raw,
                    datasets: [{
                        label: 'Product Stock',
                        backgroundColor: 'rgba(0,200,225,.5)',
                        borderColor: 'rgba(200, 200, 200, 0.75)',
                        hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
                        hoverBorderColor: 'rgba(200, 200, 200, 1)',
                        data: qty
                    }]
                };

                var ctx = $('#myChart2');

                var barGraph = new Chart(ctx, {
                    type: 'bar',
                    data: chartdata
                });
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

</script>
<?php
} // End of role check
if ($user_role == 'employer') {
    include 'includes/footer.php';
}
?>