<?php
//Include the database configuration file
include 'config/db.php';

if(isset($_POST["location_id"]) && !empty($_POST["location_id"])){
    //Get all branch data
    $query = "SELECT * FROM branches WHERE location_id = ".$_POST['location_id']." ORDER BY name ASC";
    $result = $dbh->query($query);

    //Count total number of rows
    if($result->rowCount() > 0){
        echo '<option value="">Select branch</option>';
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
    }else{
        echo '<option value="">Branch not available</option>';
    }
}
?>