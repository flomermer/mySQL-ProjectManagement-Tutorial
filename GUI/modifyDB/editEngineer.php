<?php include('../../consts/db.php'); ?>
<?php

$rowID      =   $_POST["rowID"];     //edit-new flag

$firstname  =   $_POST["firstname"];
$lastname   =   $_POST["lastname"];
$topic_id   =   $_POST["topic_id"];
$birthdate  =   $_POST["birthdate"];
$address    =   $_POST["address"];
$phones     =   $_POST["phones"];

//echo "$firstname - $lastname - $topic_id - $birthdate - $address - $phones[2]";

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    echo "error: " . $conn->connect_error;
    exit();
}

try{
    $conn->autocommit(FALSE);

    if(is_null($rowID) || $rowID==''){ //add new engineer
        $sql = "INSERT INTO engineers (software_topic_id, firstname, lastname, birthdate,address)
        VALUES ($topic_id,'$firstname','$lastname','$birthdate','$address')";

        $conn->query($sql);

        $new_id = $conn->insert_id;

        foreach($phones as $phone) {
            $sql = "INSERT INTO engineers_phones (engineer_id,phone) VALUES ($new_id,'$phone')";
            $conn->query($sql);
        }
        echo "$new_id";

    } else { //edit engineer

        $sql = "UPDATE engineers
                SET software_topic_id='$topic_id', firstname = '$firstname', lastname = '$lastname', birthdate='$birthdate', address='$address'
                WHERE engineer_id = $rowID
                ";
        $conn->query($sql);

        //delete engineer_phones and add it from scratch
        $sql = "DELETE FROM engineers_phones WHERE engineer_id=$rowID";
        $conn->query($sql);

        foreach($phones as $phone) {
            $sql = "INSERT INTO engineers_phones (engineer_id,phone) VALUES ($rowID,'$phone')";
            $conn->query($sql);
        }
    }

    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    echo "error: insert has failed";
}

$conn->close;

?>
