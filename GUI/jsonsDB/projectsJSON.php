<?php include('../../consts/db.php'); ?>
<?php
$engineer_id = $_POST['engineer_id'];

header('Content-Type: application/json');

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("SET NAMES 'utf8'");

if(is_null($engineer_id) || $engineer_id==''){
    $sql =  "SELECT *,  DATE_FORMAT(startDate, '%d/%m/%Y') as startDateStr FROM projects";
} else {
    $sql =  "
            SELECT p.projectName, p.project_id
            FROM project_participations a
            LEFT JOIN projects p ON a.project_id=p.project_id
            WHERE engineer_id=$engineer_id
            ";
}

$result = $conn->query($sql);

$jsonArr = array();
if ($result->num_rows > 0) {
    while($rs = $result->fetch_assoc()) {
        $jsonArr[] = array(
                            'rowID'         =>  $rs['project_id'],
                            'projectName'   =>  $rs['projectName'],
                            'clientName'    =>  $rs['clientName'],
                            'startDateStr'  =>  $rs['startDateStr'],
                            'startDate'     =>  $rs['startDate']
                            );
    }
}

echo json_encode($jsonArr);

$conn->close();
?>
