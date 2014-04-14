<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The App</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/main.css" rel="stylesheet" type="text/css"/>

    <script src="assets/js/jquery-2.1.0.min.js" type="text/javascript"></script>
    <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/js/app.js" type="text/javascript"></script>
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="" title="The Application">The App</a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#sidebar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        <div class="nav col-sm-3 col-md-2 sidebar collapse" id="sidebar">
            <p class="nav-title">Team Members</p>
            <ul class="nav nav-sidebar" id="team_members">
            <?php foreach ($members as $member): ?>
                    <li><a href="#" title="View <?=$member['first_name'];?>'s Devices" class="team_member" user-id="<?=$member['id'];?>"><?=$member['first_name'].' '.$member['last_name'];?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" id="team_member_viewport" data-loading-text="Loading...">

            <div id="welcome_content">
                <h4>Welcome to the App</h4>
                <p id="app_description">In order to see a list of devices each team member owns, click his name!</p>
            </div>

            <div style="display: none" id="team_member_data">

                <h3 class="page-header" id="page_header">Employee Devices</h3>
                <div class="table-responsive">
                    <table class="table table-striped" id="devices">
                        <thead>
                        <tr>
                            <th>Device ID</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>In custody from</th>
                            <th>In custody till</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>

            <button type="button" id="reload_btn" class="btn btn-info btn-sm" title="Reload" style="display: none">reload</button>

        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function()
    {
        //-- when the document is ready init the app
        App.run();
    });
</script>
</body>
</html>