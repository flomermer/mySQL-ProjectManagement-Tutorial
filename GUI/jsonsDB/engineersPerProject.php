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

$isGroup = false;

$sql =  "SELECT a.engineer_id, b.firstname, b.lastname , c.name AS topicName, c.specialty AS topicSpecialty
        FROM project_participations a
        LEFT JOIN engineers b ON a.engineer_id=b.engineer_id
        LEFT JOIN software_topics c ON b.software_topic_id=c.topic_id
        WHERE project_id=$projectID
        order by c.topic_id ASC
        ";

/*groupBy*/
if($isGroup){
    $sql =  "
        SELECT a.engineer_id, c.name AS topicName, c.specialty AS topicSpecialty,
        b.firstname, b.lastname
        FROM project_participations a
        LEFT JOIN engineers b ON a.engineer_id=b.engineer_id
        LEFT JOIN software_topics c ON b.software_topic_id=c.topic_id
        WHERE project_id=$projectID
        GROUP BY c.topic_id
        order by c.topic_id ASC
        ";
}

$result = $conn->query($sql);

$jsonArr = array();
if ($result->num_rows > 0) {
    while($rs = $result->fetch_assoc()) {
        $jsonArr[] = array(
                            'rowID'         =>  $rs['engineer_id'],
                            'name'          =>  $rs['firstname'] . ' ' . $rs['lastname'],
                            'topicName'     =>  $rs['topicName'] . ' ' . $rs['topicSpecialty']
                            );
    }
}

echo json_encode($jsonArr);

$conn->close();
?>
