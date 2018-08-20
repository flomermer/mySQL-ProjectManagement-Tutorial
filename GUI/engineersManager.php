<?php include('../consts/db.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>mySQL - GUI</title>
        <script src="includes/jquery/jquery-3.2.1.min.js"></script>
        <link rel="stylesheet" href="includes/bootstrap/3.3.5/css/bootstrap.css" />
        <script src="includes/bootstrap/3.3.5/table/src/bootstrap.table.fix.min.js"></script>
        <link rel="stylesheet" href="includes/bootstrap/3.3.5/table/src/bootstrap-table.css" />
        <script src="includes/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="includes/style.css" />
        <script src="includes/script.js"></script>

        <?php
            // Create connection
            $conn = new mysqli($servername, $username, $password,$db);

            $sql = "SELECT * FROM software_topics ORDER BY name ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($rs = $result->fetch_assoc()) {
                    $slctTopicOptions .= "<option value='".$rs["topic_id"] . "'>". $rs["name"] . " " . $rs["specialty"] ."</option>";
                }
            }

            $conn->close();
        ?>
    </head>
    <body data-page="engineersManager">
        <header class="mainMenu">
            <?php include('menu.php');?>
        </header>
        <main>
            <div class="row panel">
                <div class="col-md-2 text-right">
                    <button class="btn btn-default btnAddRow" id="btnAddRow">
                        <span class="glyphicon glyphicon-plus"></span>מהנדס חדש
                    </button>
                </div>
                <div class="col-md-8"></div>
                <div class="col-md-2 text-left">
                    <select class="form-control" id="slctSortEngineers">
                        <option value="">כל המהנדסים</option>
                        <option value="occupiedAllProjects">עובדים בכל הפרוייקטים</option>
                    </select>
                </div>
            </div>
            <table class="table table-bordered table-responsive table-hover tableDB" id="tableEngineers"
                data-unique-id="rowID" data-json="engineersJSON.php"
                data-pagination="true" data-page-size="7" data-search="true"
                data-sort-name="firstname" data-sort-order="asc">
                <caption>טבלת מהנדסים</caption>
                <thead>
                    <tr>
                        <th class="col-xs-1 text-center" data-field="rowID" data-sortable="true">קוד</th>
                        <th class="col-xs-2 text-center" data-field="firstname" data-sortable="true">שם פרטי</th>
                        <th class="col-xs-2 text-center" data-field="lastname" data-sortable="true">שם משפחה</th>
                        <th class="col-xs-2 text-center" data-field="topicName" data-sortable="true">התמחות</th>
                        <th class="col-xs-1 text-center" data-field="age" data-sortable="true">גיל</th>
                        <th class="col-xs-2 text-center" data-field="address" data-sortable="true">כתובת</th>
                        <th class="col-xs-2 text-center" data-field="phones" data-sortable="true">טלפון</th>
                        <th class="col-xs-1 text-center" data-field="rank"></th>
                        <th data-field="topic_id" data-visible="false"></th>
                        <th data-field="birthdate" data-visible="false"></th>
                    </tr>
                </thead>
                <tbody>
                    <!--loaded by ajax-->
                </tbody>
            </table>
            <div class="modal fade" id="modalRow" style="direction:rtl;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            &nbsp;&nbsp;
                            <label></label>
                            <button id="btnDeleteRow" data-table="engineers" data-field="engineer_id" class="btn btn-xs btn-default" style="float:left">
                                <span class="glyphicon glyphicon-remove"></span> הסרה
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="content">
                                <form id="formRow" method="post">
                                    <input type="hidden" name="hdnRowID" /><!-- add/edit -->
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="txtEngineerFirstName" placeholder="שם פרטי..." required/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="txtEngineerLastName" placeholder="שם משפחה..." required/>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control" name="slctEngineerTopicID" required>
                                            <option value="">התמחות</option>
                                            <?php echo $slctTopicOptions;?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" class="form-control" name="txtEngineerBirthdate" required>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="txtEngineerAddress" placeholder="כתובת..." ></textarea>
                                    </div>
                                    <div class="form-group rowPhone text-center">
                                        <label for="txtEngineerPhone">
                                            מספרי טלפון:
                                            <button type="button" class="btn btn-success btn-xs btnAddPhone">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </label>
                                        <div class="phones-container">

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
        </main>

        <div class="modal fade" id="modalRank" style="direction:rtl;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        &nbsp;&nbsp;
                        <label></label>
                    </div>
                    <div class="modal-body">
                        <div class="content">
                            <form class="text-left" id="formRankProject">
                                <input type="hidden" name="rankProjectID" id="rankProjectID" />
                                <input type="hidden" name="rankEngineerID" id="rankEngineerID" />

                                <div class="row">
                                    <div class="col-xs-6">
                                        <table class="table table-bordered table-responsive table-hover tableDB" id="tableRankProjects"
                                               data-unique-id="rowID">
                                            <caption>פרוייקטי המהנדס: </caption>
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-1 text-center" data-field="rowID" data-sortable="true">קוד</th>
                                                    <th class="col-xs-11 text-center" data-field="projectName" data-sortable="true">שם פרוייקט</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!--loaded by ajax-->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group row">
                                            <label class="col-xs-6 col-form-label" for="txtRankMonth">חודש: </label>
                                            <div class="col-xs-6">
                                                <input type="number" class="form-control" name="txtRankMonth" min="1" max="12" step="1" required/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-6 col-form-label" for="txtRankYear">שנה: </label>
                                            <div class="col-xs-6">
                                                <input class="form-control" name="txtRankYear" type="number" min="2000" max="<?php echo date("Y");?>"  required/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xs-6 col-form-label" for="txtRankGrade">ציון: </label>
                                            <div class="col-xs-6">
                                                <input class="form-control" name="txtRankGrade" type="number" min="1" max="10" step="1" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br /><br />
                                <div class="row">
                                    <div class="col-xs-12 text-center">
                                        <button class="btn btn-default btn-submit">
                                            <span class="glyphicon glyphicon-signal"></span> דרג פרויקט
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>
