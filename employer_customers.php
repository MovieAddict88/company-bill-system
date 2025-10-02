<?php
	// Start from getting the hader which contains some settings we need
	require_once 'includes/header.php';

    // Check user role from session and redirect if not authorized
    $user_role = $_SESSION['user_role'] ?? null;
	if ($user_role !== 'employer' && $user_role !== 'admin') {
		$commons->redirectTo(SITE_PATH.'login.php');
    }
?>
		<?php
			require_once "includes/classes/admin-class.php";
			$admins = new Admins($dbh);

            // Fetch all packages for mapping to avoid N+1 queries
            $all_packages = $admins->getPackages();
            $packages_map = [];
            if ($all_packages) {
                foreach ($all_packages as $pkg) {
                    $packages_map[$pkg->id] = $pkg->name;
                }
            }

            // Fetch customers based on role
            $customers = [];
            if ($user_role === 'employer') {
                $location = $_SESSION['user_location'] ?? null;
                if ($location) {
                    // Fetch customers for the employer's location
                    $customers = $admins->fetchCustomersByLocation($location, 1000);
                }
            } elseif ($user_role === 'admin') {
                // Admins see all customers
                $customers = $admins->fetchCustomer(1000);
            }
		?>

	<div class="dashboard">

	<div class="col-md-12 col-sm-12" id="employee_table">
		<div class="panel panel-default">
			<div class="panel-heading">
			<h4>Customers <?php if ($user_role === 'employer' && isset($_SESSION['user_location'])) echo " - Location: " . htmlspecialchars($_SESSION['user_location']); ?></h4>
			</div>
			<div class="panel-body">
				<div class="col-md-6">
				</div>
				<div class="col-md-6">
					<form class="form-inline pull-right">
					  <div class="form-group">
					    <label class="sr-only" for="search">Search for</label>
					    <div class="input-group">
					      <div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
					      <input type="text" class="form-control" id="search" placeholder="Type a name or detail">
					      <div class="input-group-addon"></div>
					    </div>
					  </div>
					</form>
				</div>
			</div>
				<table class="table table-striped" id="grid-basic">
					<thead class="thead-inverse">
						<tr class="info">
							<th>ID </th>
							<th>Name</th>
							<th>NID</th>
							<th>ADDRESS</th>
							<th>Package</th>
							<th>IP </th>
							<th>Email </th>
							<th>Contact</th>
							<th>Type</th>
							<th>Login Code</th>
						</tr>
					</thead>
					<tbody>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $customer):
                            $package_name = $packages_map[$customer->package_id] ?? 'N/A';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($customer->id) ?></td>
                            <td class="search-field"><?= htmlspecialchars($customer->full_name) ?></td>
                            <td class="search-field"><?= htmlspecialchars($customer->nid) ?></td>
                            <td class="search-field"><?= htmlspecialchars($customer->address) ?></td>
                            <td class="search-field"><?= htmlspecialchars($package_name) ?></td>
                            <td class="search-field"><?= htmlspecialchars($customer->ip_address) ?></td>
                            <td class="search-field"><?= htmlspecialchars($customer->email) ?></td>
                            <td class="search-field"><?= htmlspecialchars($customer->contact) ?></td>
                            <td class="search-field"><?= htmlspecialchars($customer->conn_type) ?></td>
                            <td><?= htmlspecialchars($customer->login_code) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No customers found.</td>
                        </tr>
                    <?php endif; ?>
					</tbody>
				</table>
		</div>
	</div>

	<?php
	include 'includes/footer.php';
	?>
	<script type="text/javascript">
	  $(function() {
	    var grid = $('#grid-basic');

	    // handle search fields of members key up event
	    $('#search').keyup(function(e) {
	      var text = $(this).val().toUpperCase();

	      grid.find('tbody tr').each(function() {
	        var rowText = $(this).find('.search-field').text().toUpperCase();
	        if (rowText.indexOf(text) === -1) {
	          $(this).hide();
	        } else {
	          $(this).show();
	        }
	      });
	    });
	  });
	</script>