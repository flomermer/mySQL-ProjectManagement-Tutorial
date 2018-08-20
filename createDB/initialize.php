<?php include('../consts/db.php'); ?>
<?php
// Create connection
$conn = new mysqli($servername, $username, $password);

// Create database
createDB($conn);
createTables($conn);
initializeValues($conn);

$conn->close();


function createDB($conn){
	$sql = "CREATE DATABASE projectDB";
	if ($conn->query($sql) === TRUE) {
		echo "Database created successfully" . "<BR><BR>";
	} else {
        echo "Database already exists. please delete it and then try again. <BR>";
        echo "or maybe try to procceed into the <a href='../GUI'>GUI</a>";
        exit();
    }
}

function createTables($conn){
    $conn->query("USE projectDB");

    $newTable_software_topics = "CREATE TABLE software_topics (
                                topic_id INT UNSIGNED AUTO_INCREMENT,
                                name VARCHAR(50) NOT NULL,
                                specialty VARCHAR(50) NOT NULL,

                                PRIMARY KEY (topic_id)
                            ) ENGINE=InnoDB";

    $newTable_engineers = "CREATE TABLE engineers (
                            engineer_id INT UNSIGNED AUTO_INCREMENT,
                            software_topic_id INT UNSIGNED NOT NULL,
                            firstname VARCHAR(50) NOT NULL,
                            lastname  VARCHAR(50) NOT NULL,
                            birthdate DATE,
                            address MEDIUMTEXT,

                            PRIMARY KEY (engineer_id),

                            FOREIGN KEY (software_topic_id) REFERENCES software_topics (topic_id)
                            ON DELETE RESTRICT
                            ON UPDATE CASCADE
                    ) ENGINE=InnoDB";

    $newTable_projects = "CREATE TABLE projects (
                        project_id INT UNSIGNED AUTO_INCREMENT,
                        projectName VARCHAR(30) NOT NULL,
                        clientName  VARCHAR(30) NOT NULL,
                        startDate DATE,

                        PRIMARY KEY (project_id)
                    )ENGINE=InnoDB";

    $newTable_milestones = "CREATE TABLE milestones (
                            milestone_id INT UNSIGNED AUTO_INCREMENT,
                            project_id INT UNSIGNED NOT NULL,
                            amount DOUBLE,
                            targetDate DATE,
                            description MEDIUMTEXT,

                            PRIMARY KEY (milestone_id),

                            FOREIGN KEY (project_id) REFERENCES projects (project_id)
                            ON DELETE CASCADE
                            ON UPDATE CASCADE
                    ) ENGINE=InnoDB";

    $newTable_development_topics =  "CREATE TABLE development_topics(
                                    dev_topic_id INT UNSIGNED AUTO_INCREMENT,
                                    name VARCHAR(50) NOT NULL,

                                    PRIMARY KEY(dev_topic_id)
                                ) ENGINE=InnoDB";

    $newTable_engineers_phones =    "CREATE TABLE engineers_phones(
                                    engineer_id INT UNSIGNED NOT NULL,
                                    phone VARCHAR(20) NOT NULL,

                                    PRIMARY KEY(engineer_id,phone),

                                    FOREIGN KEY (engineer_id) REFERENCES engineers (engineer_id)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE

                                ) ENGINE=InnoDB";

    $newTable_grades =              "CREATE TABLE grades(
                                    engineer_id INT UNSIGNED NOT NULL,
                                    project_id INT UNSIGNED NOT NULL,
                                    grade TINYINT NOT NULL,
                                    month TINYINT NOT NULL,
                                    year  SMALLINT NOT NULL,

                                    PRIMARY KEY(engineer_id,project_id,month,year),

                                    FOREIGN KEY(engineer_id) REFERENCES engineers(engineer_id)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE,

                                    FOREIGN KEY(project_id) REFERENCES projects(project_id)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE
                                ) ENGINE=InnoDB";

    $newTable_project_participations =  "CREATE TABLE project_participations(
                                    engineer_id INT UNSIGNED NOT NULL,
                                    project_id  INT UNSIGNED NOT NULL,

                                    PRIMARY KEY(engineer_id,project_id),

                                    FOREIGN KEY (engineer_id) REFERENCES engineers (engineer_id)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE,

                                    FOREIGN KEY (project_id) REFERENCES projects (project_id)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE

                                ) ENGINE=InnoDB";

    $newTable_development_tools =  "CREATE TABLE development_tools(
                                    auto_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                    project_id   INT UNSIGNED NOT NULL,
                                    dev_topic_id INT UNSIGNED NOT NULL,
                                    tool VARCHAR(50) NOT NULL,

                                    PRIMARY KEY(auto_id),

                                    FOREIGN KEY (project_id) REFERENCES projects (project_id)
                                    ON DELETE CASCADE
                                    ON UPDATE CASCADE,

                                    FOREIGN KEY (dev_topic_id) REFERENCES development_topics (dev_topic_id)
                                    ON DELETE RESTRICT
                                    ON UPDATE CASCADE

                                ) ENGINE=InnoDB";


    if ($conn->query($newTable_software_topics)==TRUE)
        echo "software_topic TABLE created successfully" . "<BR>";

    if ($conn->query($newTable_engineers)==TRUE)
        echo "engineers TABLE created successfully" . "<BR>";

    if ($conn->query($newTable_projects)==TRUE)
        echo "projects TABLE created successfully" . "<BR>";

    if ($conn->query($newTable_milestones)==TRUE)
        echo "milestones TABLE created successfully" . "<BR>";

    if ($conn->query($newTable_development_topics)==TRUE)
        echo "development_topics TABLE created successfully" . "<BR>";

    if ($conn->query($newTable_engineers_phones)==TRUE)
        echo "engnieers_phones TABLE created successfully" . "<BR>";

    if ($conn->query($newTable_grades)==TRUE)
        echo "grades TABLE created successfully" . "<BR>";

    if ($conn->query($newTable_project_participations)==TRUE)
        echo "project_participations TABLE created successfully" . "<BR>";

    if ($conn->query($newTable_development_tools)==TRUE)
        echo "development_tools TABLE created successfully" . "<BR>";
}

