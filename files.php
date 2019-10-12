<?php
    require('core.php');
    $dbmsg = "";
    $screenname = "";
    $emailid = "";

    if (!isset($_COOKIE['anyshareAuth'])) {
        echo "nocookie";
    } else {
        $u = User::withToken($_COOKIE['anyshareAuth']);
        $u->doAuthenticate();
        $screenname = $u->screenname;
        $emailid = $u->getEmailID();

        // build filelist for user's drive
        $filelist = array();
        $conn = new mysqli(dbhost, dbun, dbpw, db);
        if ($conn->connect_error) {
            die("Can't connect to database!");
        }

        $flstmt = $conn->prepare("SELECT * FROM files WHERE username = ?");
        $flstmt->bind_param("s", $u->username);
        $flstmt->execute();
        $flresult = $flstmt->get_result();
        if ($flresult->num_rows > 0) {
            while($row = $flresult->fetch_assoc()) {
                $filelist[$row['fileid']]['name'] = $row['filename'];
                $filelist[$row['fileid']]['size'] = $row['size'];
                $filelist[$row['fileid']]['dlink'] = buildDirectDownloadLink($row['fileid']);
                $filelist[$row['fileid']]['datemodified'] = date("d-m-Y h:i:s a", strtotime($row['datemodified']));
                $shareinfostmt = $conn->prepare("SELECT shareinfo FROM shares WHERE fileid = ?");
                $shareinfostmt->bind_param("s", $row['fileid']);
                $shareinfostmt->execute();
                $shareinforesult = $shareinfostmt->get_result();
                if ($shareinforesult->num_rows > 0) {
                    while ($srow = $shareinforesult->fetch_assoc()) {
                        $filelist[$row['fileid']]['shareinfojson'] = $srow['shareinfo'];
                    }
                }
            }
        }
        //var_dump($filelist);

        // build table rows
        $tablerows = "";
        foreach ($filelist as $fileid => $file) {
            $filesize = round($file['size'] / 1024);
            if ($filesize == 0) {
                $filesize = $file['size'];
                $sizeunit = "B";
            } else {
                $sizeunit = "KB";
            }
            $tablerow = "<tr>
                            <td>
                                <label class='au-checkbox'>
                                    <input type='checkbox'>
                                    <span class='au-checkmark'></span>
                                </label>
                            </td>
                            <td><i class='far fa-file-image m-r-10' style='font-size: 18px;'></i></td>
                            <td>" . $file['name'] . "</td>
                            <td>
                                <span>" . $filesize . " " . $sizeunit . "</span>
                            </td>
                            <td class='desc'>" . $file['datemodified'] . "</td>
                            <td>
                                <div class='table-data-feature'>
                                    <button class='item' data-toggle='tooltip' data-placement='top' title='Share'>
                                        <i class='fas fa-share'></i>
                                    </button>
                                    <button class='item' data-toggle='tooltip' onclick='downloadFile(\"". $fileid . "\")' data-placement='top' title='Download'>
                                        <i class='fas fa-arrow-down' onclick=></i>
                                    </button>
                                    <button class='item' data-toggle='tooltip' data-placement='top' title='Delete'>
                                        <i class='zmdi zmdi-delete'></i>
                                    </button>
                                    <button class='item' data-toggle='tooltip' data-placement='top' title='Info'>
                                        <i class='zmdi zmdi-info'></i>
                                    </button>
                                </div>
                            </td>
                        </tr>";
            $tablerows .= $tablerow;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Files</title>

    <!-- Modal Button CSS-->
    <link href="css/modalbutton.css" rel="stylesheet" media="all">

    <!-- Upload CSS -->
    <link href="css/updprogressbar.css" rel="stylesheet" media="all">

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

    <style>
        .uploadTable {

        }

        .uploadTable tr th {
            padding-left: 20px;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-right: 20px;
        }

        .uploadTable tr td {
            padding-left: 20px;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-right: 20px;
        }

        .uploadTable tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .uploaddiv {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            margin-right: 10px;
            z-index: 1;
        }

        .uploadbuttonbox {
            position: absolute;
            font-size: 50px;
            opacity: 0;
            right: 0;
            top: 0;
        }

        .uploaddiv input {
            cursor: pointer;
            display: none;
        }

        .uploaddiv a {
            cursor: pointer;
        }
    </style>
</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
                            <img id="productlogo" src="images/icon/logo.png" alt="CoolAdmin" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo file_get_contents('defaultmobile.menu'); ?>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
                    <img src="images/icon/logo.png" alt="Cool Admin" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <?php echo file_get_contents('default.menu'); ?>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">
                                <input class="au-input au-input--xl" type="text" name="search" placeholder="Search for files...">
                                <button class="au-btn--submit" type="submit">
                                    <i class="zmdi zmdi-search"></i>
                                </button>
                            </form>
                            <div class="header-button">
                                <div class="noti-wrap">
                                    <div class="noti__item js-item-menu">
                                        <div class="mess-dropdown js-dropdown">
                                            <div class="mess__title">
                                                <p>You have 2 news message</p>
                                            </div>
                                            <div class="mess__item">
                                                <div class="image img-cir img-40">
                                                    <img src="images/icon/avatar-06.jpg" alt="Michelle Moreno">
                                                </div>
                                                <div class="content">
                                                    <h6>Michelle Moreno</h6>
                                                    <p>Have sent a photo</p>
                                                    <span class="time">3 min ago</span>
                                                </div>
                                            </div>
                                            <div class="mess__item">
                                                <div class="image img-cir img-40">
                                                    <img src="images/icon/avatar-04.jpg" alt="Diane Myers">
                                                </div>
                                                <div class="content">
                                                    <h6>Diane Myers</h6>
                                                    <p>You are now connected on message</p>
                                                    <span class="time">Yesterday</span>
                                                </div>
                                            </div>
                                            <div class="mess__footer">
                                                <a href="#">View all messages</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="noti__item js-item-menu">
                                        <div class="email-dropdown js-dropdown">
                                            <div class="email__title">
                                                <p>You have 3 New Emails</p>
                                            </div>
                                            <div class="email__item">
                                                <div class="image img-cir img-40">
                                                    <img src="images/icon/avatar-06.jpg" alt="Cynthia Harvey">
                                                </div>
                                                <div class="content">
                                                    <p>Meeting about new dashboard...</p>
                                                    <span>Cynthia Harvey, 3 min ago</span>
                                                </div>
                                            </div>
                                            <div class="email__item">
                                                <div class="image img-cir img-40">
                                                    <img src="images/icon/avatar-05.jpg" alt="Cynthia Harvey">
                                                </div>
                                                <div class="content">
                                                    <p>Meeting about new dashboard...</p>
                                                    <span>Cynthia Harvey, Yesterday</span>
                                                </div>
                                            </div>
                                            <div class="email__item">
                                                <div class="image img-cir img-40">
                                                    <img src="images/icon/avatar-04.jpg" alt="Cynthia Harvey">
                                                </div>
                                                <div class="content">
                                                    <p>Meeting about new dashboard...</p>
                                                    <span>Cynthia Harvey, April 12,,2018</span>
                                                </div>
                                            </div>
                                            <div class="email__footer">
                                                <a href="#">See all emails</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="noti__item js-item-menu">
                                        <i class="far fa-bell"></i>
                                        <span class="quantity">1</span>
                                        <div class="notifi-dropdown js-dropdown">

                                            <div class="notifi__item">
                                                <div class="bg-c3 img-cir img-40">
                                                    <i class="zmdi zmdi-file-text"></i>
                                                </div>
                                                <div class="content">
                                                    <p>You got a new file</p>
                                                    <span class="date">April 12, 2018 06:50</span>
                                                </div>
                                            </div>
                                            <div class="notifi__footer">
                                                <a href="#">All notifications</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="images/icon/avatar-01.png" alt="[Username]" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#"><?php echo $screenname;?></a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="images/icon/avatar-01.png" alt="[Username]" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#"><?php echo $screenname;?></a>
                                                    </h5>
                                                    <span class="email"><?php echo $emailid;?></span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-account"></i>Account</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-settings"></i>Setting</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-money-box"></i>Billing</a>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="javascript:confirmSignout()">
                                                    <i class="zmdi zmdi-power"></i>Sign out</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- END HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- FILES TABLE -->
                                <h3 class="title-5 m-b-35" id="filesheading">Files</h3>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <button class="au-btn au-btn-icon au-btn--white au-btn--small btn btn-secondary" style="margin-right: 10px; border: 0;">
                                            <i class="fas fa-check"></i>Select All</button>
                                            <!-- Example single danger button -->
                                            <div class="btn-group">
                                              <button type="button" class="au-btn au-btn-icon newfilebutton au-btn--green au-btn--small btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-plus"></i>New
                                              </button>
                                              <div class="dropdown-menu">
                                                <a class="dropdown-item btn-success" href="#">File</a>
                                                <a class="dropdown-item btn-success" href="#">Folder</a>
                                              </div>
                                            </div>
                                    </div>
                                    <div class="table-data__tool-right">
                                        <button class="au-btn au-btn-icon au-btn--small btn btn-danger deletebutton" style="margin-right: 10px; border: 0;">
                                            <i class="fas fa-trash-alt" style="margin: 0"></i></button>
                                        <div style="display: inline-block; margin-right: 10px;">
                                            Sort by:
                                        </div>
                                        <div class="rs-select2--light rs-select2--md" style="display: inline-block">
                                            <select class="js-select2" name="property">
                                                <option selected="selected">Name</option>
                                                <option value="">Last Modified</option>
                                                <option value="">Size</option>
                                                <option value="">File Type</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive table m-b-30" style="border-radius: 8px;">
                                    <table class="table table-borderless" id="filebrowsertable">
                                        <thead style="background: black; color: white;">
                                            <tr>
                                                <th style="width: 60px"></th>
                                                <th style="width: 60px"></th>
                                                <th style="width: 30%">Name</th>
                                                <th>Size</th>
                                                <th>Last Modified</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo $tablerows?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- END FILES TABLE -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Confirm Modal -->
    <div class="modal fade" id="cnfModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="cnfModalTitle">[Title]</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="text-center">
                                <form id="cnfModalForm" method="POST">
                                    <p style="margin-bottom: 32px" id="cnfModalText">[Text]</p>
                                    <div class="container-login100-form-btn" style="margin-top: 24px; margin-bottom: 16px; display: inline-block;">
                                        <div class="wrap-login100-form-btn">
                                            <div class="login100-form-bgbtn"></div>
                                            <button class="login100-form-btn" id="cnfModalButtonConfirm">
                                                [Caption]
                                            </button>
                                        </div>
                                    </div>
                                    <div class="container-login100-form-btn" style="margin-top: 0px; margin-bottom: 16px; display: inline-block;">
                                        <div class="wrap-login100-form-btn">
                                            <div class="login100-form-bgbtn"></div>
                                            <button class="login100-form-btn" id="cnfModalButtonCancel">
                                                [Caption]
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
      </div>
      </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="uploadModalTitle">Upload</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="text-center">
                                <form id="uploadForm" method="POST" action="upload.php" enctype="multipart/form-data">
                                        <div style="border-radius: 5px; border: 1px solid black;">
                                            <table class="uploadTable" style="width: 100%;" id="filetable">
                                                <tr style="color: white; background: black;text-align: left;">
                                                    <th style="width: 10%;">Queue</th>
                                                    <th style="width: 35%;">File Name</th>
                                                    <th style="width: 15%;">Size</th>
                                                    <th style="width: 40%;">Status</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">No files selected for upload</td>
                                                </tr>
                                            </table>
                                        </div>
                                    <div class="container-login100-form-btn" style="margin-top: 24px; margin-bottom: 16px; width: auto; float: right;">
                                        <div class='progress' id="progress_div" style="margin-top: 8px; margin-right: 35px;"><div class='bar' id='bar'></div><div class='percent' id='percent'>0%</div></div>
                                        <div class="wrap-login100-form-btn">
                                            <div class="login100-form-bgbtn"></div>

                                            <div class="file btn btn-primary uploaddiv" onclick="fileselector.click();">
                                                <span onClick="fileselector.click()" style="cursor: pointer">Browse...</span>
                    							<input type="file" id="fileselector" name="files[]" multiple="multiple" style="margin-right: 10px;" class="uploadbuttonbox"/>
                    						</div>

                                        </div>
                                        <div class="wrap-login100-form-btn">
                                            <div class="login100-form-bgbtn"></div>
                                            <input type="submit" name='submit' value="Upload" class="btn btn-success" style="margin-right: 10px" onClick='upload_files();'/>
                                        </div>
                                        <div class="wrap-login100-form-btn">
                                            <div class="login100-form-bgbtn"></div>
                                            <button class="btn btn-danger disabled" onClick="" id="uploadModalButtonConfirm" disabled>
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
      </div>
      </div>
    </div>

    <form id="dashboardform" action="signin.php" method="POST">
        <input type="hidden" name="dbmsg" id="dashboardmsgfield">
    </form>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>

    <!-- Main JS-->
    <script src="js/main_dash.js"></script>

    <!-- Upload JS -->
    <script src="js/upload_progress.js"></script>
    <script src="js/jquery.form.js"></script>

    <script language="javascript">
        $( document ).ready(function() {
            $("[data-toggle=tooltip]").hover(function(){
            	$('.tooltip').css('top',parseInt($('.tooltip').css('left')) + 15 + 'px')
            });
        });
    </script>

    <script language="javascript">
        var filequeuecount = 0;
        var fnarray = [];
        var progressarray = [];
        window.onload = function() {
            fileslink.classList.add("active");

            $('#fileselector').change(function() {
                if (filequeuecount == 0) {
                    filetable.deleteRow(1);
                }
                for (i = 0; i < fileselector.files.length; i++) {
                    if (fnarray.indexOf(fileselector.files[i].name) == -1) {
                        fnarray.push(fileselector.files[i].name);
                        var tablerow = "<td>" + (filequeuecount + 1) + "</td><td>" + fileselector.files[i].name + "</td><td>" + (Math.round((fileselector.files[i].size / 1024) * 10) / 10 ) + " KB</td><td>Ready to upload</td>";
                        r = filetable.insertRow();
                        r.innerHTML = tablerow;
                        progressarray[fileselector.files[i].name] = i;
                        console.log(tablerow);
                        filequeuecount++;
                    }
                }
                console.log(progressarray);
            });
        }

        function downloadFile(fileid) {
            window.open('<?php echo getAppLinkPath() . "directdl.php?fileid="?>' + fileid, "_blank");
        }
    </script>
</body>

</html>
<!-- end document-->
