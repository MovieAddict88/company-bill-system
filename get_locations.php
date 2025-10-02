<?php
//Include the database configuration file
include 'config/db.php';

$query = "SELECT * FROM locations ORDER BY name ASC";
$result = $dbh->query($query);

if($result->rowCount() > 0){
    echo '<option value="">Select location</option>';
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }
}else{
    echo '<option value="">Location not available</option>';
}
?>