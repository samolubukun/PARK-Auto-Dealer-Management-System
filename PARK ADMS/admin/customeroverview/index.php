<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<?php
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date("Y-m");

// Handle form submission to update the selected month
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedMonth = isset($_POST['selected_month']) ? $_POST['selected_month'] : date("Y-m");
    
    // Add an option to show all
    if ($selectedMonth == 'all') {
        // Set the selected month to an empty string or any default value
        $selectedMonth = '';
    }
}
?>

<div class="card card-outline rounded-0 card-navy">
    <div class="card-header">
        <h3 class="card-title">Customer Overview Report</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <!-- Month Filter Form -->
            <form method="post" class="mb-3">
                <label for="selected_month">Select Month:</label>
                <select id="selected_month" name="selected_month" class="form-control">
                    <option value="all" <?php echo ($selectedMonth == '') ? 'selected' : ''; ?>>Show All</option>
                    <?php
                    // Add your code to generate month options based on your specific requirements
                    // For simplicity, you can hardcode a range of months here
                    for ($i = 1; $i <= 12; $i++) {
                        $monthValue = date('Y-m', strtotime("2024-$i-01"));
                        echo "<option value='$monthValue' " . ($selectedMonth == $monthValue ? 'selected' : '') . ">" . date('F Y', strtotime("2024-$i-01")) . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary mt-2">Filter</button>
            </form>

            <!-- Search Bar -->
            <div class="mb-3">
                <input type="text" id="customerSearch" class="form-control" placeholder="Enter customer name to find purchases">
            </div>

            <table class="table table-hover table-striped table-bordered" id="transaction-tbl">
                <colgroup>
                    <col width="10%">
                    <col width="35%">
                    <col width="35%">
                    <col width="20%">
                </colgroup>

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Vehicles Purchased</th>
                        <th>Total Money Spent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $i = 1;

                    // Use an associative array to store customer information
                    $customerData = [];

                    // Modify the SQL query to consider the selected month
                    $queryCondition = ($selectedMonth != '') ? "AND date_format(t.date_created, '%Y-%m') = '{$selectedMonth}'" : '';
                    $qry = $conn->query("SELECT t.*, concat(t.firstname,' ', t.lastname) as customer, v.mv_number, v.plate_number, v.price, m.model, b.name as `brand`, ct.name as `car_type` from transaction_list t inner join `vehicle_list` v on t.vehicle_id = v.id inner join model_list m on v.model_id = m.id inner join brand_list b on m.brand_id = b.id inner join car_type_list ct on m.car_type_id = ct.id where v.status = 1 and v.delete_flag = 0 {$queryCondition} order by abs(unix_timestamp(v.date_created)) asc ");

                    while ($row = $qry->fetch_assoc()):
                        $total += $row['price'];

                        // Group transactions by customer name
                        $customerName = $row['customer'];
                        if (!isset($customerData[$customerName])) {
                            $customerData[$customerName] = [
                                'totalSpent' => 0,
                                'cars' => [],
                            ];
                        }

                        // Add transaction details to customer data
                        $customerData[$customerName]['totalSpent'] += $row['price'];
                        $customerData[$customerName]['cars'][] = [
                            'brand' => $row['brand'],
                            'model' => $row['model'],
                            'price' => $row['price'],
                        ];

                    endwhile;

                    // Display customer information in the table
                    foreach ($customerData as $customerName => $customer):
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="">
                                <div style="line-height:1em">
                                    <div><b><?= $customerName ?></b></div>
                                    <div>Total Money Spent: <?= format_num($customer['totalSpent'], 2) ?></div>
                                </div>
                            </td>
                            <td class="">
                                <div style="line-height:1em">
                                    <?php foreach ($customer['cars'] as $car): ?>
                                        <div><b><?= $car['brand'] ?> - <?= $car['model'] ?></b> (<?= format_num($car['price'], 2) ?>)</div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="text-right"><?= format_num($customer['totalSpent'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $("#customerSearch").on("input", function () {
            var searchText = $(this).val().toLowerCase();
            $("#transaction-tbl tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
            });
        });
    });
</script>

