<?php include('../consts/db.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>mySQL - GUI</title>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <script src="includes/jquery/jquery-3.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="includes/bootstrap/3.3.5/css/bootstrap.css" />
    <script src="includes/bootstrap/3.3.5/table/src/bootstrap.table.fix.min.js"></script>
    <link rel="stylesheet" href="includes/bootstrap/3.3.5/table/src/bootstrap-table.css" />
    <script src="includes/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="includes/style.css" />
    <script src="includes/script.js"></script>

    <?php
    $projectID = $_GET["projectID"];

    // Create connection
    $conn = new mysqli($servername, $username, $password,$db);

    $sql = "SELECT * FROM projects WHERE project_id=$projectID";
    $result = $conn->query($sql);
    $rs = $result->fetch_assoc();

    $projectName = $rs['projectName'];
    $clientName  = $rs['clientName'];
    $startDate   = $rs['startDate'];

    $sql = "SELECT * FROM development_topics ORDER BY name ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($rs = $result->fetch_assoc()) {
            $slctToolDevTopic .= "<option value='".$rs["dev_topic_id"] . "'>". $rs["name"]  ."</option>";
        }
    }

    $conn->close();
    ?>
</head>
<body data-page="project">
    <header class="mainMenu"><?php include('menu.php');?></header>
    <main>
        <div class="row col-xs-12 text-center form-group">
            <div class="col-xs-2 text-right">
                <button class="btn btn-default btnLink" data-href="projectsManager.php">
                    <span class="glyphicon glyphicon-arrow-right"></span> חזרה
                </button>
            </div>
            <div class="col-xs-8 text-center">
                <h2>
                    פרויקט: <?php echo $projectID;?> -
                    <span id="spanProjectName"><?php echo $projectName?></span>
                </h2>
            </div>
            <div class="col-xs-2 text-left">
                <button id="btnEditRow" class="btn btn-default" data-toggle="modal" data-target="#modalEditRow">
                    <span class="glyphicon glyphicon-edit"></span> עריכה
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 text-right"> <!--column details-->
            <header class="form-group text-center">
                <button class="btn btn-default btn-xs" id="btnAddDevTool">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
                &nbsp;
                <label>כלי פיתוח:</label>
            </header>
                <table class="table table-bordered table-responsive table-hover tableDB" id="tableDevTools"
                    data-unique-id="rowID" data-json="developmentToolsPerProject.php?projectID=<?php echo $projectID;?>"
                    data-sort-name="topic" data-sort-order="asc">
                    <thead>
                        <tr>
                            <th data-field="rowID" data-visible="false">קוד</th>
                            <th class="col-md-6 text-center" data-field="topic" data-sortable="true">שלב פיתוח</th>
                            <th class="col-md-6 text-center" data-field="tool" data-sortable="true">כלי פיתוח</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--loaded by ajax-->
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4 text-center"> <!--column milestones-->
                <header class="form-group text-center">
                    <button class="btn btn-default btn-xs" id="btnAddMilestone">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                    &nbsp;
                    <label>אבני דרך:</label>
                </header>
                <table class="table table-bordered table-responsive table-hover tableDB" id="tableMilestones"
                    data-unique-id="rowID" data-json="milestonesPerProject.php?projectID=<?php echo $projectID;?>">
                    <thead>
                        <tr>
                            <th data-field="rowID" data-visible="false">קוד</th>
                            <th class="col-md-6 text-center" data-field="desc" data-sortable="true">תיאור</th>
                            <th class="col-md-2 text-center" data-field="amount" data-sortable="true">רווח[₪]</th>
                            <th class="col-md-3 text-center" data-field="targetDateStr" data-sortable="true">ת. יעד</th>
                            <th data-field="targetDate" data-visible="false"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--loaded by ajax-->
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4 text-left"> <!--column engineers-->
                <header class="form-group text-center">
                    <button class="btn btn-default btn-xs" id="btnAddEngineerToProject" data-toggle="modal" data-target="#modalEngineers">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                    &nbsp;
                    <label>מהנדסי הפרויקט:</label>
                </header>
                <table class="table table-bordered table-responsive table-hover tableDB" id="tableEngineers"
                    data-unique-id="rowID" data-json="engineersPerProject.php?projectID=<?php echo $projectID;?>">
                    <thead>
                        <tr>
                            <th class="col-md-1 text-center" data-field="rowID" data-sortable="true">קוד</th>
                            <th class="col-md-5 text-center" data-field="name" data-sortable="true">שם</th>
                            <th class="col-md-6 text-center col-notHover" data-field="topicName" data-sortable="true">תחום</th>
                            <th data-field="topicID" data-visible="false"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--loaded by ajax-->
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <div class="modal fade" id="modalMilestone" style="direction:rtl;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    &nbsp;&nbsp;
                    <label></label>
                    <button id="btnDeleteMilestone" data-table="projects" data-field="project_id" class="btn btn-xs btn-default" style="float:left">
                        <span class="glyphicon glyphicon-remove"></span>הסרה
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <form id="formMilestone" method="post">
                            <input type="hidden" name="hdnRowID" /><!-- add/edit -->

                            <div class="form-group">
                                <input type="text" class="form-control" id="txtDesc" placeholder="תיאור אבן הדרך.." required/>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="txtAmount">רווח כספי[₪] :</label>
                                <br />
                                <div class="col-xs-3">
                                    <input class="form-control" type="number" id="txtAmount" min="0" value="0" required/>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="txtTargetDate">תאריך יעד :</label>
                                <br />
                                <div class="col-xs-5">
                                    <input class="form-control" type="date" id="txtTargetDate" required/>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-success btn-submit"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEngineers" style="direction:rtl;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    &nbsp;&nbsp;
                    <label>הוספת מהנדס לפרויקט</label>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <table class="table table-bordered table-responsive table-hover tableDB" id="tableAddEngineer"
                            data-unique-id="rowID" data-json="engineersJSON.php" data-search="true">
                            <thead>
                                <tr>
                                    <th class="col-xs-3 text-center" data-field="firstname" data-sortable="true">שם פרטי</th>
                                    <th class="col-xs-3 text-center" data-field="lastname"  data-sortable="true">שם משפחה</th>
                                    <th class="col-md-6 text-center" data-field="topicName" data-sortable="true">תחום</th>
                                    <th data-field="rowID" data-visible="false"></th>
                                    <th data-field="topicID" data-visible="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--loaded by ajax-->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTool" style="direction:rtl;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    &nbsp;&nbsp;
                    <label></label>
                    <button class="btn btn-danger" id="btnDeleteTool" style="float:left">
                        <span class="glyphicon glyphicon-remove"></span> הסרה
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <form id="formTool" method="post">
                            <input type="hidden" name="hdnRowID" /><!-- add/edit -->

                            <div class="form-group">
                                <input class="form-control" id="txtTool" placeholder="כלי פיתוח.." required />
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="slctToolDevTopic">
                                    <?php echo $slctToolDevTopic;?>
                                </select>
                            </div>

                            <div class="form-group text-center">
                                <button class="btn btn-success btn-submit"></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditRow" style="direction:rtl;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    &nbsp;&nbsp;
                    <label>עריכת פרטי פרויקט:</label>
                    <button id="btnDeleteRow" data-table="projects" data-field="project_id" data-rowid="<?php echo $projectID;?>" data-redirect="projectsManager.php" class="btn btn-default btn-danger" style="float:left;">
                        <span class="glyphicon glyphicon-remove"></span>הסר פרויקט
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <form id="formDetails" method="post">
                            <div class="text-center form-group">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="txtProjectName" value="<?php echo $projectName;?>" required />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="txtClientName" value="<?php echo $clientName;?>" placeholder="שם לקוח..." required />
                                </div>
                                <div class="form-group">
                                    <input type="date" class="form-control" name="txtStartDate" value="<?php echo $startDate;?>" required />
                                </div>

                                <div class="form-group text-center">
                                    <button class="btn btn-submit">עריכת פרטים</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="hdnProjectID" value="<?php echo $projectID;?>" />
</body>
</html>
