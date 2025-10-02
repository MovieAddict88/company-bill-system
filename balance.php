<?php
$conn = new mysqli("localhost", "cornerst_121", "cornerst_121", "cornerst_121");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Count how many rows
$sql_count = "SELECT COUNT(*) AS total_rows FROM bills";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_rows = $row_count['total_rows'] ?? 0;

// Compute total balance
$sql_sum = "SELECT SUM(amount) AS total_balance FROM bills";
$result_sum = $conn->query($sql_sum);
$row_sum = $result_sum->fetch_assoc();
$total_balance = $row_sum['total_balance'] ?? 0;
?>

<table border="1" cellpadding="8">
    <tr>
        <th>Total Records</th>
        <th>Total Balance</th>
    </tr>
    <tr>
        <td><?=$total_rows?></td>
        <td>₱<?=number_format($total_balance, 2)?></td>
    </tr>
</table>
