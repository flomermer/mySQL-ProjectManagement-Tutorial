<?php include('../../consts/db.php'); ?>
<?php
header('Content-Type: application/json');

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET NAMES 'utf8'");

$projectID = $_GET["projectID"];

//SUM(a.amount) AS totalAmount -> better in js

$sql =  "SELECT *, DATE_FORMAT(a.targetDate, '%d/%m/%Y') as targetDateStr, b.projectName
        FROM milestones a
        LEFT JOIN projects b ON a.project_id = b.project_id
        WHERE targetDate<DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND targetDate>=CURDATE()
        ORDER BY a.project_id ASC
        ";
$result = $conn->query($sql);

$jsonArr = array();
if ($result->num_rows > 0) {
    while($rs = $result->fetch_assoc()) {
        $jsonArr[] = array(
                            'rowID'         =>  $rs['milestone_id'],
                            'amount'        =>  $rs['amount'],
                            'targetDate'    =>  $rs['targetDate'],
                            'targetDateStr' =>  $rs['targetDateStr'],
                            'desc'          =>  $rs['description'],
                            'projectName'   =>  $rs['projectName']
                            );
    }
}

echo json_encode($jsonArr);

$conn->close();
?>
