<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>mySQL - GUI</title>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <script src="includes/jquery/jquery-3.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <link rel="stylesheet" href="includes/bootstrap/3.3.5/css/bootstrap.css" />
    <script src="includes/bootstrap/3.3.5/table/src/bootstrap.table.fix.min.js"></script>
    <link rel="stylesheet" href="includes/bootstrap/3.3.5/table/src/bootstrap-table.css" />
    <script src="includes/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="includes/style.css" />
    <script src="includes/script.js"></script>

</head>
<body data-page="projectsRanks">
    <header class="mainMenu"><?php include('menu.php');?>
    </header>
    <main>
        <div class="row">
            <div class="col-xs-4 text-center">
                <table class="table table-bordered table-responsive" id="tableProjectsRanks"
                    data-unique-id="rowID" data-json="projectsRanks.php">
                    <caption>דירוג פרוייקטים</caption>
                    <thead>
                        <tr>
                            <th class="col-xs-9 text-center" data-field="projectName" data-sortable="false">פרוייקט</th>
                            <th class="col-xs-3 text-center" data-field="grade" data-sortable="false">דירוג ממוצע</th>
                            <th data-field="rowID" data-visible="false"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--loaded by ajax-->
                    </tbody>
                </table>
            </div>
            <div class="col-xs-8">
                <div id="container" style="width:100%; height:400px;"></div>
            </div>
        </div>
    </main>
</body>
</html>
