<?php include('../../consts/db.php'); ?>
<?php
$mode = $_POST['mode'];

header('Content-Type: application/json');

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("SET NAMES 'utf8'");


if($mode=='occupiedAllProjects'){
    $sql = "
            SELECT a.engineer_id, e.firstname, e.lastname, e.address, e.birthdate, TIMESTAMPDIFF(YEAR,e.birthdate,NOW()) AS age
            , GROUP_CONCAT(distinct p.phone SEPARATOR '<BR>') AS phones,
            s.name AS topicName, s.specialty AS topicSpecialty, s.topic_id

            FROM project_participations a

            LEFT JOIN engineers_phones p ON a.engineer_id=p.engineer_id
            LEFT JOIN engineers e ON e.engineer_id=a.engineer_id
            LEFT JOIN software_topics s ON s.topic_id=e.software_topic_id

            WHERE a.engineer_id IN
                (SELECT b.engineer_id FROM project_participations b
                GROUP BY b.engineer_id HAVING COUNT(b.engineer_id)=(SELECT COUNT(project_id) FROM projects))

            GROUP BY engineer_id
            ";


} else {
    $sql =  "
        SELECT a.engineer_id, a.firstname, a.lastname, a.address, a.birthdate, TIMESTAMPDIFF(YEAR,a.birthdate,NOW()) AS age,
        GROUP_CONCAT(distinct b.phone SEPARATOR '<br>') AS phones,
        c.name AS topicName, c.specialty AS topicSpecialty, c.topic_id
        FROM engineers a
        LEFT JOIN engineers_phones b ON a.engineer_id=b.engineer_id
        LEFT JOIN software_topics c ON a.software_topic_id=c.topic_id
        GROUP BY a.engineer_id
        ";
}
$result = $conn->query($sql);

$jsonArr = array();
if ($result->num_rows > 0) {
    while($rs = $result->fetch_assoc()) {
        $jsonArr[] = array(
                            'rowID'   =>  $rs['engineer_id'],
                            'firstname'     =>  $rs['firstname'],
                            'lastname'      =>  $rs['lastname'],
                            'age'           =>  $rs['age'],
                            'address'       =>  $rs['address'],
                            'phones'        =>  $rs['phones'],
                            'topicName'     =>  $rs['topicName'] . " - " . $rs['topicSpecialty'],
                            'topic_id'      =>  $rs['topic_id'],
                            'birthdate'     =>  $rs['birthdate'],
                            'rank'          =>  "<span class='glyphicon glyphicon-signal'></span>"
                            );
    }
}

echo json_encode($jsonArr);

$conn->close();
?>