function initializeValues($conn){
    $conn->query("USE projectDB");

    //insertSoftwareTopics($conn); //prepered statement
    $software_topics = "INSERT INTO software_topics (name,specialty) VALUES
                            ('JAVA','FRONTEND'),
                            ('JAVA','BACKEND'),
                            ('JAVA','QA'),
                            ('REACT','FRONTEND'),
                            ('C++', 'FRONTEND'),
                            ('C++', 'BACKEND'),
                            ('C++', 'QA'),
                            ('ANGULAR','FRONTEND'),
                            ('nodeJS','BACKEND'),
                            ('nodeJS','QA')
                            ";

    $engineers =     "INSERT INTO engineers (firstname,lastname,birthdate,address,software_topic_id) VALUES
                            ('aa','AA','2000/1/1','street a',1),
                            ('bb','BB','2000/2/2','street b',2),
                            ('cc','CC','2000/3/3','street c',3),
                            ('dd','DD','2000/4/4','street d',4),
                            ('ee','EE','2000/5/5','street e',5),
                            ('ff','FF','2000/6/6','street f',6),
                            ('gg','GG','2000/7/7','street g',6),
                            ('hh','HH','2000/8/8','street h',7),
                            ('ii','II','2000/9/9','street i',8),
                            ('jj','JJ','2000/10/10','street j',8)
                        ";

    $engineers_phones =      "INSERT INTO engineers_phones (engineer_id,phone) VALUES
                            (1,'100'),
                            (1,'1000'),
                            (2,'200'),
                            (3,'300'),
                            (3,'3000'),
                            (4,'400'),
                            (5,'500'),
                            (6,'600'),
                            (8,'800'),
                            (9,'900'),
                            (9,'9000')
                        ";


    $projects = "INSERT INTO projects (projectName,clientName,startDate) VALUES
                        ('P1','C1','2017/1/1'),
                        ('P2','C2','2017/2/2'),
                        ('P3','C3','2017/3/3'),
                        ('P4','C4','2017/4/4'),
                        ('P5','C5','2017/5/5'),
                        ('P6','C6','2017/6/6'),
                        ('P7','C7','2017/7/7'),
                        ('P8','C8','2017/8/8'),
                        ('P9','C9','2017/9/9'),
                        ('P10','C10','2017/10/10')
                        ";
    $milestones = "INSERT INTO milestones (project_id, amount, targetDate, description) VALUES
                        (1, 1200, '2018/01/20', 'M1'),
                        (1, 3300, '2018/01/25', 'M2'),
                        (1, 990,  '2018/02/06', 'M3'),
                        (2, 840,  '2018/01/18', 'M1'),
                        (2, 6200, '2018/02/24', 'M2'),
                        (2, 1200, '2018/03/20', 'M3'),
                        (3, 3100, '2018/01/24', 'M1'),
                        (3, 4200, '2018/03/20', 'M2'),
                        (5, 1200, '2018/01/20', 'M1'),
                        (5, 1200, '2018/02/20', 'M2')
                        ";

    $project_participations = "INSERT INTO project_participations (engineer_id, project_id) VALUES
                            (1, 1), (1, 3), (1, 4),
                            (2, 1), (2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 7), (2, 8), (2, 9), (2, 10),
                            (3, 3), (3, 5), (3, 7), (3,9),
                            (4, 2), (4, 4), (4, 6), (4, 8), (4, 10),
                            (9, 1), (9, 2), (9, 3), (9, 4), (9, 5), (9, 6), (9, 7), (9, 8), (9, 9), (9, 10)
                            ";

    $development_topics = "INSERT INTO development_topics (name) VALUES
                                ('Configuration Management'),
                                ('Design'),
                                ('Requirements management'),
                                ('Task management'),
                                ('Encoding'),
                                ('Decoding'),
                                ('QA')
                            ";
    $development_tools = "INSERT INTO development_tools (project_id, dev_topic_id, tool) VALUES
                                (1,1,'GIT'),
                                (2,1,'GIT'),
                                (3,1,'GIT'),
                                (1,2,'Photoshop'),
                                (4,2,'Illustrator'),
                                (6,2,'Paint'),
                                (8,2,'Photoshop'),
                                (9,2,'Illustrator'),
                                (5,1,'GitHUB'),
                                (8,1,'GitBucket')
                            ";

    $grades     =   "INSERT INTO grades (engineer_id, project_id, month, year, grade) VALUES
                    (2, 1, 1, 2000, 9),(2, 2, 1, 2000, 6),(2, 3, 1, 2000, 5),(2, 4, 1, 2000, 2),(2, 5, 1, 2000, 8),
                    (2, 6, 1, 2000, 6),(2, 7, 1, 2000, 9),(2, 8, 1, 2000, 7),(2, 9, 1, 2000, 3),(2, 10, 1, 2000, 10),

                    (9, 1, 1, 2000, 8),(9, 2, 1, 2000, 7),(9, 3, 1, 2000, 9),(9, 4, 1, 2000, 3),(9, 5, 1, 2000, 8),
                    (9, 6, 1, 2000, 8),(9, 7, 1, 2000, 9),(9, 8, 1, 2000, 2),(9, 9, 1, 2000, 7),(9, 10, 1, 2000, 9),

                    (4, 2, 1, 2000, 7),(4, 4, 1, 2000, 5),(4, 6, 1, 2000, 6),(4, 8, 1, 2000, 5),(4, 10, 1, 2000, 9)
                    ";

    try{
        $conn->autocommit(FALSE);

        echo "<BR><BR>";

        $conn->query($software_topics);
        $conn->query($engineers);
        $conn->query($engineers_phones);
        $conn->query($projects);
        $conn->query($project_participations); //e2 and e9 participating in all projects
        $conn->query($milestones);
        $conn->query($development_topics);
        $conn->query($development_tools);
        $conn->query($grades);

        $conn->commit();

        echo "software_topics VALUES inserted successfully" . "<BR>";
        echo "engineers VALUES inserted successfully" . "<BR>";
        echo "engineers_phones VALUES inserted successfully" . "<BR>";
        echo "projects VALUES inserted successfully" . "<BR>";
        echo "milestones VALUES inserted successfully" . "<BR>";
        echo "development_topics VALUES inserted successfully" . "<BR>";
        echo "development_tools VALUES inserted successfully" . "<BR>";
        echo "grades VALUES inserted successfully" . "<BR>";

        echo "<BR><BR>";
        echo "<div style='margin:0 auto;font-size:16px;font-weight:bold;'>
                <a href='../GUI'>��� ��� ������ �����</a>
              </div>
            ";
    }
    catch (Exception $e) {
        $conn->rollback();
        echo "error: insert has failed";
    }
}

/*
function insertSoftwareTopics($conn){ //for prepared statement
    $stmt = $conn->prepare("INSERT INTO software_topics (name, specialty) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $specialty);

    // set parameters and execute
    $name = 'JAVA';
    $specialty = 'FRONTEND';
    $stmt->execute();

    $name = 'JAVA';
    $specialty = 'BACKEND';
    $stmt->execute();

    $name = 'JAVA';
    $specialty = 'QA';
    $stmt->execute();

    $name = 'ANGULAR';
    $specialty = 'FRONTEND';
    $stmt->execute();

    echo "New records created successfully";

    $stmt->close();
}
*/
?>
