<?php include('../../consts/db.php'); ?>
﻿<?php
header('Content-Type: application/json');

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET NAMES 'utf8'");

$projectID = $_GET["projectID"];

$sql =  "SELECT *, DATE_FORMAT(a.targetDate, '%d/%m/%Y') as targetDateStr
        FROM milestones a
        WHERE project_id=$projectID";
$result = $conn->query($sql);

$jsonArr = array();
if ($result->num_rows > 0) {
    while($rs = $result->fetch_assoc()) {
        $jsonArr[] = array(
                            'rowID'         =>  $rs['milestone_id'],
                            'amount'        =>  $rs['amount'],
                            'targetDate'    =>  $rs['targetDate'],
                            'targetDateStr' =>  $rs['targetDateStr'],
                            'desc'          =>  $rs['description']
                            );
    }
}

echo json_encode($jsonArr);

$conn->close();
?>
